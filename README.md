# Url

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
