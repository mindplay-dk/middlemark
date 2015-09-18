<?php

namespace mindplay\middlemark;

use cebe\markdown\GithubMarkdown;

/**
 * Naive renderer with no layout, just adds an HTML5 document envelope.
 */
class HtmlRenderer implements RendererInterface
{
    /**
     * @var MarkdownEngineInterface
     */
    private $engine;

    public function __construct(MarkdownEngineInterface $engine = null)
    {
        $this->engine = $engine ?: $this->createDefaultEngine();
    }

    public function render(Document $doc)
    {
        $title = htmlspecialchars($doc->getTitle() ?: "No Title");
        $body = $this->engine->render($doc->getContent());

        return "<!DOCTYPE html><html><head><title>{$title}</title></head><body>{$body}</body></html>";
    }

    /**
     * @return CebeMarkdownEngine
     */
    protected function createDefaultEngine()
    {
        return new CebeMarkdownEngine();
    }
}
