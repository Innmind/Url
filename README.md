# Url

| `master` | `develop` |
|----------|-----------|
| [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Innmind/Url/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Innmind/Url/?branch=master) | [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Innmind/Url/badges/quality-score.png?b=develop)](https://scrutinizer-ci.com/g/Innmind/Url/?branch=develop) |
| [![Code Coverage](https://scrutinizer-ci.com/g/Innmind/Url/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Innmind/Url/?branch=master) | [![Code Coverage](https://scrutinizer-ci.com/g/Innmind/Url/badges/coverage.png?b=develop)](https://scrutinizer-ci.com/g/Innmind/Url/?branch=develop) |
| [![Build Status](https://scrutinizer-ci.com/g/Innmind/Url/badges/build.png?b=master)](https://scrutinizer-ci.com/g/Innmind/Url/build-status/master) | [![Build Status](https://scrutinizer-ci.com/g/Innmind/Url/badges/build.png?b=develop)](https://scrutinizer-ci.com/g/Innmind/Url/build-status/develop) |

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/5e787d7a-d408-4f3c-b92b-5f1eb1bb29ca/big.png)](https://insight.sensiolabs.com/projects/5e787d7a-d408-4f3c-b92b-5f1eb1bb29ca)

Url abstraction library

## Installation

```sh
composer require innmind/url
```

## Usage

```php
use Innmind\Url\Url;

$url = Url::fromString('http://example.com:8080/some/dir/?limit=10');

$url->scheme(); // Scheme('http')
$url->authority()->userInformation()->user(); // NullUser
$url->authority()->userInformation()->password(); // NullPassword
$url->authority()->host(); // Host('example.com')
$url->authority()->port(); // Port(8080)
$url->path(); // Path('/some/dir/')
$url->query(); // Query('limit=10')
$url->fragment() // NullFragment
```
