<?php

namespace mindplay\middlemark;

use KzykHys\FrontMatter\FrontMatter;

/**
 * A front-matter parser adapter for the `kzykhys/yaml-front-matter` package.
 *
 * @link https://packagist.org/packages/kzykhys/yaml-front-matter
 */
class YamlFrontMatter implements FrontMatterInterface
{
    /**
     * @var FrontMatter
     */
    private $parser;

    /**
     * @param FrontMatter $parser
     */
    public function __construct(FrontMatter $parser)
    {
        $this->parser = $parser;
    }

    /**
     * @inheritdoc
     */
    public function parse($markdown)
    {
        $result = $this->parser->parse($markdown);

        return new Document($result->getContent(), $result->getConfig());
    }
}
