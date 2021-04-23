<?php

namespace App\Helpers;

use App\Models\Article;

class Search {

    public static function search($requestedString) {
        // Testing if exists a search between quotation marks
        $quotMarksPattern = '/["].*?["]/';

        if (preg_match($quotMarksPattern, $requestedString) != 0) {
            // We gonna get the value between the quotation marks
            preg_match('/(?<=\")(.*?)(?=\")/',  $requestedString, $valueBetweenQuotMarks);

            // Get all the results where the search matches exactly
            $articles = Article::query()
                ->where('title', 'RLIKE', "[[:<:]]" . $valueBetweenQuotMarks[0] ."[[:>:]]")
                ->orWhere('article_text', 'RLIKE', "[[:<:]]" . $valueBetweenQuotMarks[0] . "[[:>:]]")
                ->get();

            return $articles;

            // If doesn't exists we gonna get the value between the quotation marks
        } else {
            // Regex pattern that removes any special characters
            // remaining only letters, numbers, spaces
            $removeSpecialChars = '/[^a-zA-Z0-9_ ]/';
            // Removing the special characters
            $noSpecialCharsSearch = preg_replace($removeSpecialChars, '', $requestedString);

            // Getting every word from the search input
            $searches = explode(" ",  $noSpecialCharsSearch);

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

            return $articles;
        }
    }
}
