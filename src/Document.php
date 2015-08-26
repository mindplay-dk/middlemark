<?php

namespace mindplay\middlemark;

/**
 * @see FrontMatterInterface::parse()
 */
class Document
{
    /**
     * @var string Markdown content (sans front-matter)
     */
    public $markdown;

    /**
     * @var array parsed front-matter data
     */
    public $data;

    /**
     * @param string $markdown
     * @param array  $data
     */
    public function __construct($markdown, array $data)
    {
        $this->markdown = $markdown;
        $this->data = $data;
    }
}
