<?php

namespace mindplay\middlemark;

use cebe\markdown\GithubMarkdown;
use cebe\markdown\Markdown;

/**
 * A Markdown parser adapter for the `cebe/markdown` package
 *
 * @link https://packagist.org/packages/cebe/markdown
 */
class CebeMarkdownEngine implements MarkdownEngineInterface
{
    /**
     * @var Markdown
     */
    private $engine;

    /**
     * @param Markdown $engine
     */
    public function __construct(Markdown $engine = null)
    {
        $this->engine = $engine ?: new GithubMarkdown();
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
