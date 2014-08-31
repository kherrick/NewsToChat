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
    protected $date;

    /**
     * @var string
     */
    protected $time;

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

    public function __construct($date = null, $time = null, $url = null, $description = null, $expired = null)
    {
        $this->date = date_create_from_format('Y-m-d', $date);
        $this->time = date_create_from_format('H:i:s', $time);
        $this->url = $url;
        $this->description = $description;
        $this->expired = $expired;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getDate()
    {
        return $this->date->format('Y-m-d');
    }

    public function setDate($date)
    {
        $this->date = date_create_from_format('Y-m-d', $date);
    }

    public function getTime()
    {
        return $this->time->format('H:i:s');
    }

    public function setTime($time)
    {
        $this->time = date_create_from_format('H:i:s', $time);
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
