<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
// use Spatie\Crawler\Crawler;
// use Spatie\Crawler\CrawlObservers;
// use Illuminate\Support\Facades\URL;
// use Illuminate\Support\Facades\DB;
// use Psr\Http\Message\ResponseInterface;
// use Psr\Http\Message\UriInterface;
// use GuzzleHttp\Exception\RequestException;
// use Symfony\Component\DomCrawler\Crawler as DomCrawler;
// use Spatie\Crawler\CrawlProfiles\CrawlSubdomains;
// use Spatie\Crawler\CrawlProfiles\CrawlInternalUrls;

class ArticleController extends Controller {
    // View fot the article add page
    public function create() {
        return view('articles.add-article');
    }

    
    public function store() {
        // Validating the fields from the form
        $this->validate(request(), [
            'url' => 'required|unique:articles,url'
        ]);

        // Crawler::create()
        //     ->setCrawlProfile(new CrawlInternalUrls(request('url')))
        //     ->setCrawlObserver(new class extends \Spatie\Crawler\CrawlObservers\CrawlObserver {
        //         public function crawled(UriInterface $url, ResponseInterface $response, ?UriInterface $foundOnUrl = null) {
        //             if ($url->getQuery() != '' || $response->getStatusCode() != 200 || $url->getPath() == "/") {
        //                 return;
        //             }

        //             $domCrawler = new DomCrawler((string)$response->getBody());
        //             // $content = $domCrawler->filterXPath('//text()[not(ancestor::script)]')->text();

        //             foreach ($domCrawler as $item) {
        //                 $article = new Article;
        //                 // $article->title = request('title');
        //                 $article->url = (string) $url . PHP_EOL;
        //                 $article->article_text = "{$item->textContent}" . PHP_EOL;
        //                 $article->id_user = auth()->id();
        //                 $article->save();
        //                 // var_dump($item->textContent);
        //                 // echo "**************************************************";
        //             }

        //             // echo (string) $url . PHP_EOL;
        //         }
        //         public function crawlFailed(UriInterface $url, RequestException $requestException, ?UriInterface $foundOnUrl = null) {
        //             echo $requestException->getMessage() . PHP_EOL;
        //         }

        //         // public function finishedCrawling() {
        //         //     return redirect()->to('/confirm');
        //         // }
        //     })
        //     ->setDelayBetweenRequests(500)
        //     ->startCrawling(request('url'));

        // return redirect()->to('/confirm');

        // Getting the html content of the inserted url
        $url = request('url');
        $html = file_get_contents($url);

        // Creating a new domDocument
        $dom = new \domDocument;

        // Using this functions I'm disabling the potential errors 
        $internalErrors = libxml_use_internal_errors(true);
        libxml_use_internal_errors($internalErrors);

        // Adding the html to my dom
        @$dom->loadHTML($html);

        // Getting the title of this url
        $title = $dom->getElementsByTagName('title')->item(0)->nodeValue;

        // Getting all the scripts from the html and I'm removing them
        // These scripts are in body tag and I don't need
        // to store them into my database
        $scripts = $dom->getElementsByTagName('script');
        $remove = [];
        foreach ($scripts as $item) {
            $remove[] = $item;
        }

        foreach ($remove as $item) {
            $item->parentNode->removeChild($item);
        }

        // Getting the body text without the scripts
        $bodyText = $dom->getElementsByTagName('body')->item(0)->nodeValue;
        // Removing new lines and whitespaces from the body
        $clearBodyText = preg_replace(['(\s+)u', '(^\s|\s$)u'], [' ', ''], $bodyText);

        // dd($title);

        // Creating and adding a new Article into the DB
        $article = new Article;

        $article->title = $title;
        $article->url = $url;
        $article->article_text = $clearBodyText;
        $article->id_user = auth()->id();
        $article->save();

        // Redirecting to confirm page
        return redirect()->to('/confirm');
    }

    // Search Query
    public function search(Request $request) {
        // Get the search value I'm getting from the user
        $search = $request->input('search-bar');

        // Regex pattern that removes any special characters
        $pattern = "/[^a-zA-Z0-9_ ]/";

        // Removing the special characters
        $newSearch = preg_replace($pattern, '', $search);

        // Getting every word from the search input
        $searches = explode(" ", $newSearch);

        $oneWord = 0; // first word needs to be in where clause, not in the orWhere clause
        $firstParams = array();
        $secondParams = array();

        // Creating the queries for each word
        foreach ($searches as $term) {
            $oneWord++;
            if ($oneWord == 1) {
                array_push($firstParams, array('title', 'LIKE', '%' . $term . '%'));
                array_push($secondParams, array(array('article_text', 'LIKE', '%' . $term . '%')));
            } else {
                array_push($secondParams, array(array('title', 'LIKE', '%' . $term . '%')));
                array_push($secondParams, array(array('article_text', 'LIKE', '%' . $term . '%')));
            }
        }

        // Get all the results
        // I'm using an advanced where clause query becaue I need 2 queries for each word
        $articles = Article::query()
            ->where($firstParams)
            ->orWhere(function ($query) use ($secondParams) {
                for ($i = 0; $i < count($secondParams); $i++)
                    $query->orWhere($secondParams[$i]);
            })
            ->get();

        return view('articles.search-results', compact('articles'));
    }
}
