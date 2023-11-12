<?php
namespace App\Command;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Helpers\FunctionHelper;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Entity\Wallet;
use App\Message\CreatFileCsv;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'app:get-wallet-history'
)]
class GetWalletHistoryCommand extends Command
{
    private $doctrine;
    private $messageBus;
    public function __construct(ManagerRegistry $doctrine, MessageBusInterface $messageBus)
    {
        $this->doctrine = $doctrine;
        $this->messageBus = $messageBus;
        parent::__construct();
    }
    //protected static $defaultName = 'app:get-ean-command';
    protected function configure(): void
    {
        $this
            ->setDescription('Good morning!')
            ->addArgument('walletId', InputArgument::REQUIRED, "Id portfela");
    }
    protected function execute(InputInterface $input, OutputInterface $output){
        $io = new SymfonyStyle($input, $output);
        $walletId = $input->getArgument('walletId');
        $result = $this->getWalletHistory($walletId);

        
        $io->success(json_encode($result));

        return 0;
    }
    private function getWalletHistory($walletId){
        try{
        $wallet = $this->doctrine->getRepository(Wallet::class)->findOneBy(array("id" => $walletId));
        $histories = $wallet->getHistories()->toArray();
        }catch(\Exception $e) {
            echo $e;
            }
        $dataToCsv = [];
        $dataToCsv[] = ['Portfel', 'Data', 'Akcja'];
        
        foreach($histories as $historie)
            {
                $dataToCsv[] = [$wallet->getName(), $historie->getDate()->format('Y-m-d H:i:s'), $historie->getAction()];   
            }
            $message = new CreatFileCsv($dataToCsv, $wallet->getName() );
            $this->messageBus->dispatch($message);
       

           
    }
}
?>