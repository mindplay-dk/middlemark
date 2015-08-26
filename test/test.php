<?php

use cebe\markdown\GithubMarkdown;
use KzykHys\FrontMatter\FrontMatter;
use mindplay\middlemark\CebeMarkdown;
use mindplay\middlemark\YamlFrontMatter;

require __DIR__ . '/header.php';

$SAMPLE = <<<MARKDOWN
---
title: Hello World
---
# Hello
MARKDOWN;

test(
    "default Markdown and FrontMatter adapters",
    function () use ($SAMPLE) {
        $matter = new YamlFrontMatter(new FrontMatter());

        $doc = $matter->parse($SAMPLE);

        eq($doc->data, ["title" => "Hello World"], "can parse front matter");

        $markdown = new CebeMarkdown(new GithubMarkdown());

        eq($doc->markdown, "# Hello", "can get Markdown content");

        eq(trim($markdown->render($doc->markdown)), "<h1>Hello</h1>", "can render Markdown content");
    }
);

exit(run());
