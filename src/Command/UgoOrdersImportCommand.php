<?php

namespace App\Command;

use App\Entity\Customer;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Finder\Finder;

#[AsCommand(
    name: 'ugo:orders:import',
    description: 'Imports customers & orders.',
)]
class UgoOrdersImportCommand extends Command
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
        parent::__construct();
    }
    private $csvParsingOptions = array(
        'finder_in' => 'app/Resources/',
        'ignoreFirstLine' => true
    );

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->importCustomers();
        $this->importOrders();

        $io->success('Csv files imported successfully.');

        return Command::SUCCESS;
    }

    /**
     * Parse a csv file
     *
     * @return array
     */
    private function parseCSV($fileName): array
    {
        $ignoreFirstLine = $this->csvParsingOptions['ignoreFirstLine'];
        $finder = new Finder();
        $finder->files()->in($this->csvParsingOptions['finder_in'])->name($fileName);
        $csv = '';

        foreach ($finder as $file) { $csv = $file; }
        $keys = [];
        $rows = [];

        if (($handle = fopen($csv->getRealPath(), "r")) !== FALSE) {
            $i = 0;
            while (($data = fgetcsv($handle, null, ";")) !== FALSE) {
                $i++;
                $row = [];
                if ($ignoreFirstLine && $i == 1) {
                    $keys = $data;
                    continue;
                }
                foreach ($data as $key => $value) {
                    $row[$keys[$key]] = $value;
                }
                $rows[] = $row;
            }
            fclose($handle);
        }
        return $rows;
    }

    private function importCustomers(): void
    {
        $entries = $this->parseCSV('customers.csv');

        foreach ($entries as $entry) {

            $customer = new Customer();
            $customer->setCustomerId((int) $entry['customer_id']);
            if (isset($entry['title'])) {
                if ($entry['title'] == 1 ) {
                    $customer->setTitle('mme');
                }
                elseif ($entry['title'] == 2) {
                    $customer->setTitle('mr');
                }
            }
            $customer->setLastname($entry['lastname'] ?? null);
            $customer->setFirstname($entry['firstname'] ?? null);
            $customer->setPostalCode($entry['postal_code'] ?? null);
            $customer->setCity($entry['city'] ?? null);
            $customer->setEmail($entry['email'] ?? null);
            $this->entityManager->persist($customer);
        }
        $this->entityManager->flush();
    }
    private function importOrders(): void
    {
        $entries = $this->parseCSV('purchases.csv');

        foreach ($entries as $entry) {
            $order = new Order();
            $order->setPurchaseIdentifier((int) $entry['purchase_identifier']);
            $order->setCustomerId($this->entityManager->getRepository(Customer::class)->findOneBy(['customer_id' => $entry['customer_id']]));
            $order->setProductId((int) $entry['product_id']);
            $order->setQuantity($entry['quantity']);
            $order->setPrice($entry['price']);
            $order->setCurrency($entry['currency']);
            $order->setDate($entry['date']);
            $this->entityManager->persist($order);
        }
        $this->entityManager->flush();
    }
}
