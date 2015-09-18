<?php

namespace mindplay\middlemark;

/**
 * Naive renderer with no layout, just adds an HTML5 document envelope.
 *
 * Mostly proof of concept, see {@ViewRenderer} for a more useful renderer.
 */
class HtmlRenderer implements RendererInterface
{
    public function render(View $view)
    {
        $title = htmlspecialchars($view->title);
        $body = $view->body;

        return "<!DOCTYPE html><html><head><title>{$title}</title></head><body>{$body}</body></html>";
    }
}
