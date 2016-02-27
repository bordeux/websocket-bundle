# Symfony Websocket Bundle
Symfony 3 Symfony bundle


## Installation

```
composer require bordeux/websocket-bundle
```

## Run

```
php app/console bordeux:websocket:bundle
```


## Create Websocket Controller
```php
<?php

/**
 * File must be on <your-boundle>/Websocket/*Websocket.php
 */

namespace Tattool\Bundle\MessagesBundle\Websocket;

use Bordeux\WebsocketBundle\Websocket\Client;
use Bordeux\WebsocketBundle\Websocket\Message;
use Bordeux\WebsocketBundle\Websocket\Websocket;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * Class SimpleWebsocket
 * @author Krzysztof Bednarczyk
 * @package Tattool\Bundle\MessagesBundle\Websocket
 */
class SimpleWebsocket extends Websocket
{


    /**
     * @author Krzysztof Bednarczyk
     * @param RouteCollection $collection
     */
    public function configureRoutes(RouteCollection $collection)
    {
        $collection->add("user.messages", new Route(
            "/ws/user/messages/{id}/{accessToken}/", [
            //defaults
        ], [
                "id" => "\d+", //requirements
                "accessToken" => "[a-zA-Z0-9_\-]+",
            ]
        ));
    }


    /**
     * @author Krzysztof Bednarczyk
     * @param Client $client
     * @return void
     */
    public function onConnect(Client $client)
    {

        /**
         * Example url:
         * wss://localhost.org/ws/user/messages/5/a8f5f167f44f4964e6c998dee827110c/?lorem=95
         */
        /** @var Request $request */
        $request = $client->getRequest();
        $request->getUri(); //uri
        $request->getHost(); //host
        $request->getClientIp(); //client ip


        $request->cookies->get("<your-cookies>");

        $request->attributes->get("id"); //result: 5
        $request->attributes->get("accessToken"); //result: a8f5f167f44f4964e6c998dee827110c


        $request->query->get("lorem"); //result: 95

        $client->getId(); //client id

    }

    /**
     * @author Krzysztof Bednarczyk
     * @param Client $client
     * @return void
     */
    public function onError(Client $client)
    {
        $client->kill(); //kill client ;)
    }

    /**
     * @author Krzysztof Bednarczyk
     * @param Client $client
     * @param Message $message
     * @return void
     */
    public function onMessage(Client $client, Message $message)
    {

        $client->sendMessage(new Message(
            "Hello World!"
        ));
    }

    /**
     * @author Krzysztof Bednarczyk
     * @param Client $client
     * @return void
     */
    public function onDisconnect(Client $client)
    {
    }

    /**
     * @author Krzysztof Bednarczyk
     * @return mixed
     */
    public function run()
    {
        //executed after initialize controller

        $this->getLoop(); //loop factor for async

        $this->getContainer(); //container

        $this->getParameter("doctrine.class"); //symfony parameters

        $this->getDoctrine(); //doctrine
    }

}
```
