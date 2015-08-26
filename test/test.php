<?php

use cebe\markdown\GithubMarkdown;
use KzykHys\FrontMatter\FrontMatter;
use mindplay\middlemark\CebeMarkdown;
use mindplay\middlemark\MarkdownMiddleware;
use mindplay\middlemark\YamlFrontMatter;
use Zend\Diactoros\Request;
use Zend\Diactoros\Response;

require __DIR__ . '/header.php';

test(
    "Markdown engine and FrontMatter parser integration",
    function () {
        $SAMPLE = file_get_contents(__DIR__ . "/doc/test.md");

        $matter = new YamlFrontMatter(new FrontMatter());

        $doc = $matter->parse($SAMPLE);

        eq($doc->data, array("title" => "Hello World"), "can parse front matter");

        $markdown = new CebeMarkdown(new GithubMarkdown());

        eq(trim($doc->markdown), "# Hello", "can get Markdown content");

        eq(trim($markdown->render($doc->markdown)), "<h1>Hello</h1>", "can render Markdown content");
    }
);

test(
    "",
    function () {
        $middleware = new MarkdownMiddleware(__DIR__ . "/doc");

        $request = new Request("/test.md", "GET");

        /** @var Response $response */
        $response = new Response();

        $response = $middleware($request, $response, function () {
            throw new RuntimeException("middleware did not return");
        });

        $body = (string) $response->getBody();

        eq(trim($body), "<h1>Hello</h1>");
    }
);

exit(run());
