<?php

namespace mindplay\middlemark;

use cebe\markdown\GithubMarkdown;
use KzykHys\FrontMatter\FrontMatter;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class MarkdownMiddleware
{
    /**
     * @var DocumentParserInterface
     */
    private $parser;

    /**
     * @var RendererInterface
     */
    private $renderer;

    /**
     * @var string absolute path to local document root
     */
    private $root_path;

    /**
     * @var string URI mask for Markdown files
     */
    public $uri_mask = "*.md";

    /**
     * @param string                       $root_path absolute path to local document root
     * @param DocumentParserInterface|null $parser    Document parser
     * @param RendererInterface|null       $renderer  Document renderer
     */
    public function __construct(
        $root_path,
        DocumentParserInterface $parser = null,
        RendererInterface $renderer = null
    ) {
        $this->root_path = $root_path;
        $this->parser = $parser ?: new YamlFrontMatterParser();
        $this->renderer = $renderer ?: new HtmlRenderer();
    }

    /**
     * @param Request $request
     *
     * @return string|null source file path (or NULL, if not found)
     */
    protected function getPath(Request $request)
    {
        $uri = $request->getUri()->getPath();

        if (! fnmatch($this->uri_mask, $uri)) {
            return null; // URI mask doesn't match
        }

        $pattern = '/^(.*\.)([a-zA-Z]+)$/';

        $path = $this->root_path . preg_replace($pattern, '$1md', $uri);

        if (! file_exists($path)) {
            return null; // file not found
        }

        return $path;
    }

    public function __invoke(Request $request, Response $response, callable $next)
    {
        $path = $this->getPath($request);

        if ($path === null) {
            return $next($request, $response);
        }

        $doc = $this->parser->parse(file_get_contents($path), $path);

        $html = $this->renderer->render($doc);

        $response->getBody()->write($html);

        return $response
            ->withStatus(200)
            ->withHeader("Content-Type", "text/html");
    }
}
