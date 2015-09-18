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
     * @var Renderer
     */
    protected $service;

    /**
     * @param Renderer $service the view-service (pre-configured with a Finder for the Document model)
     */
    public function __construct(Renderer $service)
    {
        $this->service = $service;
    }

    /**
     * @param View $view
     *
     * @return string
     */
    public function render(View $view)
    {
        $layout = $view->doc->getLayout() ?: 'default';

        return $this->service->capture($view, $layout);
    }
}
