<?php

namespace App\Controller;

use App\Entity\Account;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Helpers\FunctionHelper;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AccountController extends AbstractController
{

    private $helper;
    private $doctrine;
    public function __construct( FunctionHelper $helper, ManagerRegistry $doctrine)
    {
        $this->helper = $helper;
        $this->doctrine = $doctrine;
    }
    #[Route('/account', name: 'app_account')]
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/CompetitionController.php',
        ]);
    }
    /**
     * Add account.
     *
     * @param Request $request 
     * @return JsonResponse 
     */
    #[Route('account/addAccount', name: 'app_add_account', methods:['POST'])]
    public function addAccount(Request $request): JsonResponse
    {
        $jsonData = $request->getContent();
        $data = json_decode($jsonData, true);
        $numberAccount = $data['numberAccount'];

        try {
            if($numberAccount == '')
            {
                throw new Exception("Brak numeru konta");
            }
            $account = new Account();
            $account->setAccountNumber($numberAccount);
            $this->doctrine->getManager()->persist($account);
            $this->doctrine->getManager()->flush();
        } catch(\Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }

        $message['success'] = true;
        $message['message'] = 'Sukces';
        $response = $this->helper->prepareResponse(true,'post',$message);
        return $this->json($response);
    }
}
