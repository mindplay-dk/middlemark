<?php

namespace mindplay\middlemark;

use OutOfRangeException;

/**
 * This document model separates meta-data from Markdown content, and provides
 * basic support for front-matter attributes as defined by [Jekyll](https://jekyllrb.com/docs/frontmatter/).
 *
 * @see DocumentParserInterface::parse()
 * @see YamlFrontMatterParser::parse()
 */
class Document
{
    /**
     * @var string source file-name
     */
    protected $filename;

    /**
     * @var string Markdown content (sans front-matter)
     */
    protected $content;

    /**
     * @var array parsed front-matter data
     */
    protected $data;

    /**
     * @param string $filename source file-name
     * @param string $content
     * @param array  $data
     */
    public function __construct($filename, $content, array $data)
    {
        $this->filename = $filename;
        $this->content = $content;
        $this->data = $data;
    }

    /**
     * @return string source file-name
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @return string Markdown content
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $name    attribute name
     * @param mixed  $default optional default value
     *
     * @return mixed
     */
    public function getData($name, $default = null)
    {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }

        if (func_num_args() === 2) {
            return $default;
        }

        throw new OutOfRangeException("undefined meta-data attribute: {$name} (and no default given)");
    }

    /**
     * @return mixed[] the full map of data-attributes
     */
    public function getDataMap()
    {
        return $this->data;
    }

    /**
     * @return string|null the document title (or NULL, if undefined)
     */
    public function getTitle()
    {
        return $this->getData('title', null);
    }

    /**
     * @return string|null the layout file to use, without file extension (or NULL, if undefined)
     */
    public function getLayout()
    {
        return $this->getData('layout', null);
    }

    /**
     * @return string|null permalink
     */
    public function getPermalink()
    {
        return $this->getData('permalink', null);
    }

    /**
     * @return bool if false, this document should not be published
     */
    public function getPublished()
    {
        return $this->getData('published', true);
    }

    /**
     * @return string[] list of categories that this document belongs to
     */
    public function getCategories()
    {
        if (isset($this->data['categories'])) {
            return is_array($this->data['categories'])
                ? $this->data['categories']
                : array_map('trim', explode(',', $this->data['categories']));
        }

        if (isset($this->data['category'])) {
            return array($this->data['category']);
        }

        return array();
    }

    /**
     * @return string[] list of tags applied to this document
     */
    public function getTags()
    {
        if (isset($this->data['tags'])) {
            return is_array($this->data['tags'])
                ? $this->data['tags']
                : array_map('trim', explode(',', $this->data['tags']));
        }

        return array();
    }
}
