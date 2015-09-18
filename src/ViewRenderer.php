<?php

namespace mindplay\middlemark;

use mindplay\kisstpl\Renderer;

/**
 * This renderer integrates `mindplay/kisstpl` as a view-engine used to render the {@see View} model.
 *
 * @link https://github.com/mindplay-dk/kisstpl
 */
class ViewRenderer implements RendererInterface
{
    /**
     * @var RendererInterface
     */
    protected $engine;

    /**
     * @var Renderer
     */
    protected $service;

    /**
     * @param Renderer                $service the view-service (pre-configured with a Finder for the Document model)
     * @param MarkdownEngineInterface $engine  the Markdown rendering engine to use
     */
    public function __construct(Renderer $service, MarkdownEngineInterface $engine)
    {
        $this->engine = $engine;
        $this->service = $service;
    }

    /**
     * @param Document $doc
     *
     * @return string
     */
    public function render(Document $doc)
    {
        $body = $this->engine->render($doc->getContent());

        $view = $this->createView($doc, $body);

        $layout = $doc->getLayout() ?: 'default';

        return $this->service->capture($view, $layout);
    }

    /**
     * @param Document $doc
     * @param string   $body HTML body content
     *
     * @return View
     */
    protected function createView(Document $doc, $body)
    {
        $view = new View();

        $view->doc = $doc;
        $view->body = $body;
        $view->title = $doc->getTitle() ?: "No Title";

        return $view;
    }
}
