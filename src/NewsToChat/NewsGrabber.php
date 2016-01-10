<?php

namespace NewsToChat;

class NewsGrabber
{
    /**
     * @param  array $sources
     * @return array
     */
    public function __invoke($sources)
    {
        foreach ($sources as $source => $url) {
            $transformerClass = 'NewsToChat\\Transformer\\' . $source;
            $transformer = new $transformerClass($url);
            $result[] = $transformer->transform();
        }

        // merge an arbitrary number of article arrays into a single array
        foreach ($result as $articles) {
            foreach ($articles as $article) {
                $mergedArticles[] = $article;
            }
        }

        // prepare to sort the news
        foreach ($mergedArticles as $key => $value) {
            $data[$key] = $value['dateTime'];
        }

        // sort the news by date
        array_multisort($data, SORT_ASC, $mergedArticles);

        // print_r($mergedArticles); die();
        return $mergedArticles;
    }
}
