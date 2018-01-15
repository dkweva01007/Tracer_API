<?php

namespace DB\ServiceBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Request;

class UpCommand extends ContainerAwareCommand {

    protected function configure() {
        $this
                ->setName('DB:up')
                ->addOption(
                        'host', null, InputOption::VALUE_REQUIRED, 'Database\'s host', null
                )
                ->addOption(
                        'login', null, InputOption::VALUE_REQUIRED, 'Database\'s login', null
                )
                ->addOption(
                        'pswd', null, InputOption::VALUE_REQUIRED, 'Database\'s password', null
                )
                ->addOption(
                        'choose', null, InputOption::VALUE_REQUIRED, 'choose status', null
                )
                ->setDescription("Start the update's protocom")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $controleur = $this->getContainer()->get('db.reservation');
        $data = array('host' => $input->getOption('host'), 'login' => $input->getOption('login'), 'pswd' => $input->getOption('pswd'), 'choose' => $input->getOption('choose'));
        $request = Request::create(NULL, 'POST', $data);
        $path = array(
            'request' => $request,
            '_controller' => 'DBServiceBundle:Reservation:postReservationsmaj'
        );
        $subRequest = $request->duplicate(array(), null, $path);
        $this->getContainer()->get('http_kernel')->handle($subRequest, HttpKernelInterface::SUB_REQUEST);
        $output->writeln("200");
    }

}
