<?php

namespace App\Controller;

use App\Entity\Wallet;
use App\Helpers\FunctionHelper;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Account;
use App\Entity\History;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;

class WalletController extends AbstractController
{
    private $helper;
    private $doctrine;
    public function __construct( FunctionHelper $helper, ManagerRegistry $doctrine)
    {
        $this->helper = $helper;
        $this->doctrine = $doctrine;
    }
    #[Route('/wallet', name: 'app_wallet')]
    public function index(): Response
    {
        return $this->render('wallet/index.html.twig', [
            'controller_name' => 'WalletController',
        ]);
    }
        /**
     * Add Wallet.
     *
     * @param Request $request 
     * @return JsonResponse 
     */
    #[Route('/wallet/addWallet', name: 'app_add_wallet', methods:['POST'])]
    public function addWallet(Request $request): JsonResponse
    {   
        $dateTimeImmutable = new \DateTimeImmutable();
        //download and convert from json to arrays
        $jsonData = $request->getContent();
        $data = json_decode($jsonData, true);

        $balance = $data['balance'] ;
        $name = $data['name'];
        $accountId = $data['accountId'];
    
        try {
            if($name == '' or $accountId == '' )
            {
                throw new Exception("Brak wszystkich danych");
            }
            $account = $this->doctrine->getRepository(Account::class)->findOneBy(array("id" => $accountId));
            $history = new History();
            $wallet = new Wallet();
            //prepares and saves to the database
            $wallet->setBalance($balance);
            $wallet->setName($name);
            $wallet->setAccountId($account);
            $history->setAction("Dodaje nowy portfel: " . $name );
            $history->setDate($dateTimeImmutable);
            $wallet->addHistory($history);

            $this->doctrine->getManager()->persist($wallet);
            $this->doctrine->getManager()->persist($history);
            $this->doctrine->getManager()->flush();
        } catch(\Exception $e) {
        $message['message'] = $e->getMessage();
        $response = $this->helper->prepareResponse(false,'post',$message);
        return $this->json($response);
        }

        $message['message'] = 'Sukces';
        $response = $this->helper->prepareResponse(true,'post',$message);
        return $this->json($response);
    }
            /**
     * Adds money to wallet.
     *
     * @param Request $request 
     * @return JsonResponse 
     */
    #[Route('/wallet/addMoney', name: 'app_add_money', methods:['POST'])]
    public function addMoney(Request $request)
    {   
        $dateTimeImmutable = new \DateTimeImmutable();
        $jsonData = $request->getContent();
        $data = json_decode($jsonData, true);
        $money = $data['money'] ;
        $walletId = $data['walletId'];

        
        try {
            $history = new History();
            $history->setAction("Dodanie kwoty: " . $money . " zł");
            $history->setDate($dateTimeImmutable);
            
            $wallet = $this->doctrine->getRepository(Wallet::class)->findOneBy(array("id" => $walletId));
            $history->setWalletId($wallet);
            $wallet->setBalance($wallet->getBalance()+$money);
            $this->doctrine->getManager()->persist($history);
            $this->doctrine->getManager()->persist($wallet);
            $this->doctrine->getManager()->flush();

        } catch(\Exception $e) {
        $message['message'] = $e->getMessage();
        $response = $this->helper->prepareResponse(false,'post',$message);
        return $this->json($response);
        }
        $message['message'] = 'Sukces';
        $response = $this->helper->prepareResponse(true,'post',$message);
        return $this->json($response);
    }
    /**
     * Subtracts money to wallet.
     *
     * @param Request $request 
     * @return JsonResponse 
     */
    #[Route('/wallet/getMoney', name: 'app_get_money', methods:['POST'])]
    public function getMoney(Request $request): JsonResponse 
    {   
        $dateTimeImmutable = new \DateTimeImmutable();
        $jsonData = $request->getContent();
        $data = json_decode($jsonData, true);
        $money = $data['money'] ;
        $walletId = $data['walletId'];

        
        try {
            $history = new History();
            $history->setAction("Wyciagnieta kwota " . $money . " zł");
            $history->setDate($dateTimeImmutable);
            
            $wallet = $this->doctrine->getRepository(Wallet::class)->findOneBy(array("id" => $walletId));
            if($wallet->getBalance() <= $money)
            {
                throw new Exception("Kwota wieksza niz jest na porfelu");
            }
            $history->setWalletId($wallet);
            $wallet->setBalance($wallet->getBalance()-$money);
            $this->doctrine->getManager()->persist($history);
            $this->doctrine->getManager()->persist($wallet);
            $this->doctrine->getManager()->flush();

        } catch(\Exception $e) {
        $message['message'] = $e->getMessage();
        $response = $this->helper->prepareResponse(false,'post',$message);
        return $this->json($response);
        }
        $message['message'] = 'Sukces';
        $response = $this->helper->prepareResponse(true,'post',$message);
        return $this->json($response);
    }
        /**
     * Get balance wallet.
     *
     * @param Request $request 
     * @return JsonResponse 
     */
    #[Route('/wallet/getBalance', name: 'app_GET_balance', methods:['GET'])]
    public function getBalance(Request $request): JsonResponse
    {   
        $dateTimeImmutable = new \DateTimeImmutable();
        $jsonData = $request->getContent();
        $data = json_decode($jsonData, true);
        $walletId = $data['walletId'];
        
        try {
            $wallet = $this->doctrine->getRepository(Wallet::class)->findOneBy(array("id" => $walletId));
            if(empty($wallet))
            {
                throw new Exception("Nie ma tego portfelu w naszej bazie danych");
            }
            $row["id"] = $wallet->getId();
            $row["name"] = $wallet->getName();
            $row["balance"] = $wallet->getBalance();
            $history = new History();
            $history->setAction("Pobranie salda");
            $history->setDate($dateTimeImmutable);
            $history->setWalletId($wallet);
            $this->doctrine->getManager()->persist($history);
            $this->doctrine->getManager()->flush();
        } catch(\Exception $e) {
        $message['message'] = $e->getMessage();
        $response = $this->helper->prepareResponse(false,'get',$message);
        return $this->json($response);
        }
        $responseData[] = $row;
        $data["rows"] = $responseData;
        $message['data'] = $data;
        $message['message'] = 'Sukces';
        $response = $this->helper->prepareResponse(true,'get',$message);
        return $this->json($response);
    }
}
