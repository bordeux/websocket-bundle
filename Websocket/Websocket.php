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
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class Websocket
 * @author Krzysztof Bednarczyk
 * @package Bordeux\WebsocketBundle\Websocket
 */
abstract class Websocket implements WebsocketInterface
{
    use ContainerAwareTrait;

    /**
     * @var ConnectionManager
     */
    protected $connectionManager;


    public function __construct(ConnectionManager $connectionManager, ContainerInterface $container)
    {
        $this->setConnectionManager($connectionManager);
        $this->setContainer($container);
    }

    /**
     * Get connectionManager value
     * @author Krzysztof Bednarczyk
     * @return ConnectionManager
     */
    public function getConnectionManager()
    {
        return $this->connectionManager;
    }

    /**
     * Set connectionManager value
     * @author Krzysztof Bednarczyk
     * @param ConnectionManager $connectionManager
     * @return  $this
     */
    public function setConnectionManager($connectionManager)
    {
        $this->connectionManager = $connectionManager;
        return $this;
    }


    /**
     * @author Krzysztof Bednarczyk
     */
    public function getDoctrine()
    {
        return $this->container->get("doctrine");
    }


    /**
     * Gets a container service by its id.
     *
     * @param string $id The service id
     *
     * @return object The service
     */
    public function get($id)
    {
        return $this->container->get($id);
    }

    /**
     * Returns true if the service id is defined.
     *
     * @param string $id The service id
     *
     * @return bool true if the service id is defined, false otherwise
     */
    public function has($id)
    {
        return $this->container->has($id);
    }

    /**
     * Gets a container configuration parameter by its name.
     *
     * @param string $name The parameter name
     *
     * @return mixed
     */
    protected function getParameter($name)
    {
        return $this->container->getParameter($name);
    }


    /**
     * @author Krzysztof Bednarczyk
     * @return LoopInterface
     */
    public function getLoop()
    {
        return $this->connectionManager->getLoop();
    }

    /**
     * Get container value
     * @author Krzysztof Bednarczyk
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }


    /**
     * @author Krzysztof Bednarczyk
     * @return \Symfony\Component\Console\Output\OutputInterface
     */
    public function getOutput()
    {
        return $this->connectionManager->getOutput();
    }
}