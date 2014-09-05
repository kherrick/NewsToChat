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
     * @var boolean
     */
    protected $expired;

    public function __construct($dateTime = null, $url = null, $description = null, $expired = null)
    {
        $this->dateTime = date_create_from_format('Y-m-d H:i:s', $dateTime);
        $this->url = $url;
        $this->description = $description;
        $this->expired = $expired;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getDateTime()
    {
        return $this->dateTime->format('Y-m-d H:i:s');
    }

    public function setDateTime($dateTime)
    {
        $this->dateTime = date_create_from_format('Y-m-d H:i:s', $dateTime);
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getExpired()
    {
        return $this->expired;
    }

    public function setExpired($isExpired)
    {
        $this->expired = $isExpired;
    }
}
