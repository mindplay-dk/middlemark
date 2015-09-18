<?php

namespace mindplay\middlemark;

use Ciconia\Ciconia;
use Ciconia\Extension\Gfm;

/**
 * A Markdown parser adapter for the `kzykhys/ciconia` package
 *
 * @link https://packagist.org/packages/kzykhys/ciconia
 */
class CiconiaMarkdownEngine implements MarkdownEngineInterface
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
    public function __construct(Ciconia $engine = null)
    {
        $this->engine = $engine ?: $this->createDefaultEngine();
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

    /**
     * @return MarkdownEngineInterface
     */
    protected function createDefaultEngine()
    {
        $engine = new Ciconia();

        $engine->addExtension(new Gfm\FencedCodeBlockExtension());
        $engine->addExtension(new Gfm\TaskListExtension());
        $engine->addExtension(new Gfm\InlineStyleExtension());
        $engine->addExtension(new Gfm\WhiteSpaceExtension());
        $engine->addExtension(new Gfm\TableExtension());
        $engine->addExtension(new Gfm\UrlAutoLinkExtension());

        return $engine;
    }
}
