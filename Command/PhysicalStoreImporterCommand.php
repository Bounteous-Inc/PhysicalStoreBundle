<?php
namespace DemacMedia\Bundle\PhysicalStoreBundle\Command;

use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

use DemacMedia\Bundle\PhysicalStoreBundle\Entity\OroPhysicalStoreAccounts;
use DemacMedia\Bundle\PhysicalStoreBundle\Entity\OroPhysicalStoreOrders;
use DemacMedia\Bundle\PhysicalStoreBundle\Entity\OroPhysicalStoreOrderItems;

class PhysicalStoreImporterCommand extends ContainerAwareCommand
{
    const COMMAND_NAME = 'demacmedia:oro:physicalstore:import';

    protected $csvFile;
    protected $csvPath;
    protected $entityManager;
    protected $progress;
    protected $lines;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName(self::COMMAND_NAME)
            ->setDescription('Import a PhysicalStore .csv using command line')
            ->addOption(
                'current-user',
                null,
                InputOption::VALUE_REQUIRED,
                'current-user is required'
            )
            ->addOption(
                'current-organization',
                null,
                InputOption::VALUE_REQUIRED,
                'current-organization is required'
            )
            ->addOption(
                'csvfile',
                null,
                InputOption::VALUE_REQUIRED,
                '.csv file path is required',
                'csv'
            );
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $style = new OutputFormatterStyle('white', 'cyan', array('blink'));
        $output->getFormatter()->setStyle('demac', $style);

        $csvfile = $input->getOption('csvfile');
        $currentUser = $input->getOption('current-user');
        $currentOrganization = $input->getOption('current-organization');

        $errorMessage = sprintf(
            '<comment>Use: %s --file=file.csv --current-user=17 --current-organization=1</comment>',
            self::COMMAND_NAME
        );

        if (!$csvfile || !$currentUser || !$currentOrganization){
            $output->writeln(
                $errorMessage
            );
        }

        $this->showDemacMediaHeader($output);

        $output->writeln(
            sprintf('<comment>Importing .csv: <info>%s</info></comment>',
                $csvfile
            )
        );

        $this->checkIfCsvExist($csvfile, $output);

        $this->progress = $this->getHelper('progress');
        $this->lines = $this->getLines($this->csvPath);
        $this->progress->start($output, $this->lines);

        if (($handle = fopen($this->csvPath, "r")) !== FALSE) {
            $line = 0;
            $header = '';

            while (($data = fgetcsv($handle)) !== FALSE) {
                if (0 == $line) {
                    $header = $data;
                    $header = array_flip($header);
                } else {

                    if ($this->isAccountsParser($header)) {
                        $this->updateAccount($header, $data);
                    }

                    if ($this->isOrdersParser($header)) {
                        $this->updateOrders($header, $data);
                    }

                    if ($this->isOrderItemsParser($header)) {
                        $this->updateOrderItems($header, $data);
                    }
                }

                $line++;

                $this->progress->advance();
            }
            fclose($handle);
        }

        $this->progress->finish();
        $output->writeln('<info>Success!</info>');
        $output->writeln('');
    }


    protected function isAccountsParser($header) {
        $arrayAccounts = [
            'custno',
            'company',
            'contact',
            'title',
            'address1',
            'address2',
            'city',
            'addrstate',
            'zip',
            'country',
            'phone',
            'phone2',
            'source',
            'type',
            'email',
            'custmemo',
            'url'
        ];

        if (sizeof(array_diff(array_flip($header), $arrayAccounts)) < 1 ) {
            return true;
        }
        return false;
    }

    protected function isOrdersParser($header) {
        $arrayOrders = [
            'invno',
            'custno',
            'invdte',
            'shipvia',
            'cshipno',
            'taxrate',
            'tax',
            'invamt',
            'ponum',
            'refno'
        ];

        if (sizeof(array_diff(array_flip($header), $arrayOrders)) < 1 ) {
            return true;
        }
        return false;
    }


    protected function isOrderItemsParser($header) {
        $arrayOrderItems = [
            'invno',
            'custno',
            'item',
            'descrip',
            'taxrate',
            'cost',
            'price',
            'qtyord',
            'qtyshp',
            'extprice',
            'invdte'
        ];

        if (sizeof(array_diff(array_flip($header), $arrayOrderItems)) < 1 ) {
            return true;
        }
        return false;
    }


    protected function updateAccount($header, $data) {
        $entity = null;
        $update = false;

        // Validating required fields
        if (!$data[$header['custno']] ||
            !$data[$header['contact']] ||
            !$data[$header['city']] ||
            !$data[$header['phone']]) {

            return false;
        }

        if ($data[$header['custno']] < 1) {
            return false;
        }

        $data[$header['custno']] = is_numeric($data[$header['custno']])? (int) $data[$header['custno']]: $data[$header['custno']];

        $physicalStoreAccounts = $this->getEntityManager()
            ->getRepository('DemacMediaPhysicalStoreBundle:OroPhysicalStoreAccounts')->findOneBy([
            'custno' => $data[$header['custno']]
        ]);

        if (!$physicalStoreAccounts) {
            $this->getEntityManager()->clear();
            $physicalStoreAccounts = new OroPhysicalStoreAccounts();
        } else {
            $entity = $physicalStoreAccounts;
        }

        foreach($header as $key => $value) {
            $csvValue = utf8_encode($data[$value]);
            if ($data[$value]) {
                if (is_object($entity)){
                    $getValue = call_user_func([$entity, 'get' . ucwords($key)]);
                    if ($csvValue != $getValue) {
                        call_user_func([$physicalStoreAccounts, 'set' . ucwords($key)], $csvValue);
                        $update = true;
                    }
                } else {
                    call_user_func([$physicalStoreAccounts, 'set' . ucwords($key)], $csvValue);
                }
            }
        }

        try {
            if ($update == true && is_object($entity) || !$update && !is_object($entity)) {
                $this->getEntityManager()->persist($physicalStoreAccounts);
                $this->getEntityManager()->flush();
                $this->getEntityManager()->clear();
            }
        } catch (\Doctrine\ORM\ORMException $e) {
            echo $e->getMessage();
        }
    }


    protected function updateOrders($header, $data) {
        $entity = null;
        $update = false;

        if (!$data[$header['custno']] || !$data[$header['invno']]) {
            return false;
        }

        $data[$header['invno']] = is_numeric($data[$header['invno']])? (int) $data[$header['invno']]: $data[$header['invno']];

        $physicalStoreOrders = $this->getEntityManager()
            ->getRepository('DemacMediaPhysicalStoreBundle:OroPhysicalStoreOrders')->findOneBy([
                'invno' => $data[$header['invno']]
            ]);

        if (!$physicalStoreOrders) {
            $this->getEntityManager()->clear();
            $physicalStoreOrders = new OroPhysicalStoreOrders();
        } else {
            $entity = $physicalStoreOrders;
        }

        foreach($header as $key => $value) {
            $csvValue = utf8_encode($data[$value]);
            if ($data[$value]) {
                if (is_object($entity)){
                    $getValue = call_user_func([$entity, 'get' . ucwords($key)]);
                    if ($csvValue != $getValue) {
                        call_user_func([$physicalStoreOrders, 'set' . ucwords($key)], $csvValue);
                        $update = true;
                    }
                } else {
                    call_user_func([$physicalStoreOrders, 'set' . ucwords($key)], $csvValue);
                }
            }
        }

        die($physicalStoreOrders->getInvdte());

        try {
            if ($update == true && is_object($entity) || !$update && !is_object($entity)) {
                $this->getEntityManager()->persist($physicalStoreOrders);
                $this->getEntityManager()->flush();
                $this->getEntityManager()->clear();
            }
        } catch (\Doctrine\ORM\ORMException $e) {
            echo $e->getMessage();
        }
    }


    protected function updateOrderItems($header, $data) {
        $entity = null;
        $update = false;

        if (!$data[$header['invno']]) {
            return false;
        }

        if (preg_match_all('/\.000000/', $data[$header['invno']])) {
            $data[$header['invno']] = (int) $data[$header['invno']];
        }

        if (!$data[$header['custno']]) {

            $custnoEntity = $this->getEntityManager()
                ->getRepository('DemacMediaPhysicalStoreBundle:OroPhysicalStoreOrders')->findOneBy([
                    'invno'  => $data[$header['invno']]
                ]);

            if ($custnoEntity) {
                $data[$header['custno']] = $custnoEntity->getCustno();
                die('achoU!');
            }

            $this->getEntityManager()->clear();

            if (!$data[$header['custno']]) {
                return false;
            } else {
                echo 'Customer Number: ' .PHP_EOL;
                print_r($data);
                die;
            }
        }

        $physicalStoreOrderItems = $this->getEntityManager()
            ->getRepository('DemacMediaPhysicalStoreBundle:OroPhysicalStoreOrderItems')->findOneBy([
            'invno'  => $data[$header['invno']]
        ]);

        if (!$physicalStoreOrderItems) {
            $this->getEntityManager()->clear();
            $physicalStoreOrderItems = new OroPhysicalStoreOrderItems();
        } else {
            $entity = $physicalStoreOrderItems;
        }

        foreach($header as $key => $value) {
            $csvValue = utf8_encode($data[$value]);
            if ($data[$value]) {
                if (is_object($entity)){
                    $getValue = call_user_func([$entity, 'get' . ucwords($key)]);
                    if ($csvValue != $getValue) {
                        if ('invdte' == $key) {
                            $csvValue = new \DateTime($data[$value], new \DateTimeZone('UTC'));
                        }
                        call_user_func([$physicalStoreOrderItems, 'set' . ucwords($key)], $csvValue);
                        $update = true;
                    }
                } else {
                    call_user_func([$physicalStoreOrderItems, 'set' . ucwords($key)], $csvValue);
                }
            }
        }

        try {
            if ($update == true && is_object($entity) || !$update && !is_object($entity)) {
                $this->getEntityManager()->persist($physicalStoreOrderItems);
                $this->getEntityManager()->flush();
                $this->getEntityManager()->clear();
            }
        } catch (\Doctrine\ORM\ORMException $e) {
            echo $e->getMessage();
        }
    }

    protected function showDemacMediaHeader(OutputInterface $output) {
        $output->writeln('');
        $output->writeln('<demac>                                ');
        $output->writeln('                                ');
        $output->writeln('         DemacMedia.com         ');
        $output->writeln('                                ');
        $output->writeln('                                </demac>');
        $output->writeln('');
    }

    protected function checkIfCsvExist($csvfile, OutputInterface $output) {
        if (!is_readable($this->csvPath = $csvfile)){
            $output->writeln('');
            $output->writeln('<error>        ERROR        </error>');
            $output->writeln(
                sprintf('<error>I can\'t read the .csv: %s</error>',
                    $this->csvPath
                )
            );
            $output->writeln('');
            die();
        }
    }

    /**
     * @return EntityManagerInterface
     */
    protected function getEntityManager()
    {
        if (!$this->entityManager) {
            $this->entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');
        }

        $this->entityManager->getConnection()->getConfiguration()->setSQLLogger(null);

        return $this->entityManager;
    }

    /**
     * @param Filename
     * @return String lines
     */
    protected function getLines($file)
    {
        $f = fopen($file, 'rb');
        $lines = 0;

        while (!feof($f)) {
            $lines += substr_count(fread($f, 8192), "\n");
        }

        fclose($f);
        return $lines;
    }
}
