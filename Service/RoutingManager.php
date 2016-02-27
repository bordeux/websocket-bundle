<?php
/**
 * @author Krzysztof Bednarczyk
 * User: devno
 * Date: 26.02.2016
 * Time: 10:20
 */

namespace Bordeux\WebsocketBundle\Service;

use Bordeux\WebsocketBundle\Websocket\Websocket;
use React\EventLoop\LoopInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * Class RoutingManager
 * @author Krzysztof Bednarczyk
 * @package Bordeux\WebsocketBundle\Service
 */
class RoutingManager
{
    use ContainerAwareTrait;


    /**
     * @var Kernel
     */
    protected $kernel;

    /**
     * @var string
     */
    protected $blackListedBundles = [];

    /**
     * @var string[]
     */
    protected $blackListedWebsocketFiles = [];

    /**
     * @var ConnectionManager
     */
    protected $connectionManager;

    /**
     * RoutingManager constructor.
     * @author Krzysztof Bednarczyk
     */
    public function __construct(ContainerInterface $container)
    {
        $this->setContainer($container);
        $this->kernel = $this->container->get("kernel");
    }


    /**
     * @author Krzysztof Bednarczyk
     * @return string[]
     */
    public function findWebsocketClasses()
    {
        $dirs = array();
        foreach ($this->kernel->getBundles() as $bundle) {
            if (in_array($bundle->getName(), $this->blackListedBundles)) {
                continue;
            }

            if (!is_dir($websocketDir = $bundle->getPath() . '/Websocket')) {
                continue;
            }
            $dirs[] = $websocketDir;
        }

        foreach (Finder::create()->name('*Websocket.php')->in($dirs)->files() as $file) {
            $filename = $file->getRealPath();
            if (!in_array($filename, $this->blackListedWebsocketFiles)) {
                require_once $filename;
            }
        }
        // It is not so important if these controllers never can be reached with
        // the current configuration nor whether they are actually controllers.
        // Important is only that we do not miss any classes.
        return array_filter(get_declared_classes(), function ($name) {
            return preg_match('/Websocket\\\(.+)Websocket$/', $name) > 0;
        });
    }


    /**
     * @author Krzysztof Bednarczyk
     * @return RouteCollection|Route[]
     */
    public function findRoutes()
    {
        $classes = $this->findWebsocketClasses();

        $allRoutes = new RouteCollection();

        foreach ($classes as $class) {
            /** @var Websocket $instance */
            $instance = new $class(
                $this->getConnectionManager(),
                $this->container
            );


            /** @var Route[]|RouteCollection $routes */
            $routes = new RouteCollection();
            $instance->configureRoutes($routes);

            foreach ($routes as $route) {
                $route->setDefault("_websocket", $instance);
            }
            $allRoutes->addCollection($routes);

            $instance->run();
        }

        return $allRoutes;
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


}