<?php

namespace mindplay\middlemark;

interface DocumentParserInterface
{
    /**
     * @param string      $markdown Markdown content
     * @param string|null $filename source file-name (optional)
     *
     * @return Document
     */
    public function parse($markdown, $filename = null);
}
