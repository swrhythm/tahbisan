<?php

namespace App\Support;

use HTMLPurifier;
use HTMLPurifier_Config;

class HtmlSanitizer
{
    /**
     * Whitelist matching the biography editor's toolbar output only —
     * this content is rendered unescaped on public profile pages, so
     * nothing outside this set (scripts, event handlers, styles, iframes)
     * is allowed through.
     */
    public static function biography(string $html): string
    {
        $config = HTMLPurifier_Config::createDefault();
        $config->set('HTML.Allowed', 'p,br,strong,em,u,h2,h3,blockquote,ol,ul,li,a[href|target|rel],img[src|alt|width|height]');
        $config->set('HTML.TargetBlank', true);
        $config->set('URI.AllowedSchemes', ['http' => true, 'https' => true]);
        $config->set('Cache.SerializerPath', storage_path('framework/cache'));

        return (new HTMLPurifier($config))->purify($html);
    }
}
