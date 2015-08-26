<?php

namespace mindplay\middlemark;

interface FrontMatterInterface
{
    /**
     * @param string $markdown
     *
     * @return Document
     */
    public function parse($markdown);
}
