<?php

namespace mindplay\middlemark;

/**
 * This the view-model created and rendered by the {@see ViewRenderer}.
 *
 * @see ViewRenderer::createView()
 */
class View
{
    /**
     * @var Document
     */
    public $doc;

    /**
     * @var string page title (in plain text)
     */
    public $title;

    /**
     * @var string rendered Markdown content as HTML
     */
    public $body;
}
