<?php

use cebe\markdown\GithubMarkdown;
use Ciconia\Ciconia;
use KzykHys\FrontMatter\FrontMatter;
use mindplay\middlemark\CebeMarkdownEngine;
use mindplay\middlemark\CiconiaMarkdownEngine;
use mindplay\middlemark\Document;
use mindplay\middlemark\MarkdownMiddleware;
use mindplay\middlemark\YamlFrontMatterParser;
use Zend\Diactoros\Request;
use Zend\Diactoros\Response;

require __DIR__ . '/header.php';

test(
    "kzykhys/yaml-front-matter parser integration",
    function () {
        $SAMPLE = file_get_contents(__DIR__ . "/doc/test.md");

        $matter = new YamlFrontMatterParser(new FrontMatter());

        $doc = $matter->parse($SAMPLE);

        eq(trim($doc->getContent()), "# Hello", "can get Markdown content");

        eq($doc->getDataMap(), array("title" => "Hello World"), "can parse front matter");
    }
);

test(
    "cebe/markdown parser integration",
    function () {
        $SAMPLE = "# Hello";

        $adapter = new CebeMarkdownEngine();

        eq(trim($adapter->render($SAMPLE)), "<h1>Hello</h1>", "can render Markdown content");
    }
);

test(
    "kzykhys/ciconia parser integration",
    function () {
        $SAMPLE = "# Hello";

        $adapter = new CiconiaMarkdownEngine();

        eq(trim($adapter->render($SAMPLE)), "<h1>Hello</h1>", "can render Markdown content");
    }
);

test(
    "Can get Document meta-data",
    function () {
        $parser = new YamlFrontMatterParser(new FrontMatter());

        $default = $parser->parse("---\nboo: bar\n---\n");

        eq($default->getTitle(), null, "can get empty title");
        eq($default->getLayout(), null, "can get empty layout");
        eq($default->getPermalink(), null, "can get empty permalink");
        eq($default->getPublished(), true, "published is true by default");
        eq($default->getCategories(), array(), "returns empty list of categories by default");
        eq($default->getTags(), array(), "returns empty list of tags by default");

        eq($parser->parse("---\ntitle: bar\n---\n")->getTitle(), 'bar', "can get title");
        eq($parser->parse("---\nlayout: foo\n---\n")->getLayout(), 'foo', "can get layout");
        eq($parser->parse("---\npermalink: foo\n---\n")->getPermalink(), 'foo', "can get permalink");
        eq($parser->parse("---\npublished: true\n---\n")->getPublished(), true, "can get published (true)");
        eq($parser->parse("---\npublished: false\n---\n")->getPublished(), false, "can get published (false)");

        eq($parser->parse("---\ncategory: foo\n---\n")->getCategories(), array('foo'), "can get single category");
        eq($parser->parse("---\ncategories: foo, bar\n---\n")->getCategories(), array('foo','bar'), "can get comma-separated categories");
        eq($parser->parse("---\ncategories:\n- foo\n- bar\n---\n")->getCategories(), array('foo','bar'), "can get list of categories");

        eq($parser->parse("---\ntags: foo\n---\n")->getTags(), array('foo'), "can get single tag");
        eq($parser->parse("---\ntags: foo, bar\n---\n")->getTags(), array('foo','bar'), "can get comma-separated tags");
        eq($parser->parse("---\ntags:\n- foo\n- bar\n---\n")->getTags(), array('foo','bar'), "can get list of tags");
    }
);

test(
    "middleware can render documents",
    function () {
        $middleware = new MarkdownMiddleware(__DIR__ . "/doc");

        $request = new Request("/test.md", "GET");

        /** @var Response $response */
        $response = new Response();

        $response = $middleware->__invoke($request, $response, function () {
            throw new RuntimeException("middleware did not return");
        });

        $body = (string) $response->getBody();

        ok(strpos($body, "<h1>Hello</h1>") !== false, "contains rendered content");
    }
);

exit(run());
