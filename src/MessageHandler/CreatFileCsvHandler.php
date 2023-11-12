<?php
namespace App\MessageHandler;

    use Symfony\Component\Messenger\Attribute\AsMessageHandler;
    use Doctrine\Persistence\ManagerRegistry;
    use App\Helpers\FunctionHelper;
    use App\Message\CreatFileCsv;

#[AsMessageHandler]
class CreatFileCsvHandler{

    private $doctrine;
    private $helper;


    public function __construct(ManagerRegistry $doctrine,   FunctionHelper $helper)
    {
        $this->doctrine = $doctrine;
        $this->helper = $helper;
    }
    public function __invoke(CreatFileCsv $CreatFileCsv)
    { 

        $path = __DIR__ . '/../../history/history_' . $CreatFileCsv->getNameWallet() . '.csv';;

        // Open file to save
        $handle = fopen($path, 'w+');

        // Check file open
        if ($handle === false) {
            throw new \RuntimeException("Nie można otworzyć pliku: $path");
        }

        // Iterate over the data and save it
        foreach ($CreatFileCsv->getDataToCsv() as $row) {
            fputcsv($handle, $row);
        }

        // Close file
        fclose($handle);

        echo "Plik CSV został zapisany.";
       

     }
}
    

?>