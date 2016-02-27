<?php
/**
 * @author Krzysztof Bednarczyk
 * User: devno
 * Date: 26.02.2016
 * Time: 12:44
 */

namespace Bordeux\WebsocketBundle\Websocket;

/**
 * Class Message
 * @author Krzysztof Bednarczyk
 * @package Bordeux\WebsocketBundle\Websocket
 */
class Message
{
    /**
     * @var null|string
     */
    protected $content;

    /**
     * Message constructor.
     * @author Krzysztof Bednarczyk
     * @param string $content
     */
    public function __construct($content = null)
    {
        $this->content = $content;
    }


    /**
     * Get content value
     * @author Krzysztof Bednarczyk
     * @return null|string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set content value
     * @author Krzysztof Bednarczyk
     * @param null|string $content
     * @return  $this
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }


}