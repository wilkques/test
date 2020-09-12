<?php

namespace App\Service\Crawler;

use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;

class CrawlerService
{
    protected static $method = ['url'];

    protected $url;

    /**
     * Undocumented function
     *
     * @param string $url
     * @return void
     */
    public function setUrl(string $url = '')
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return Illuminate\Http\Client\Response
     */
    public function httpGet()
    {
        return Http::get($this->url);
    }

    /**
     * @return string
     */
    public function getDom()
    {
        return $this->httpGet()->body();
    }

    /**
     * @return Symfony\Component\DomCrawler\Crawler
     */
    public function getCrawler()
    {
        return new Crawler($this->getDom());
    }

    public static function __callStatic($method, $arguments)
    {
        if (in_array($method, ['url'])) {
            $method = sprintf("set%s", ucfirst($method));
        }

        return (new static)->$method(...$arguments);
    }
}
