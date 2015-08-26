<?php

namespace mindplay\middlemark;

interface MarkdownInterface
{
    /**
     * @param string $markdown
     *
     * @return string rendered HTML
     */
    public function render($markdown);
}
