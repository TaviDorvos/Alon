<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use Spatie\Crawler\Crawler;
use Spatie\Crawler\CrawlObservers;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\DB;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;

class ArticleController extends Controller {
    public function create() {
        return view('articles.add-article');
    }

    public function store() {
        // validating the fields from the form
        $this->validate(request(), [
            // 'title' => 'required|unique:articles,title',
            'url' => 'required|unique:articles,url'
        ]);

        Crawler::create()
            ->setCrawlObserver(new class extends \Spatie\Crawler\CrawlObservers\CrawlObserver {
                public function crawled(UriInterface $url, ResponseInterface $response, ?UriInterface $foundOnUrl = null) {
                    if ($url->getQuery() != '' || $response->getStatusCode() != 200 || $url->getPath() == "/") {
                        return;
                    }

                    $domCrawler = new DomCrawler((string)$response->getBody());
                    // $content = $domCrawler->filterXPath('//text()[not(ancestor::script)]')->text();

                    foreach ($domCrawler as $item) {
                        $article = new Article;
                        // $article->title = request('title');
                        $article->url = (string) $url . PHP_EOL;
                        $article->article_text = "{$item->textContent}" . PHP_EOL;
                        $article->id_user = auth()->id();
                        $article->save();
                        // var_dump($item->textContent);
                        // echo "**************************************************";
                    }

                    // echo (string) $url . PHP_EOL;
                }
                public function crawlFailed(UriInterface $url, RequestException $requestException, ?UriInterface $foundOnUrl = null) {
                    echo $requestException->getMessage() . PHP_EOL;
                }
            })
            ->setDelayBetweenRequests(500)
            ->startCrawling(request('url'));

        return redirect()->to('/confirm');

        // //getting the body content of the url
        // $url = request('url');
        // $content = file_get_contents($url);
        // $first_explode = explode('<body', $content);
        // //this one will contain the body content which we will store into the database
        // $second_explode = explode('</body>', $first_explode[1]);

        // //creating and adding a new Article into the DB
        // $article = new Article;

        // $article->title = request('title');
        // $article->url = $url;
        // $article->article_text = $second_explode[0];
        // $article->id_user = auth()->id();
        // $article->save();

        // return redirect()->to('/confirm');
    }

    public function search(Request $request) {
        // get the search value I'm getting from the user
        $search = $request->input('search-bar');

        //regex pattern that removes any special characters
        $pattern = "/[^a-zA-Z0-9_ ]/";

        //removing the special characters
        $newSearch = preg_replace($pattern, '', $search);

        //getting every word from the search input
        $searches = explode(" ", $newSearch);

        $oneWord = 0; // first word needs to be in where clause, not in the orWhere clause
        $firstParams = array();
        $secondParams = array();

        //creating the queries for each word
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

        //get all the results
        //i'm using an advanced where clause query becaue I need 2 queries for each word
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
