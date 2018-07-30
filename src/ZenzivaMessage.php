<?php

namespace TuxDaemon\ZenzivaNotificationChannel;

class ZenzivaMessage
{
    /** @var string */
    public $content;

    /**
     * Factory instances.
     *
     * @param string $content
     *
     * @return static
     */
    public static function create($content = '')
    {
        return new static($content);
    }

    /**
     * Default Constructor.
     *
     * @param string $content
     */
    public function __construct($content = '')
    {
        $this->content = $content;
    }

    /**
     * Set content of the SMS message.
     *
     * @param string $content
     *
     * @return $this
     */
    public function content($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content of the SMS message.
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }
}
