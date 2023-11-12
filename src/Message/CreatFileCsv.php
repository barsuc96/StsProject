<?php
   namespace App\Message;

class CreatFileCsv{


    private readonly array $dataToCsv;
    private readonly string $nameWallet;


    public function __construct(array $dataToCsv, string $nameWallet)
    {
        $this->dataToCsv = $dataToCsv;
        $this->nameWallet = $nameWallet;

    }
    public function getDataToCsv(): array
    {
       return $this->dataToCsv;
    }
    public function getNameWallet(): string
    {
       return $this->nameWallet;
    }
}
?>