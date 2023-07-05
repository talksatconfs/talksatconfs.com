<?php

use Spatie\Url\Url;

if (! function_exists('twitter_url')) {
    function twitterUrl($handle): Url
    {
        $url = Url::fromString('https://twitter.com/');

        return $url->withPath($handle);
    }
}
if (! function_exists('website_url')) {
    function websiteUrl($website): Url
    {
        return Url::fromString($website);
    }
}
if (! function_exists('website_host')) {
    function websiteHost($website, $trimWww = false): string
    {
        $url = Url::fromString($website);

        if (count(array_filter($url->getSegments())) === 0) {
            $host = $url->getHost();

            return $trimWww ? Str::after($host, 'www.') : $host;
        }

        return $url->getHost().$url->getPath();
    }
}
