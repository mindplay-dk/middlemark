<?php

namespace mindplay\middlemark;

/**
 * This component is used internally to rewrite URLs in generated HTML documents.
 */
class Rewriter
{
    /**
     * @var string regular expression pattern matching <a> tags
     */
    const PATTERN = <<<'REGEX'
#(\<a\s[^>]*href=)(\"|\'|)([^\" >]*?)(\2[^>]*>.*<\/a\>)#siU
REGEX;

    /**
     * @param string   $html     HTML content
     * @param callable $callback function (string $url) : string
     *
     * @return string filtered HTML content
     */
    public function process($html, callable $callback)
    {
        return preg_replace_callback(
            self::PATTERN,
            function ($matches) use ($callback) {
                unset($matches[0]);

                $matches[3] = $callback($matches[3]);

                return implode('', $matches);
            },
            $html
        );
    }
}
