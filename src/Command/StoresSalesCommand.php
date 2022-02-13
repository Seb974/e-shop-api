<?php

namespace App\Command;

use App\Service\Hiboutik\Sale as HiboutikSale;
use App\Repository\StoreRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class StoresSalesCommand extends Command
{
    protected $hiboutik;
    protected $storeRepository;
    protected static $defaultName = 'app:stores:sales';
    protected static $defaultDescription = 'get daily sales from all stores';

    public function __construct(StoreRepository $storeRepository, HiboutikSale $hiboutik)
    {
        parent::__construct();
        $this->hiboutik = $hiboutik;
        $this->storeRepository = $storeRepository;
    }

    protected function configure()
    {
        $this->setDescription(self::$defaultDescription);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $stores = $this->storeRepository->findAll();
            foreach ($stores as $store) {
                $this->hiboutik->getSales($store);
            } 
            $io->success("Les ventes ont bien été importées.");
        } catch (\Exception $e) {
            $io->error("Une erreur est survenue. Veuillez réessayer ultérieurement.");
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
