<?php

namespace mindplay\middlemark;

use KzykHys\FrontMatter\FrontMatter;

/**
 * A front-matter parser adapter for the `kzykhys/yaml-front-matter` package.
 *
 * @link https://packagist.org/packages/kzykhys/yaml-front-matter
 */
class YamlFrontMatterParser implements DocumentParserInterface
{
    /**
     * @var FrontMatter
     */
    private $parser;

    /**
     * @param FrontMatter $parser
     */
    public function __construct(FrontMatter $parser = null)
    {
        $this->parser = $parser ?: $this->createDefaultParser();
    }

    /**
     * @inheritdoc
     */
    public function parse($markdown, $filename = null)
    {
        $result = $this->parser->parse($markdown);

        return new Document($filename, $result->getContent(), $result->getConfig());
    }

    /**
     * @return FrontMatter
     */
    protected function createDefaultParser()
    {
        return new FrontMatter();
    }
}
