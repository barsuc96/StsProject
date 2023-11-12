 <?php

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Messenger\MessageBusInterface;


class GenerateCsvCommandTest extends KernelTestCase
{
    private $doctrine;
    private $messageBus;
    public function __construct()
    {
        $this->doctrine = $this->createMock(ManagerRegistry::class);
        $this->messageBus = $this->createMock(MessageBusInterface::class);
 
        parent::__construct();
    }
    public function testExecute()
    { 
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $command = $application->find('app:get-wallet-history');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['walletId' => 6]);

        $this->assertFileExists(__DIR__ . '/..//../history/history_Bartosz.csv');
    }
}