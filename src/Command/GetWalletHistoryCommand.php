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
use Exception;
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
    protected function configure(): void
    {
        $this
            ->setDescription('Good morning!')
            ->addArgument('walletId', InputArgument::REQUIRED, "Id portfela");
    }
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $walletId = $input->getArgument('walletId');

        try {
            $result = $this->getWalletHistory($walletId);
            $io->success(json_encode($result));
        } catch (\Exception $e) {
            $io->error($e->getMessage());
            return 1; 
        }
        return 0;
    }
    private function getWalletHistory($walletId){
        try{
        $wallet = $this->doctrine->getRepository(Wallet::class)->findOneBy(array("id" => $walletId));
        if(empty($wallet) or empty($histories = $wallet->getHistories()->toArray()))
        {
            throw new Exception("Brak danych do wydrukowania");
        }
        }catch(\Exception $e) {
            return $e->getMessage(); 
            }
        $dataToCsv = [];
        $dataToCsv[] = ['Portfel', 'Data', 'Akcja'];
        
        foreach($histories as $historie)
            {
                $dataToCsv[] = [$wallet->getName(), $historie->getDate()->format('Y-m-d H:i:s'), $historie->getAction()];   
            }
            $message = new CreatFileCsv($dataToCsv, $wallet->getName() );
            $this->messageBus->dispatch($message); 
            return 1;   
    }
}
?>