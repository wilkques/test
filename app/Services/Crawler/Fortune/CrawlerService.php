<?php

namespace App\Service\Crawler\Fortune;

use App\Models\Fortune;
use App\Service\Crawler\CrawlerInterFace;
use App\Service\Crawler\CrawlerService as BaseCrawlerService;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Symfony\Component\DomCrawler\Crawler;

class CrawlerService implements CrawlerInterFace
{
    /** @var array */
    protected static $astro = [
        '牡羊座', '金牛座', '雙子座', '巨蟹座', '獅子座', '處女座', '天秤座', '天蠍座', '射手座', '摩羯座', '水瓶座', '雙魚座'
    ];

    public function exec()
    {
        collect(self::$astro)->map(function ($item, $index) {
            $executeDay = Carbon::now()->format("Y-m-d");

            $url = $this->getUrl($executeDay, $index);

            $data = [
                'astro' => $item,
                'execute_day' => $executeDay
            ];

            $data += $this->getFortuneData($url);

            Fortune::create($data);
        });
    }

    /**
     * @param string $executeDay
     * @param integer $index
     * @return string
     */
    protected function getUrl(string $executeDay, int $index): string
    {
        return sprintf(
            "https://astro.click108.com.tw/daily.php?iAcDay=%s&iAstro=%s",
            $executeDay,
            $index
        );
    }

    /**
     * @param string $url
     * @return array
     */
    protected function getFortuneData(string $url): array
    {
        $eachData = $this->url($url)->getCrawler()->filterXPath('//div[@class="TODAY_CONTENT"]/p')
            ->each(fn (Crawler $node, $index) => $this->eachCallBack($node, $index));

        return Arr::collapse($eachData);
    }

    /**
     * @param Crawler $node
     * @param integer $index
     * @return array
     */
    protected function eachCallBack(Crawler $node, int $index): array
    {
        $text = $node->text();
        switch ($index) {
            case 0:
                $data = ['fortune' => $text];
                break;
            case 1:
                $data = ['fortune_comment' => $text];
                break;
            case 2:
                $data = ['love' => $text];
                break;
            case 3:
                $data = ['love_comment' => $text];
                break;
            case 4:
                $data = ['cause' => $text];
                break;
            case 5:
                $data = ['cause_comment' => $text];
                break;
            case 6:
                $data = ['money' => $text];
                break;
            case 7:
                $data = ['money_comment' => $text];
                break;
        }

        return $data;
    }

    /**
     * @param string $method
     * @param array $arguments
     * @return App\Service\Crawler\CrawlerService
     */
    public function baseCrawler($method, $arguments)
    {
        return (new BaseCrawlerService)->setService($this)->$method(...$arguments);
    }

    public function __call($method, $arguments)
    {
        return $this->baseCrawler($method, $arguments);
    }

    public static function __callStatic($method, $arguments)
    {
        return (new static)->$method(...$arguments);
    }
}
