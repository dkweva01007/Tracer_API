<?php

namespace DB\ServiceBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CountBookingByRecipientCommand extends ContainerAwareCommand {

    protected function configure() {
        $this
                ->setName('DB:count:recipient')
                ->setDescription("Start the count booking by etablisment id")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $controleur = $this->getContainer()->get('db.revival');
        $controleur->getCountBookingByRecipoentAction();
        $output->writeln("200");
    }

}
