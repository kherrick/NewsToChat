<?php
namespace NewsToChat\Transformer;

use DOMDocument;

class SoylentNewsRSSBot
{
    /**
     * @var string
     */
    private $url;

    /**
     * @var array
     */
    private $pageUrls;

    /**
     * @param string $url
     */
    public function __construct($url)
    {
        $this->url = $url;
        $this->pageUrls = $this->getpageUrls();
    }

    /**
     * get the last two links on the page at the url provided
     * @return array
     */
    private function getpageUrls()
    {
        $newsLinks = [];
        $domDocument = new DOMDocument();

        @$domDocument->loadHTMLFile($this->url);

        foreach ($domDocument->getElementsByTagName('a') as $link) {
            $newsLinks[] = array('url' => $link->getAttribute('href'), 'text' => $link->nodeValue);
        }

        $currentNewsLinkIndex = count($newsLinks) - 1;
        $pastNewsLinkIndex = count($newsLinks) - 2;
        $pageUrl[] = $this->url . $newsLinks[$pastNewsLinkIndex]['url'];
        $pageUrl[] = $this->url . $newsLinks[$currentNewsLinkIndex]['url'];

        return $pageUrl;
    }

    /**
     * get the content, prep, build, and return
     * @return array
     */
    public function transform()
    {
        foreach ($this->pageUrls as $url) {
            $content = file_get_contents($url);
            $articles = explode('<br />', $content);
            $date = substr($url, -15, -5);
            $data[] = $this->buildPageData($articles, $date, $url);

        }

        return array_merge($data[0], $data[1]);
    }

    /**
     * final steps in building out the page data
     * @todo revisit the regex strings, in particular for the url
     * @param  array  $articles
     * @param  string $date
     * @param  string $url
     * @return array
     */
    private function buildPageData(array $articles, $date, $url)
    {
        $regexHead = "/DOCTYPE/";
        $regexUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
        $regexTime = "/(?:(?:([01]?\d|2[0-3]):)?([0-5]?\d):)?([0-5]?\d)/";

        foreach ($articles as $article) {
            if (preg_match($regexUrl, $article, $url) && !preg_match($regexHead, $article)) {
                $description = explode('</span>', $article);
                $description = explode('<a', $description[1]);
                $description = explode(']', $description[0]);
                $description = trim($description[1]);
                $description = substr($description, 0, strlen($description) - 2);

                preg_match($regexTime, $article, $time);

                $data[] = [
                    'dateTime' => "$date $time[0]",
                    'url' => substr($url[0], 0, -1),
                    'description' => $description
                ];
            }
        }

        return $data;
    }
}
