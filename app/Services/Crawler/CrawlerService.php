<?php

namespace App\Service\Crawler;

use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;

abstract class CrawlerService
{
    /** @var string */
    protected $url;

    abstract function exec();

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
}
