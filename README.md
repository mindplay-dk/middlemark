# mindplay/middlemark

[PSR-7](http://www.php-fig.org/psr/psr-7/) Markdown rendering middleware, with support for
[front matter](http://jekyllrb.com/docs/frontmatter/), for use with e.g.
[mindplay/middleman](https://github.com/mindplay-dk/middleman),
[relay/relay](https://github.com/relayphp/Relay.Relay) or
[zend-stratigility](https://github.com/zendframework/zend-stratigility) middleware dispatcher.

## How it works

The idea is, you point this middleware to a root-folder containing `*.md` files, and when this
middleware gets a request for e.g. `foo/bar.html`, it searches for `foo/bar.md`, and renders it.

It does this by assembling a simple [Document](src/Document.php) and [View](src/View.php) model,
which can then be rendered by a renderer implementing a simple interface - the included renderer
integrates [mindplay/kisstpl](https://github.com/mindplay-dk/kisstpl), and integrating any other
renderer or template engine is trivial.

## Adapters

Every third-party component is integrated via an adapter interface - to get a working middleware
component, you need to select a Markdown engine, front-matter adapter, and renderer.

### Markdown Adapters

The available/default engine adapters are [cebe/markdown](https://packagist.org/packages/cebe/markdown) and
[kzykhys/ciconia](https://github.com/kzykhys/Ciconia/), both of which default to GitHub-flavored Markdown,
though you are free to replace/reconfigure these as needed. Engine adapters are a simple interface, and you
can easily integrate any Markdown engine you wish to.

### Front Matter Adapter

The default engine for Jekyll-style [front matter](https://jekyllrb.com/docs/frontmatter/) is
[kzykhys/yaml-front-matter](https://packagist.org/packages/kzykhys/yaml-front-matter). Only one (YAML)
front matter engine is currently available, but this is behind an adapter interface as well, and is
easy to replace.

### Renderer Adapter

The default engine for rendering the view-model is [mindplay/kisstpl](https://github.com/mindplay-dk/kisstpl),
and this is currently the only renderer supported - to use any different view engine, implement the
[Renderer](src/RendererInterface).
