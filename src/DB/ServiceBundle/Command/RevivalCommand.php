<?php

namespace DB\ServiceBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class RevivalCommand extends ContainerAwareCommand {

    protected function configure() {
        $this
                ->setName('DB:revival:send')
                ->setDescription("Start the revival's protocom")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $controleur = $this->getContainer()->get('db.revival');
        $controleur->getRevivalAction();
        $output->writeln("200");
    }

}
