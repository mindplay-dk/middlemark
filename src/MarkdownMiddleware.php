<?php

namespace mindplay\middlemark;

use cebe\markdown\GithubMarkdown;
use KzykHys\FrontMatter\FrontMatter;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Zend\Diactoros\Stream;

class MarkdownMiddleware
{
    /**
     * @var MarkdownInterface
     */
    private $markdown;
    /**
     * @var FrontMatterInterface
     */
    private $frontmatter;

    /**
     * @var string absolute path to local document root
     */
    private $root_path;

    /**
     * @var string URI mask for Markdown files
     */
    public $uri_mask = "*.md";

    /**
     * @param string                    $root_path   absolute path to local document root
     * @param MarkdownInterface|null    $markdown    Markdown rendering engine
     * @param FrontMatterInterface|null $frontmatter FrontMatter parser
     */
    public function __construct(
        $root_path,
        MarkdownInterface $markdown = null,
        FrontMatterInterface $frontmatter = null
    ) {
        $this->markdown = $markdown ?: new CebeMarkdown(new GithubMarkdown());
        $this->frontmatter = $frontmatter ?: new YamlFrontMatter(new FrontMatter());
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

        $pattern = '/^(.*)\.(\w+)$/';

        $path = $this->root_path . DIRECTORY_SEPARATOR . preg_replace($pattern, "md", $uri);

        if (!file_exists($path)) {
            return null; // file not found
        }

        return null;
    }

    public function __invoke(Request $request, Response $response, callable $next)
    {
        $path = $this->getPath($request);

        if ($path) {
            return $next($request, $response);
        }

        $response->getBody()->write($this->markdown->render(file_get_contents($path)));

        return $response
            ->withStatus(200)
            ->withHeader("Content-Type", "text/html");
    }
}
