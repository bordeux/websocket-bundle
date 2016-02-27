# Symfony Websocket Bundle
Simple and great websocket manager.


## Installation

```
composer require bordeux/websocket-bundle
```

## Run

```
php app/console bordeux:websocket:bundle
```

## Edit Appkernel

```php
    public function registerBundles()
    {
        $bundles = array(
			...
            new Bordeux\WebsocketBundle\BordeuxWebsocketBundle(),
			....
        );

        return $bundles;
    }

```

## Create Websocket Controller
```php
<?php

/**
 * File must be on <your-boundle>/Websocket/<your-name>Websocket.php
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


## Configuration init.d

1. Edit  [sf-websocket.sh](https://github.com/bordeux/websocket-bundle/blob/master/Resources/init.d/sf-websocket.sh) from Resources/init.d
2. Execute as root:
```bash
cp <your-path>/init.d/sf-websocket.sh /etc/init.d/sf-websocket
chmod a+x /etc/init.d/sf-websocket
update-rc.d sf-websocket defaults
```


## Configuration nginx proxy

```nginx
upstream websocketServers {
    server 127.0.0.1:1337;
    server 127.0.0.2:1337;
    server 127.0.0.3:1337;
    server 127.0.0.4:1337;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;

    ssl on;
    ssl_certificate /www/cert/fullchain.pem;
    ssl_certificate_key /www/cert/privkey.pem;
    ssl_prefer_server_ciphers on;
    ssl_ciphers 'kEECDH+ECDSA+AES128 kEECDH+ECDSA+AES256 kEECDH+AES128 kEECDH+AES256 kEDH+AES128 kEDH+AES256 DES-CBC3-SHA +SHA !aNULL !eNULL !LOW !kECDH !DSS !MD5 !EXP !PSK !SRP !CAMELLIA !SEED';

    server_name ws.localhost.org;
	charset utf-8;
	client_max_body_size 1M;

	access_log  off;
	error_log  /var/log/sf-websocket/error.log;

    location / {
        proxy_pass http://websocketServers;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "upgrade";
	}
	
}

```



## Connect

```javascript
var connection = new WebSocket('wss://localhost.org/ws/user/messages/5/a8f5f167f44f4964e6c998dee827110c/?lorem=95');

// When the connection is open, send some data to the server
connection.onopen = function () {
	console.log("opened!");
  connection.send('Ping'); // Send the message 'Ping' to the server
};

// Log errors
connection.onerror = function (error) {
  console.log('WebSocket Error ' + error);
};

// Log messages from the server
connection.onmessage = function (e) {
  console.log('Server: ' + e.data);
};
```
