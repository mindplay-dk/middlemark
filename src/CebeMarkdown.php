<?php

namespace mindplay\middlemark;

use cebe\markdown\Markdown;

/**
 * A Markdown parser adapter for the `cebe/markdown` package
 *
 * @link https://packagist.org/packages/cebe/markdown
 */
class CebeMarkdown implements MarkdownInterface
{
    /**
     * @var Markdown
     */
    private $engine;

    /**
     * @param Markdown $engine
     */
    public function __construct(Markdown $engine)
    {
        $this->engine = $engine;
    }

    /**
     * @param string $markdown
     *
     * @return string rendered HTML
     */
    public function render($markdown)
    {
        return $this->engine->parse($markdown);
    }
}
