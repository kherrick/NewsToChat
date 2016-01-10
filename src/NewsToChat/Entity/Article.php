<?php

namespace NewsToChat\Entity;

class Article
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $dateTime;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var bool
     */
    protected $expired;

    public function __construct($dateTime = null, $url = null, $description = null, $expired = null)
    {
        $this->dateTime = date_create_from_format('Y-m-d H:i:s', $dateTime);
        $this->url = $url;
        $this->description = $description;
        $this->expired = $expired;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getDateTime()
    {
        return $this->dateTime->format('Y-m-d H:i:s');
    }

    /**
     * @param string
     * @param return null
     */
    public function setDateTime($dateTime)
    {
        $this->dateTime = date_create_from_format('Y-m-d H:i:s', $dateTime);
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string
     * @param return null
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @param return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string
     * @param return null
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @param return string
     */
    public function getExpired()
    {
        return $this->expired;
    }

    /**
     * @param string
     * @param return null
     */
    public function setExpired($isExpired)
    {
        $this->expired = $isExpired;
    }
}
