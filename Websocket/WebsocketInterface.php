<?php
/**
 * @author Krzysztof Bednarczyk
 * User: devno
 * Date: 26.02.2016
 * Time: 11:52
 */

namespace Bordeux\WebsocketBundle\Websocket;


use Bordeux\WebsocketBundle\Service\ConnectionManager;
use React\EventLoop\LoopInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\RouteCollection;

/**
 * Interface WebsocketInterface
 * @package Bordeux\WebsocketBundle\Websocket
 */
interface WebsocketInterface
{

    /**
     * WebsocketInterface constructor.
     * @author Krzysztof Bednarczyk
     * @param ConnectionManager $connectionManager
     * @param ContainerInterface $container
     */
    public function __construct(ConnectionManager $connectionManager, ContainerInterface $container);


    /**
     * @author Krzysztof Bednarczyk
     * @return mixed
     */
    public function run();


    /**
     * @author Krzysztof Bednarczyk
     * @param RouteCollection $collection
     * @return void
     */
    public function configureRoutes(RouteCollection $collection);

    /**
     * @author Krzysztof Bednarczyk
     * @param Client $client
     * @return void
     */
    public function onConnect(Client $client);

    /**
     * @author Krzysztof Bednarczyk
     * @param Client $client
     * @return void
     */
    public function onError(Client $client);

    /**
     * @author Krzysztof Bednarczyk
     * @param Client $client
     * @param Message $message
     * @return void
     */
    public function onMessage(Client $client, Message $message);


    /**
     * @author Krzysztof Bednarczyk
     * @param Client $client
     * @return void
     */
    public function onDisconnect(Client $client);
}