<?php

namespace Bordeux\WebsocketBundle\Websocket;

use Symfony\Component\HttpFoundation\Request;
use Ratchet\ConnectionInterface;


/**
 * Class Client
 * @author Krzysztof Bednarczyk
 * @package Bordeux\WebsocketBundle\Websocket
 */
class Client
{

    /**
     * Client id
     *
     * @var string
     */
    protected $id;


    /**
     * HTTP request data
     *
     * @var Request
     */
    protected $request;


    /**
     * Connection from Ratchet
     *
     * @var \Ratchet\Server\IoServer
     */
    protected $connection;

    /**
     * Websocket controller
     *
     * @var Websocket
     */
    protected $websocket;


    /**
     * Client constructor.
     * @author Krzysztof Bednarczyk
     * @param string $id
     * @param Request $request
     */
    public function __construct($id, ConnectionInterface $connection, WebsocketInterface $websocket, Request $request)
    {
        $this->id = $id;
        $this->request = $request;
        $this->connection = $connection;
        $this->websocket = $websocket;
    }


    /**
     * Get id value
     * @author Krzysztof Bednarczyk
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get request value
     * @author Krzysztof Bednarczyk
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @author Krzysztof Bednarczyk
     * @param Message $message
     * @return mixed
     */
    public function sendMessage(Message $message)
    {

        return $this->connection->send(
            $message->getContent()
        );
    }

    /**
     * Get websocket value
     * @author Krzysztof Bednarczyk
     * @return Websocket
     */
    public function getWebsocket()
    {
        return $this->websocket;
    }


    /**
     * @author Krzysztof Bednarczyk
     * @return bool
     */
    public function kill(){
        $this->connection->close();
        return true;
    }
}