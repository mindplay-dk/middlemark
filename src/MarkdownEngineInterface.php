<?php

namespace mindplay\middlemark;

/**
 * This defines a common adapter interface for Markdown engines.
 */
interface MarkdownEngineInterface
{
    /**
     * @param string $markdown
     *
     * @return string rendered HTML
     */
    public function render($markdown);
}
