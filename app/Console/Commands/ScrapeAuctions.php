<?php

namespace App\Console\Commands;

use App\Models\PriceHistory;
use App\Models\Product;
use App\Models\Scraper;
use App\Models\TypeAuction;
use Carbon\Carbon;
use DOMDocument;
use DOMXPath;
use Exception;
use Illuminate\Console\Command;
use Faker\Factory;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class ScrapeAuctions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:scrape-auctions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run scrapers';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $runTime = Carbon::now();
        $scrapers = Scraper::query()->where('run', 1)->get();
        foreach ($scrapers as $scraper) {
            if (!isset($scraper->last_scraped_at) || $scraper->last_scraped_at < $runTime->subminutes($scraper->period)) {
                $this->scrapeAuction($scraper, $runTime);
            }
        }
    }

    public function scrapeAuction(Scraper $scraper, $runTime)
    {
        $faker = Factory::create();
        $client = new Client([
            'headers' => [
                'User-Agent' => $faker->userAgent()
            ],
            'verify' => false,
        ]);

        try {
            $response = $client->get($scraper->url);
            $html = $response->getBody()->getContents();
            $productsUpdated = $this->extractLiveAuctionProducts($html, $scraper);
            $scraper->update(['last_scraped_at', $runTime]);
            if(count($productsUpdated)) {
                //scrape account
                $accountData = $this->scrapeAccount($scraper->auction);
                //send email
            }
        } catch (Exception $e) {
            $this->line($e->getMessage());
        }
        sleep(0.3);
    }

    private function extractLiveAuctionProducts($html, Scraper $scraper): array
    {
        libxml_use_internal_errors(true);

        $dom = new DOMDocument;
        $dom->loadHTML($html);
        $articleTags = $dom->getElementsByTagName('article');
        $pricesUpdated = [];
        foreach ($articleTags as $tag) {
            $articleHTML = $dom->saveHTML($tag);
            $d = new DOMDocument;
            libxml_use_internal_errors(true);
            $d->loadHTML($articleHTML);
            libxml_clear_errors();
            $xpath = new DOMXPath($d);

            $name = $this->extractTextContent($xpath, '//*[contains(@data-testid, "body-primary")]');
            $targetElement = $xpath->query('//article//button[@aria-label="favorite button" and @aria-pressed="false"]/ancestor::section[contains(@class, "CardPrice__CardPriceSection")]/descendant::a[@data-testid="link"]')->item(0);
            $priceElement = $xpath->query('.//span[@class="FormattedCurrency__StyledFormattedCurrency-sc-1ugrxi1-0 cZCaob"]', $targetElement)->item(0);
            $price = $priceElement ? $priceElement->textContent : '0';
            $price = (int) preg_replace('/[^0-9]/', '', $price);
            $linkElement = $xpath->query('//*[contains(@data-testid, "link")]')->item(0);
            $link = $linkElement ? "https://www.liveauctioneers.com/" . $linkElement->getAttribute('href') : '';
            $this->line('Name: ' . $name . ' Link: ' . $link . ' Price:' . $price);
            $product = Product::query()->where('url', $link)->first();
            if ($product) {
                $product->update([
                    'old_price' => $product->new_price,
                    'new_price' => $price
                ]);
                PriceHistory::query()->create([
                    'product_id' => $product->id,
                    'old_price'  => $product->old_price,
                    'new_price'  => $product->new_price
                ]);
                $pricesUpdated[] = $product;
            } else {
                Product::query()->create([
                    'name' => $name,
                    'url' => $link,
                    'new_price' => $price,
                    'type_auction_id' => $scraper->type_auction_id,
                    'scraper_id' => $scraper->id
                ]);
            }
        }

        return $pricesUpdated;
    }

    public function extractTextContent($xpath, $query)
    {
        $elements = $xpath->query($query);
        $textContent = '';

        foreach ($elements as $element) {
            $textContent .= $element->textContent;
        }

        return $textContent;
    }


    public function scrapeAccount(TypeAuction $auction): array
    {
        $data = [];

        $faker = Factory::create();
        $client = new Client([
            'headers' => [
                'User-Agent' => $faker->userAgent()
            ],
            'verify' => false,
        ]);
        $response = $client->get($auction->link);
        $html = $response->getBody()->getContents();


        return $data;
    }

}
