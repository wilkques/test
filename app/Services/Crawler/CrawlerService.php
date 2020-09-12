<?php

namespace App\Service\Crawler;

use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;

class CrawlerService
{
    /** @var static */
    protected $service;

    /**
     * set url
     *
     * @param string $url
     * @return $this
     */
    public function url(string $url = '')
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return Illuminate\Http\Client\Response
     */
    public function getResponse()
    {
        return Http::get($this->url);
    }

    /**
     * @return string
     */
    public function getDom()
    {
        return $this->getResponse()->body();
    }

    /**
     * @return Symfony\Component\DomCrawler\Crawler
     */
    public function getCrawler()
    {
        return new Crawler($this->getDom());
    }

    /**
     * set Crawler Service
     *
     * @param static $service
     * @return $this
     */
    public function setService($service)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * get Crawler Service
     *
     * @return static
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * execute
     *
     * @return static
     */
    public function execute()
    {
        return $this->getService()->exec();
    }

    public function __call($method, $arguments)
    {
        return $this->getService()->$method(...$arguments);
    }

    public static function __callStatic($method, $arguments)
    {
        return (new static)->$method(...$arguments);
    }
}
