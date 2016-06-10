<?php

namespace mindplay\middlemark;


use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ResponseInterface as Response;

class MarkdownMiddleware
{
    /**
     * @var string absolute path to local document root
     */
    private $root_path;

    /**
     * @var string absolute root URI for which this middleware responds
     */
    private $base_uri;

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
     * @var string URI file extension for generated HTML documents
     */
    public $html_ext = "html";

    /**
     * @var string file extension for Markdown documents
     */
    public $md_ext = "md";

    /**
     * @param string                       $root_path absolute path to local document root
     * @param string                       $base_uri  base URI for which this middleware responds
     * @param DocumentParserInterface|null $parser    Document parser
     * @param MarkdownEngineInterface|null $engine    Markdown engine
     * @param RendererInterface|null       $renderer  Document renderer
     */
    public function __construct(
        $root_path,
        $base_uri = '/',
        DocumentParserInterface $parser = null,
        MarkdownEngineInterface $engine = null,
        RendererInterface $renderer = null
    ) {
        $this->root_path = $root_path;
        $this->base_uri = rtrim($base_uri, '/') . '/';
        $this->parser = $parser ?: $this->createDefaultParser();
        $this->engine = $engine ?: $this->createDefaultEngine();
        $this->renderer = $renderer ?: $this->createDefaultRenderer();
    }

    /**
     * Attempt to resolve a request for a HTML document as a path to a Markdown document.
     *
     * @param Request $request
     *
     * @return string|null source file path (or NULL, if not found)
     */
    protected function getPath(Request $request)
    {
        $url = $request->getUri()->getPath();

        if (!fnmatch("*.{$this->html_ext}", $url)) {
            return null; // URL mask doesn't match
        }

        if (strncmp($url, $this->base_uri, strlen($this->base_uri)) !== 0) {
            return null; // URL base path doesn't match
        }

        $path = $this->root_path . "/" . $this->replaceExtension(substr($url, strlen($this->base_uri)), $this->html_ext, $this->md_ext);

        if (!file_exists($path)) {
            return null; // file not found
        }

        return $path;
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @param callable $next
     *
     * @return ResponseInterface
     */
    public function __invoke(Request $request, Response $response, callable $next)
    {
        $path = $this->getPath($request);

        if ($path === null) {
            return $next($request, $response);
        }

        $doc = $this->parser->parse(file_get_contents($path), $path);

        $body = $this->engine->render($doc->getContent());

        $body = $this->rewriteURLs($body);

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

    /**
     * Rewrite all <a> tags in the given HTML body content
     *
     * @param string $body HTML body content
     *
     * @return string
     */
    private function rewriteURLs($body)
    {
        $rewriter = new Rewriter();

        return $rewriter->process(
            $body,
            array($this, "rewriteURL")
        );
    }

    /**
     * Rewrite a single URL (from e.g. "*.md" to "*.html")
     *
     * @param string $url
     *
     * @return string
     */
    public function rewriteURL($url)
    {
        if (!fnmatch("*.{$this->md_ext}", $url)) {
            return $url; // URI mask doesn't match
        }

        $path = $this->replaceExtension($url, $this->md_ext, $this->html_ext);

        return substr($path, 1) === "/"
            ? $this->base_uri . $path // append base URI to absolute path
            : $path; // preserve relative path
    }

    /**
     * Replace the file extension in a given path.
     *
     * @param string $path path
     * @param string $from_ext
     * @param string $to_ext
     *
     * @return string path with extension replaced
     */
    protected function replaceExtension($path, $from_ext, $to_ext)
    {
        return preg_replace("/^(.*\\.)({$from_ext})$/", '$1' . $to_ext, $path);
    }
}
