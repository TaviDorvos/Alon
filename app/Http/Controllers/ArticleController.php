<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Helpers\Crawler;
use App\Helpers\Search;

class ArticleController extends Controller {
    // View for the article add page
    public function create() {
        return view('articles.add-article');
    }


    public function crawl() {
        // Validating the fields from the form
        $this->validate(request(), [
            'url' => 'required|unique:articles,url'
        ]);
        
        // Getting the inserted url
        $url = request('url');

        // Calling the function 'requestDataUrl' from Helpers 
        // in order get the data we need
        $title = Crawler::requestDataUrl($url)['title'];
        $textContent = Crawler::requestDataUrl($url)['text'];
        $htmlContent = Crawler::requestDataUrl($url)['html-text'];

        // Creating and adding a new Article into the DB
        $article = new Article;

        $article->title = $title;
        $article->url = $url;
        $article->article_text = $textContent;
        $article->html_content = $htmlContent;
        $article->id_user = auth()->id();
        $article->save();

        // Redirecting to confirm page
        return redirect()->to('/confirm');
    }

    // Search Query
    public function search(Request $request) {
        // Get the search value I'm getting from the user
        $searchString = $request->input('search-bar');

        // Calling the function 'search' from Helpers 
        // in order search the requested value
        $articles = Search::search($searchString);

        return view('articles.search-results', compact('articles'));
    }
}
