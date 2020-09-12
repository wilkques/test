<?php

namespace App\Http\Controllers;

use App\Models\Fortune;
use App\Service\Crawler\CrawlerService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Symfony\Component\DomCrawler\Crawler;

class TestController extends Controller
{
    public function index(Request $request)
    {
        $astro = [
            '牡羊座', '金牛座', '雙子座', '巨蟹座', '獅子座', '處女座', '天秤座', '天蠍座', '射手座', '摩羯座', '水瓶座', '雙魚座'
        ];

        collect($astro)->map(function ($item, $index) {
            $executeDay = Carbon::now()->format("Y-m-d");

            $url = sprintf(
                "https://astro.click108.com.tw/daily.php?iAcDay=%s&iAstro=%s",
                $executeDay,
                $index
            );

            $data = [
                'astro' => $item,
                'execute_day' => $executeDay
            ];

            CrawlerService::url($url)->getCrawler()->filterXPath('//div[@class="TODAY_CONTENT"]/p')
                ->each(function (Crawler $node, $index) use (&$data) {
                    $text = $node->text();
                    switch ($index) {
                        case 0:
                            $data += ['fortune' => $text];
                            break;
                        case 1:
                            $data += ['fortune_comment' => $text];
                            break;
                        case 2:
                            $data += ['love' => $text];
                            break;
                        case 3:
                            $data += ['love_comment' => $text];
                            break;
                        case 4:
                            $data += ['cause' => $text];
                            break;
                        case 5:
                            $data += ['cause_comment' => $text];
                            break;
                        case 6:
                            $data += ['money' => $text];
                            break;
                        case 7:
                            $data += ['money_comment' => $text];
                            break;
                    }
                });

            Fortune::create($data);
        });
    }
}
