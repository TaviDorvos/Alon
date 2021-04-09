<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\DB;

class ArticleController extends Controller {
    public function create() {
        return view('articles.add-article');
    }

    public function store() {
        //validating the fields from the form
        $this->validate(request(), [
            'title' => 'required|unique:articles,title',
            'url' => 'required'
        ]);

        //getting the body content of the url
        $url = request('url');
        $content = file_get_contents($url);
        $first_explode = explode('<body', $content);
        //this one will contain the body content which we will store into the database
        $second_explode = explode('</body>', $first_explode[1]);

        //creating and adding a new Article into the DB
        $article = new Article;

        $article->title = request('title');
        $article->url = $url;
        $article->article_text = $second_explode[0];
        $article->id_user = auth()->id();
        $article->save();

        return redirect()->to('/confirm');
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
