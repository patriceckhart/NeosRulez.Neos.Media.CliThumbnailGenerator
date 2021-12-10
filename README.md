# Command Line Interface based thumbnail generators for Neos Media

A Neos package that provides thumbnail generation via the command line interface (cli).

## Installation

The NeosRulez.Neos.Media.CliThumbnailGenerator package is listed on packagist (https://packagist.org/packages/neosrulez/neos-media-clithumbnailgenerator) - therefore you don't have to include the package in your "repositories" entry any more.

Just run:

```
composer require neosrulez/neos-media-clithumbnailgenerator
```

## Configuration

```yaml
Neos:
  Media:
    thumbnailGenerators:
      Neos\Media\Domain\Model\ThumbnailGenerator\DocumentThumbnailGenerator:
        disable: true # Disable the Default Document Thumbnail Generator
      NeosRulez\Neos\Media\CliThumbnailGenerator\Domain\Model\ThumbnailGenerator\DocumentThumbnailGenerator:
        resolution: 120
        supportedExtensions:
          - pdf
          - eps
          - ai
        paginableDocuments:
          - pdf

```

## Author

* E-Mail: mail@patriceckhart.com
* URL: http://www.patriceckhart.com
