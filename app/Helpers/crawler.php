<?php

namespace App\Helpers;

class Crawler {

    public static function requestDataUrl($requestedUrl) {
        // Getting the html content of the inserted url
        $html = file_get_contents($requestedUrl);

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
        // Removing new lines and whitespaces from the body wwith regex
        $clearBodyText = preg_replace(['(\s+)u', '(^\s|\s$)u'], [' ', ''], $bodyText);

        $requestFromServer = [
            'title' => $title,
            'text' => $clearBodyText,
            'html-text' => $html
        ];
        return $requestFromServer;
    }
}
