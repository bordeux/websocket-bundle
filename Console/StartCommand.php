<?php
namespace Bordeux\WebsocketBundle\Console;


use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class StartCommand
 * @author Krzysztof Bednarczyk
 * @package Bordeux\WebsocketBundle\Console
 */
class StartCommand extends ContainerAwareCommand
{
    /**
     * @author Krzysztof Bednarczyk
     */
    protected function configure()
    {
        $this
            ->setName('bordeux:websocket:start')
            ->setDescription('Start websocket server')
            ->addOption(
                'port',
                null,
                InputOption::VALUE_OPTIONAL,
                "Port of websocket",
                1337
            )->addOption(
                'ip',
                null,
                InputOption::VALUE_OPTIONAL,
                "IP of websocket",
                '0.0.0.0'
            );
    }


    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Starting server");

        $manager = $this->getContainer()->get("bordeux.websocket.service.connection.manager");


        $manager->setOutput($output);

        $ws = new WsServer($manager);
        $ws->disableVersion(0);
        $server = IoServer::factory(
            new HttpServer($ws),
            (int)$input->getOption('port'),
            $input->getOption('ip')
        );

        $manager->setLoop(
            $server->loop
        );

        $routingManager = $this->getContainer()
            ->get("bordeux.websocket.service.routing.manager");
        $routingManager->setConnectionManager($manager);


        $manager->setRoutes(
            $routingManager->findRoutes()
        );

        $server->run();
    }
}