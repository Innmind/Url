# Url

| `develop` |
|-----------|
| [![codecov](https://codecov.io/gh/Innmind/Url/branch/develop/graph/badge.svg)](https://codecov.io/gh/Innmind/Url) |
| [![Build Status](https://travis-ci.org/Innmind/Url.svg?branch=develop)](https://travis-ci.org/Innmind/Url) |

Url abstraction library

## Installation

```sh
composer require innmind/url
```

## Usage

```php
use Innmind\Url\Url;

$url = Url::of('http://example.com:8080/some/dir/?limit=10');

$url->scheme(); // Scheme('http')
$url->authority()->userInformation()->user(); // User::none()
$url->authority()->userInformation()->password(); // Password::none()
$url->authority()->host(); // Host('example.com')
$url->authority()->port(); // Port(8080)
$url->path(); // Path('/some/dir/')
$url->query(); // Query('limit=10')
$url->fragment() // Fragment::none()
```
