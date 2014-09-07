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
            $data[] = $transformer->transform();
        }

        return $data;
    }
}
