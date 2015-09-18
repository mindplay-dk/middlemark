<?php

use cebe\markdown\GithubMarkdown;
use Ciconia\Ciconia;
use KzykHys\FrontMatter\FrontMatter;
use mindplay\middlemark\CebeMarkdown;
use mindplay\middlemark\CiconiaMarkdown;
use mindplay\middlemark\MarkdownMiddleware;
use mindplay\middlemark\YamlFrontMatter;
use Zend\Diactoros\Request;
use Zend\Diactoros\Response;

require __DIR__ . '/header.php';

test(
    "kzykhys/yaml-front-matter parser integration",
    function () {
        $SAMPLE = file_get_contents(__DIR__ . "/doc/test.md");

        $matter = new YamlFrontMatter(new FrontMatter());

        $doc = $matter->parse($SAMPLE);

        eq(trim($doc->markdown), "# Hello", "can get Markdown content");

        eq($doc->data, array("title" => "Hello World"), "can parse front matter");
    }
);

test(
    "cebe/markdown parser integration",
    function () {
        $SAMPLE = "# Hello";

        $adapter = new CebeMarkdown(new GithubMarkdown());

        eq(trim($adapter->render($SAMPLE)), "<h1>Hello</h1>", "can render Markdown content");
    }
);

test(
    "kzykhys/ciconia parser integration",
    function () {
        $SAMPLE = "# Hello";

        $adapter = new CiconiaMarkdown(new Ciconia());

        eq(trim($adapter->render($SAMPLE)), "<h1>Hello</h1>", "can render Markdown content");
    }
);

test(
    "",
    function () {
        $middleware = new MarkdownMiddleware(__DIR__ . "/doc");

        $request = new Request("/test.md", "GET");

        /** @var Response $response */
        $response = new Response();

        $response = $middleware->__invoke($request, $response, function () {
            throw new RuntimeException("middleware did not return");
        });

        $body = (string) $response->getBody();

        eq(trim($body), "<h1>Hello</h1>");
    }
);

exit(run());
