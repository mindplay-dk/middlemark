<?php

namespace mindplay\middlemark;

use Ciconia\Ciconia;

/**
 * A Markdown parser adapter for the `kzykhys/ciconia` package
 *
 * @link https://packagist.org/packages/kzykhys/ciconia
 */
class CiconiaMarkdown implements MarkdownInterface
{
    /**
     * @var Ciconia
     */
    private $engine;

    /**
     * @var int Number of spaces
     */
    public $tabWidth = 4;

    /**
     * @var int Max depth of nested HTML tags
     */
    public $nestedTagLevel = 3;

    /**
     * @var bool Throws exception if markdown contains syntax error
     */
    public $strict = false;

    /**
     * @param Ciconia $engine
     */
    public function __construct(Ciconia $engine)
    {
        $this->engine = $engine;
    }

    /**
     * @param string $markdown
     *
     * @return string rendered HTML
     */
    public function render($markdown)
    {
        $options = array(
            'tabWidth'       => $this->tabWidth,
            'nestedTagLevel' => 3,
            'strict'         => $this->strict,
        );

        return $this->engine->render($markdown, $options);
    }
}
