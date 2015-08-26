# mindplay/middlemark

[PSR-7](http://www.php-fig.org/psr/psr-7/) Markdown and [Front Matter](http://jekyllrb.com/docs/frontmatter/)
rendering middleware for use with e.g. [relay/relay](https://github.com/relayphp/Relay.Relay) or
[zend-stratigility](https://github.com/zendframework/zend-stratigility) middleware dispatcher.

The default engines are [cebe/markdown](https://packagist.org/packages/cebe/markdown) for GitHub-flavored
Markdown, and [kzykhys/yaml-front-matter](https://packagist.org/packages/kzykhys/yaml-front-matter) for
Front Matter - these are behind adapter interfaces, are optional, and can easily be replaced.
