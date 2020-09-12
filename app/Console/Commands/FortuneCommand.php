<?php

namespace App\Console\Commands;

use App\Models\Fortune;
use App\Service\Crawler\CrawlerService;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Symfony\Component\DomCrawler\Crawler;

class FortuneCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawler:fortune';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '抓取運勢資料';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $astro = [
            '牡羊座', '金牛座', '雙子座', '巨蟹座', '獅子座', '處女座', '天秤座', '天蠍座', '射手座', '摩羯座', '水瓶座', '雙魚座'
        ];

        collect($astro)->map(function ($item, $index) {
            $executeDay = Carbon::now()->format("Y-m-d");

            $url = $this->getUrl($executeDay, $index);

            $data = [
                'astro' => $item,
                'execute_day' => $executeDay
            ];

            $data += $this->getFortuneData($url, $data);

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
        $eachData = CrawlerService::url($url)->getCrawler()->filterXPath('//div[@class="TODAY_CONTENT"]/p')
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
}
