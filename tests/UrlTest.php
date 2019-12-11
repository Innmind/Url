<?php
declare(strict_types = 1);

namespace Innmind\Url\Tests;

use Innmind\Url\{
    Url,
    UrlInterface,
    Scheme,
    NullScheme,
    Authority,
    Authority\UserInformation,
    Authority\UserInformation\User,
    Authority\UserInformation\NullUser,
    Authority\UserInformation\Password,
    Authority\UserInformation\NullPassword,
    Authority\Host,
    Authority\NullHost,
    Authority\Port,
    Authority\NullPort,
    Path,
    NullPath,
    Query,
    NullQuery,
    Fragment,
    NullFragment,
    NullAuthority
};
use PHPUnit\Framework\TestCase;

class UrlTest extends TestCase
{
    public function testInterface()
    {
        $u = new Url(
            new Scheme('http'),
            new Authority(
                new UserInformation(
                    new User('foo'),
                    new Password('bar')
                ),
                new Host('localhost'),
                new Port(8080)
            ),
            new Path('/foo'),
            new Query('foo=bar'),
            new Fragment('baz')
        );

        $this->assertInstanceOf(UrlInterface::class, $u);
        $this->assertSame('http://foo:bar@localhost:8080/foo?foo=bar#baz', (string) $u);

        $this->assertSame(
            '/',
            (string) new Url(
                new NullScheme,
                new Authority(
                    new UserInformation(new NullUser, new NullPassword),
                    new NullHost,
                    new NullPort
                ),
                new NullPath,
                new NullQuery,
                new NullFragment
            )
        );
    }

    /**
     * @dataProvider fromString
     */
    public function testOf(
        string $url,
        string $scheme,
        string $user,
        string $password,
        string $host,
        string $port,
        string $path,
        string $query,
        string $fragment
    ) {
        $url = Url::of($url);

        $this->assertInstanceOf(Url::class, $url);
        $this->assertSame($scheme, (string) $url->scheme());
        $this->assertSame($user, (string) $url->authority()->userInformation()->user());
        $this->assertSame($password, (string) $url->authority()->userInformation()->password());
        $this->assertSame($host, (string) $url->authority()->host());
        $this->assertSame($port, (string) $url->authority()->port());
        $this->assertSame($path, (string) $url->path());
        $this->assertSame($query, (string) $url->query());
        $this->assertSame($fragment, (string) $url->fragment());
    }

    /**
     * @expectedException Innmind\Url\Exception\InvalidArgumentException
     */
    public function testThrowWhenBuildingFromInvalidString()
    {
        Url::of('http://user:password/path');
    }

    /**
     * @dataProvider cases
     */
    public function testFormatNotAltered(string $url)
    {
        $this->assertSame(
            $url,
            (string) Url::of($url)
        );
    }

    /**
     * @dataProvider parseable
     */
    public function testParse(string $url)
    {
        $this->assertInstanceOf(
            UrlInterface::class,
            Url::of($url)
        );
    }

    public function testWithScheme()
    {
        $url = Url::of('http://example.com');
        $url2 = $url->withScheme($scheme = new Scheme('https'));

        $this->assertNotSame($url, $url2);
        $this->assertSame($scheme, $url2->scheme());
        $this->assertSame($url->authority(), $url2->authority());
        $this->assertSame($url->path(), $url2->path());
        $this->assertSame($url->query(), $url2->query());
        $this->assertSame($url->fragment(), $url2->fragment());
    }

    public function testWithAuthority()
    {
        $url = Url::of('http://example.com');
        $url2 = $url->withAuthority($authority = new NullAuthority);

        $this->assertNotSame($url, $url2);
        $this->assertSame($url->scheme(), $url2->scheme());
        $this->assertSame($authority, $url2->authority());
        $this->assertSame($url->path(), $url2->path());
        $this->assertSame($url->query(), $url2->query());
        $this->assertSame($url->fragment(), $url2->fragment());
    }

    public function testWithPath()
    {
        $url = Url::of('http://example.com');
        $url2 = $url->withPath($path = new NullPath);

        $this->assertNotSame($url, $url2);
        $this->assertSame($url->scheme(), $url2->scheme());
        $this->assertSame($url->authority(), $url2->authority());
        $this->assertSame($path, $url2->path());
        $this->assertSame($url->query(), $url2->query());
        $this->assertSame($url->fragment(), $url2->fragment());
    }

    public function testWithQuery()
    {
        $url = Url::of('http://example.com');
        $url2 = $url->withQuery($query = new NullQuery);

        $this->assertNotSame($url, $url2);
        $this->assertSame($url->scheme(), $url2->scheme());
        $this->assertSame($url->authority(), $url2->authority());
        $this->assertSame($url->path(), $url2->path());
        $this->assertSame($query, $url2->query());
        $this->assertSame($url->fragment(), $url2->fragment());
    }

    public function testWithFragment()
    {
        $url = Url::of('http://example.com');
        $url2 = $url->withFragment($fragment = new NullFragment);

        $this->assertNotSame($url, $url2);
        $this->assertSame($url->scheme(), $url2->scheme());
        $this->assertSame($url->authority(), $url2->authority());
        $this->assertSame($url->path(), $url2->path());
        $this->assertSame($url->query(), $url2->query());
        $this->assertSame($fragment, $url2->fragment());
    }

    public function testCastWithNullScheme()
    {
        $url = Url::of('//example.com');

        $this->assertSame('example.com/', (string) $url);
    }

    public function cases(): array
    {
        return [
            ['#foobar'],
            ['?some=query'],
        ];
    }

    public function parseable(): array
    {
        return [
            ['/wiki/Category:42'],
            ['/wiki/Category:42?some=query'],
            ['http://a.pl'],
            ['http://www.google.com'],
            ['http://www.google.com.'],
            ['http://www.google.museum'],
            ['https://google.com/'],
            ['https://google.com:80/'],
            ['http://www.example.coop/'],
            ['http://www.test-example.com/'],
            ['http://www.symfony.com/'],
            ['http://symfony.fake/blog/'],
            ['http://symfony.com/?'],
            ['http://symfony.com/search?type=&q=url+validator'],
            ['http://symfony.com/#'],
            ['http://symfony.com/#?'],
            ['http://www.symfony.com/doc/current/book/validation.html#supported-constraints'],
            ['http://very.long.domain.name.com/'],
            ['http://localhost/'],
            ['http://myhost123/'],
            ['http://127.0.0.1/'],
            ['http://127.0.0.1:80/'],
            ['http://[::1]/'],
            ['http://[::1]:80/'],
            ['http://[1:2:3::4:5:6:7]/'],
            ['http://sãopaulo.com/'],
            ['http://xn--sopaulo-xwa.com/'],
            ['http://sãopaulo.com.br/'],
            ['http://xn--sopaulo-xwa.com.br/'],
            ['http://пример.испытание/'],
            ['http://xn--e1afmkfd.xn--80akhbyknj4f/'],
            ['http://مثال.إختبار/'],
            ['http://xn--mgbh0fb.xn--kgbechtv/'],
            ['http://例子.测试/'],
            ['http://xn--fsqu00a.xn--0zwm56d/'],
            ['http://例子.測試/'],
            ['http://xn--fsqu00a.xn--g6w251d/'],
            ['http://例え.テスト/'],
            ['http://xn--r8jz45g.xn--zckzah/'],
            ['http://مثال.آزمایشی/'],
            ['http://xn--mgbh0fb.xn--hgbk6aj7f53bba/'],
            ['http://실례.테스트/'],
            ['http://xn--9n2bp8q.xn--9t4b11yi5a/'],
            ['http://العربية.idn.icann.org/'],
            ['http://xn--ogb.idn.icann.org/'],
            ['http://xn--e1afmkfd.xn--80akhbyknj4f.xn--e1afmkfd/'],
            ['http://xn--espaa-rta.xn--ca-ol-fsay5a/'],
            ['http://xn--d1abbgf6aiiy.xn--p1ai/'],
            ['http://☎.com/'],
            ['http://username:password@symfony.com'],
            ['http://user-name@symfony.com'],
            ['http://symfony.com?'],
            ['http://symfony.com?query=1'],
            ['http://symfony.com/?query=1'],
            ['http://symfony.com#'],
            ['http://symfony.com#fragment'],
            ['http://symfony.com/#fragment'],
            [' http://www.independent.co.uk/service/privacy-policy-a6184181.html'],
            ['http://www.independent.co.uk/service/privacy-policy-a6184181.html '],
            ["http://del.icio.us/post?url=http://news.bbc.co.uk/2/hi/entertainment/7619828.stm&amp;title=New Hitchhiker's author announced"],
        ];
    }

    public function fromString(): array
    {
        return [
            ['http://foo:bar@localhost:8080/foo?bar=baz#whatever', 'http', 'foo', 'bar', 'localhost', '8080', '/foo', 'bar=baz', 'whatever'],
            ['//foo:bar@localhost:8080/foo?bar=baz#whatever', '', 'foo', 'bar', 'localhost', '8080', '/foo', 'bar=baz', 'whatever'],
            ['//localhost:8080/foo?bar=baz#whatever', '', '', '', 'localhost', '8080', '/foo', 'bar=baz', 'whatever'],
            ['ftp://localhost:8080/foo?bar=baz#whatever', 'ftp', '', '', 'localhost', '8080', '/foo', 'bar=baz', 'whatever'],
            ['/foo', '', '', '', '', '', '/foo', '', ''],
            ['/wiki/Category:42', '', '', '', '', '', '/wiki/Category:42', '', ''],
            ['/wiki/Category:42?some=query', '', '', '', '', '', '/wiki/Category:42', 'some=query', ''],
            ['http://a.pl', 'http', '', '', 'a.pl', '', '/', '', ''],
            ['http://www.google.com', 'http', '', '', 'www.google.com', '', '/', '', ''],
            ['http://www.google.com.', 'http', '', '', 'www.google.com.', '', '/', '', ''],
            ['http://www.google.museum', 'http', '', '', 'www.google.museum', '', '/', '', ''],
            ['https://google.com:80/', 'https', '', '', 'google.com', '80', '/', '', ''],
            ['http://symfony.com/?', 'http', '', '', 'symfony.com', '', '/', '', ''],
            ['http://symfony.com/search?type=&q=url+validator', 'http', '', '', 'symfony.com', '', '/search', 'type=&q=url+validator', ''],
            ['http://symfony.com/#', 'http', '', '', 'symfony.com', '', '/', '', ''],
            ['http://symfony.com/#?', 'http', '', '', 'symfony.com', '', '/', '', '?'],
            ['http://127.0.0.1:80/', 'http', '', '', '127.0.0.1', '80', '/', '', ''],
            ['http://[::1]:80/', 'http', '', '', '[::1]', '80', '/', '', ''],
            ['http://[1:2:3::4:5:6:7]/', 'http', '', '', '[1:2:3::4:5:6:7]', '', '/', '', ''],
            ['http://sãopaulo.com/', 'http', '', '', 'sãopaulo.com', '', '/', '', ''],
            ['http://xn--sopaulo-xwa.com/', 'http', '', '', 'xn--sopaulo-xwa.com', '', '/', '', ''],
            ['http://пример.испытание/', 'http', '', '', 'пример.испытание', '', '/', '', ''],
            ['http://xn--e1afmkfd.xn--80akhbyknj4f/', 'http', '', '', 'xn--e1afmkfd.xn--80akhbyknj4f', '', '/', '', ''],
            ['http://مثال.إختبار/', 'http', '', '', 'مثال.إختبار', '', '/', '', ''],
            ['http://xn--mgbh0fb.xn--kgbechtv/', 'http', '', '', 'xn--mgbh0fb.xn--kgbechtv', '', '/', '', ''],
            ['http://例子.测试/', 'http', '', '', '例子.测试', '', '/', '', ''],
            ['http://xn--fsqu00a.xn--0zwm56d/', 'http', '', '', 'xn--fsqu00a.xn--0zwm56d', '', '/', '', ''],
            ['http://例子.測試/', 'http', '', '', '例子.測試', '', '/', '', ''],
            ['http://xn--fsqu00a.xn--g6w251d/', 'http', '', '', 'xn--fsqu00a.xn--g6w251d', '', '/', '', ''],
            ['http://例え.テスト/', 'http', '', '', '例え.テスト', '', '/', '', ''],
            ['http://xn--r8jz45g.xn--zckzah/', 'http', '', '', 'xn--r8jz45g.xn--zckzah', '', '/', '', ''],
            ['http://مثال.آزمایشی/', 'http', '', '', 'مثال.آزمایشی', '', '/', '', ''],
            ['http://xn--mgbh0fb.xn--hgbk6aj7f53bba/', 'http', '', '', 'xn--mgbh0fb.xn--hgbk6aj7f53bba', '', '/', '', ''],
            ['http://실례.테스트/', 'http', '', '', '실례.테스트', '', '/', '', ''],
            ['http://xn--9n2bp8q.xn--9t4b11yi5a/', 'http', '', '', 'xn--9n2bp8q.xn--9t4b11yi5a', '', '/', '', ''],
            ['http://العربية.idn.icann.org/', 'http', '', '', 'العربية.idn.icann.org', '', '/', '', ''],
            ['http://xn--ogb.idn.icann.org/', 'http', '', '', 'xn--ogb.idn.icann.org', '', '/', '', ''],
            ['http://xn--e1afmkfd.xn--80akhbyknj4f.xn--e1afmkfd/', 'http', '', '', 'xn--e1afmkfd.xn--80akhbyknj4f.xn--e1afmkfd', '', '/', '', ''],
            ['http://xn--espaa-rta.xn--ca-ol-fsay5a/', 'http', '', '', 'xn--espaa-rta.xn--ca-ol-fsay5a', '', '/', '', ''],
            ['http://xn--d1abbgf6aiiy.xn--p1ai/', 'http', '', '', 'xn--d1abbgf6aiiy.xn--p1ai', '', '/', '', ''],
            ['http://☎.com/', 'http', '', '', '☎.com', '', '/', '', ''],
            ['http://username:password@symfony.com', 'http', 'username', 'password', 'symfony.com', '', '/', '', ''],
            ['http://user-name@symfony.com', 'http', 'user-name', '', 'symfony.com', '', '/', '', ''],
            [
                "http://del.icio.us/post?url=http://news.bbc.co.uk/2/hi/entertainment/7619828.stm&amp;title=New Hitchhiker's author announced",
                'http',
                '',
                '',
                'del.icio.us',
                '',
                '/post',
                'url=http://news.bbc.co.uk/2/hi/entertainment/7619828.stm&amp;title=New%20Hitchhiker\'s%20author%20announced',
                '',
            ],
        ];
    }
}
