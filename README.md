# mindplay/middlemark

[PSR-7](http://www.php-fig.org/psr/psr-7/) Markdown rendering middleware, with support for
[front matter](http://jekyllrb.com/docs/frontmatter/), for use with e.g.
[relay/relay](https://github.com/relayphp/Relay.Relay) or
[zend-stratigility](https://github.com/zendframework/zend-stratigility) middleware dispatcher.

## How it works

The idea is, you point this middleware to a root-folder containing `*.md` files, and when this
middleware gets a request for e.g. `foo/bar.html`, it searches for `foo/bar.md`, and renders it.

It does this by assembling a simple [Document](src/Document.php) and [View](src/View.php) model,
which can then be rendered by a renderer implementing a simple interface - the included renderer
integrates [mindplay/kisstpl](https://github.com/mindplay-dk/kisstpl), and integrating any other
renderer or template engine is trivial.

## Markdown and Front Matter Adapters

The available/default engine adapters are [cebe/markdown](https://packagist.org/packages/cebe/markdown) and
[kzykhys/ciconia](https://github.com/kzykhys/Ciconia/), both of which default to GitHub-flavored Markdown,
though you are free to replace/reconfigure these as needed. Engine adapters are a simple interface, and you
can easily integrate any Markdown engine you wish to.

The default engine for Jekyll-style [front matter](https://jekyllrb.com/docs/frontmatter/) is
[kzykhys/yaml-front-matter](https://packagist.org/packages/kzykhys/yaml-front-matter). Only one (YAML)
front matter engine is currently available, but this is behind an adapter interface as well, and is
easy to replace.
