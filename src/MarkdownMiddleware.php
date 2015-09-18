<?php

namespace mindplay\middlemark;


use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class MarkdownMiddleware
{
    /**
     * @var string absolute path to local document root
     */
    private $root_path;

    /**
     * @var DocumentParserInterface
     */
    private $parser;

    /**
     * @var MarkdownEngineInterface
     */
    private $engine;

    /**
     * @var RendererInterface
     */
    private $renderer;

    /**
     * @var string URI mask for Markdown files
     */
    public $uri_mask = "*.md";

    /**
     * @param string                       $root_path absolute path to local document root
     * @param DocumentParserInterface|null $parser    Document parser
     * @param MarkdownEngineInterface|null $engine    Markdown engine
     * @param RendererInterface|null       $renderer  Document renderer
     */
    public function __construct(
        $root_path,
        DocumentParserInterface $parser = null,
        MarkdownEngineInterface $engine = null,
        RendererInterface $renderer = null
    ) {
        $this->root_path = $root_path;
        $this->parser = $parser ?: $this->createDefaultParser();
        $this->engine = $renderer ?: $this->createDefaultEngine();
        $this->renderer = $renderer ?: $this->createDefaultRenderer();
    }

    /**
     * @param Request $request
     *
     * @return string|null source file path (or NULL, if not found)
     */
    protected function getPath(Request $request)
    {
        $uri = $request->getUri()->getPath();

        if (!fnmatch($this->uri_mask, $uri)) {
            return null; // URI mask doesn't match
        }

        $pattern = '/^(.*\.)([a-zA-Z]+)$/';

        $path = $this->root_path . preg_replace($pattern, '$1md', $uri);

        if (!file_exists($path)) {
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

        $body = $this->engine->render($doc->getContent());

        $view = $this->createView($doc, $body);

        $html = $this->renderer->render($view);

        $response->getBody()->write($html);

        return $response
            ->withStatus(200)
            ->withHeader("Content-Type", "text/html");
    }

    /**
     * @param Document $doc
     * @param string   $body HTML body content
     *
     * @return View
     */
    protected function createView(Document $doc, $body)
    {
        $view = new View();

        $view->doc = $doc;
        $view->body = $body;
        $view->title = $doc->getTitle() ?: "No Title";

        return $view;
    }

    /**
     * @return MarkdownEngineInterface
     */
    protected function createDefaultEngine()
    {
        return new CebeMarkdownEngine();
    }

    /**
     * @return YamlFrontMatterParser
     */
    protected function createDefaultParser()
    {
        return new YamlFrontMatterParser();
    }

    /**
     * @return RendererInterface
     */
    protected function createDefaultRenderer()
    {
        return new HtmlRenderer();
    }
}
