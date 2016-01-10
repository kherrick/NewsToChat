<?php

namespace NewsToChat\Transformer;

use SimplePie;

class RSS
{
    /**
     * @var array
     */
    private $urls;

    /**
     * @param array $urls
     */
    public function __construct($urls)
    {
        $this->urls = $urls;
    }

    /**
     * get the content, prep, build, and return
     * @return array
     */
    public function transform()
    {
        $feed = new SimplePie();
        $feed->enable_cache(true);
        $feed->set_feed_url($this->urls);
        $feed->set_cache_duration(7200);
        $feed->init();
        $feed->handle_content_type();

        $items = $feed->get_items();

        foreach ($items as $item) {
            $dateTime = $item->get_date('Y-m-d H:i:s');
            $url = $item->get_permalink();
            $description = $item->get_title();

            $data[] = [
                'dateTime' => $dateTime,
                'url' => $url,
                'description' => str_replace(["\n", "\r\n", "\r"], ' ', $description)
            ];
        }

        return $data;
    }
}
