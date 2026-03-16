<?php
declare(strict_types = 1);

namespace Innmind\Url\Tests;

use Innmind\Url\{
    Url,
    Scheme,
    Authority,
    Authority\UserInformation,
    Authority\UserInformation\User,
    Authority\UserInformation\Password,
    Authority\Host,
    Authority\Port,
    Path,
    Query,
    Fragment,
};
use Fixtures\Innmind\Url\Url as Fixture;
use Innmind\BlackBox\{
    PHPUnit\Framework\TestCase,
    PHPUnit\BlackBox,
};
use PHPUnit\Framework\Attributes\DataProvider;

class UrlTest extends TestCase
{
    use BlackBox;

    public function testInterface()
    {
        $u = Url::from(
            Scheme::of('http'),
            Authority::of(
                UserInformation::of(
                    User::of('foo'),
                    Password::of('bar'),
                ),
                Host::of('localhost'),
                Port::of(8080),
            ),
            Path::of('/foo'),
            Query::of('foo=bar'),
            Fragment::of('baz'),
        );

        $this->assertInstanceOf(Url::class, $u);
        $this->assertSame('http://foo:bar@localhost:8080/foo?foo=bar#baz', $u->toString());

        $this->assertSame(
            '/',
            Url::from(
                Scheme::none(),
                Authority::of(
                    UserInformation::of(User::none(), Password::none()),
                    Host::none(),
                    Port::none(),
                ),
                Path::none(),
                Query::none(),
                Fragment::none(),
            )->toString(),
        );
    }

    #[DataProvider('fromString')]
    public function testOf(
        string $url,
        string $scheme,
        string $user,
        string $password,
        string $host,
        string $port,
        string $path,
        string $query,
        string $fragment,
    ) {
        $url = Url::of($url);

        $this->assertInstanceOf(Url::class, $url);
        $this->assertSame($scheme, $url->scheme()->toString());
        $this->assertSame($user, $url->authority()->userInformation()->user()->toString());
        $this->assertSame($password, $url->authority()->userInformation()->password()->toString());
        $this->assertSame($host, $url->authority()->host()->toString());
        $this->assertSame($port, $url->authority()->port()->toString());
        $this->assertSame($path, $url->path()->toString());
        $this->assertSame($query, $url->query()->toString());
        $this->assertSame($fragment, $url->fragment()->toString());
    }

    #[DataProvider('fromString')]
    #[DataProvider('parseable')]
    public function testValidStringsReturnAnUrl($string)
    {
        $this->assertInstanceOf(Url::class, Url::maybe($string)->match(
            static fn($url) => $url,
            static fn() => null,
        ));
    }

    public function testMaybeReturnNothingForUnparseableStrings()
    {
        $this->assertNull(Url::maybe('http://user:password/path')->match(
            static fn($url) => $url,
            static fn() => null,
        ));
    }

    public function testThrowWhenBuildingFromInvalidString()
    {
        $this->expectException(\DomainException::class);

        $_ = Url::of('http://user:password/path');
    }

    #[DataProvider('cases')]
    public function testFormatNotAltered(string $url)
    {
        $this->assertSame(
            $url,
            Url::of($url)->toString(),
        );
    }

    #[DataProvider('parseable')]
    public function testParse(string $url)
    {
        $this->assertInstanceOf(
            Url::class,
            Url::of($url),
        );
    }

    public function testWithScheme()
    {
        $url = Url::of('http://example.com');
        $url2 = $url->withScheme($scheme = Scheme::of('https'));

        $this->assertNotSame($url, $url2);
        $this->assertSame($scheme, $url2->scheme());
        $this->assertSame($url->authority(), $url2->authority());
        $this->assertSame($url->path(), $url2->path());
        $this->assertSame($url->query(), $url2->query());
        $this->assertSame($url->fragment(), $url2->fragment());
    }

    public function testWithoutScheme()
    {
        $url = Url::of('http://example.com');
        $url2 = $url->withoutScheme();

        $this->assertNotSame($url, $url2);
        $this->assertEquals(Scheme::none(), $url2->scheme());
        $this->assertSame($url->authority(), $url2->authority());
        $this->assertSame($url->path(), $url2->path());
        $this->assertSame($url->query(), $url2->query());
        $this->assertSame($url->fragment(), $url2->fragment());
    }

    public function testWithAuthority()
    {
        $url = Url::of('http://example.com');
        $url2 = $url->withAuthority($authority = Authority::none());

        $this->assertNotSame($url, $url2);
        $this->assertSame($url->scheme(), $url2->scheme());
        $this->assertSame($authority, $url2->authority());
        $this->assertSame($url->path(), $url2->path());
        $this->assertSame($url->query(), $url2->query());
        $this->assertSame($url->fragment(), $url2->fragment());
    }

    public function testWithoutAuthority()
    {
        $url = Url::of('http://example.com');
        $url2 = $url->withoutAuthority();

        $this->assertNotSame($url, $url2);
        $this->assertSame($url->scheme(), $url2->scheme());
        $this->assertEquals(Authority::none(), $url2->authority());
        $this->assertSame($url->path(), $url2->path());
        $this->assertSame($url->query(), $url2->query());
        $this->assertSame($url->fragment(), $url2->fragment());
    }

    public function testWithPath()
    {
        $url = Url::of('http://example.com');
        $url2 = $url->withPath($path = Path::none());

        $this->assertNotSame($url, $url2);
        $this->assertSame($url->scheme(), $url2->scheme());
        $this->assertSame($url->authority(), $url2->authority());
        $this->assertSame($path, $url2->path());
        $this->assertSame($url->query(), $url2->query());
        $this->assertSame($url->fragment(), $url2->fragment());
    }

    public function testWithoutPath()
    {
        $url = Url::of('http://example.com');
        $url2 = $url->withoutPath();

        $this->assertNotSame($url, $url2);
        $this->assertSame($url->scheme(), $url2->scheme());
        $this->assertSame($url->authority(), $url2->authority());
        $this->assertEquals(Path::none(), $url2->path());
        $this->assertSame($url->query(), $url2->query());
        $this->assertSame($url->fragment(), $url2->fragment());
    }

    public function testWithQuery()
    {
        $url = Url::of('http://example.com');
        $url2 = $url->withQuery($query = Query::none());

        $this->assertNotSame($url, $url2);
        $this->assertSame($url->scheme(), $url2->scheme());
        $this->assertSame($url->authority(), $url2->authority());
        $this->assertSame($url->path(), $url2->path());
        $this->assertSame($query, $url2->query());
        $this->assertSame($url->fragment(), $url2->fragment());
    }

    public function testWithoutQuery()
    {
        $url = Url::of('http://example.com');
        $url2 = $url->withoutQuery();

        $this->assertNotSame($url, $url2);
        $this->assertSame($url->scheme(), $url2->scheme());
        $this->assertSame($url->authority(), $url2->authority());
        $this->assertSame($url->path(), $url2->path());
        $this->assertEquals(Query::none(), $url2->query());
        $this->assertSame($url->fragment(), $url2->fragment());
    }

    public function testWithFragment()
    {
        $url = Url::of('http://example.com');
        $url2 = $url->withFragment($fragment = Fragment::none());

        $this->assertNotSame($url, $url2);
        $this->assertSame($url->scheme(), $url2->scheme());
        $this->assertSame($url->authority(), $url2->authority());
        $this->assertSame($url->path(), $url2->path());
        $this->assertSame($url->query(), $url2->query());
        $this->assertSame($fragment, $url2->fragment());
    }

    public function testWithoutFragment()
    {
        $url = Url::of('http://example.com');
        $url2 = $url->withoutFragment();

        $this->assertNotSame($url, $url2);
        $this->assertSame($url->scheme(), $url2->scheme());
        $this->assertSame($url->authority(), $url2->authority());
        $this->assertSame($url->path(), $url2->path());
        $this->assertSame($url->query(), $url2->query());
        $this->assertEquals(Fragment::none(), $url2->fragment());
    }

    public function testCastWithNullScheme()
    {
        $url = Url::of('//example.com');

        $this->assertSame('example.com/', $url->toString());
    }

    public function testPathIsAbsolute()
    {
        $this->assertTrue(Url::of('/some/path')->path()->absolute());
        $this->assertFalse(Url::of('some/path')->path()->absolute());
    }

    public function testEquals(): BlackBox\Proof
    {
        return $this
            ->forAll(Fixture::any(), Fixture::any())
            ->filter(static fn($a, $b) => $a->toString() !== $b->toString())
            ->prove(function($a, $b) {
                $this->assertTrue($a->equals($a));
                $this->assertTrue($a->equals(Url::from(
                    $a->scheme(),
                    $a->authority(),
                    $a->path(),
                    $a->query(),
                    $a->fragment(),
                )));
                $this->assertFalse($a->equals($b));
                $this->assertFalse($b->equals($a));
            });
    }

    #[DataProvider('normalized')]
    public function testParsedUrlIsNotAltered($url)
    {
        $this->assertSame(
            $url,
            Url::of($url)->toString(),
        );
    }

    public static function cases(): array
    {
        return [
            ['#foobar'],
            ['?some=query'],
        ];
    }

    public static function parseable(): array
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

    public static function normalized(): array
    {
        return [
            ['/wiki/Category:42'],
            ['/wiki/Category:42?some=query'],
            ['http://a.pl/'],
            ['http://www.google.com/'],
            ['http://www.google.museum/'],
            ['https://google.com/'],
            ['http://www.example.coop/'],
            ['http://www.test-example.com/'],
            ['http://www.symfony.com/'],
            ['http://symfony.fake/blog/'],
            ['http://symfony.com/'],
            ['http://symfony.com/search?type=&q=url+validator'],
            ['http://www.symfony.com/doc/current/book/validation.html#supported-constraints'],
            ['http://very.long.domain.name.com/'],
            ['http://localhost/'],
            ['http://myhost123/'],
            ['http://127.0.0.1/'],
            ['http://127.0.0.1/'],
            ['http://[::1]/'],
            ['http://[::1]/'],
            ['http://[1:2:3:0:4:5:6:7]/'],
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
            ['http://username:password@symfony.com/'],
            ['http://user-name@symfony.com/'],
            ['http://symfony.com/'],
            ['http://symfony.com/?query=1'],
            ['http://symfony.com/#fragment'],
            ['http://www.independent.co.uk/service/privacy-policy-a6184181.html'],
            ['http://del.icio.us/post?url=http://news.bbc.co.uk/2/hi/entertainment/7619828.stm&amp;title=New%20Hitchhiker%27s%20author%20announced'],
            ['example.com'],
            ['http://example.com/'],
            ['http://example.com/bar'],
            ['http://xn--example.com/foo/baz'],
            ['http://xn--example.com/foo/baz/'],
            ['http://xn--example.com/foo/'],
            ['http://xn--example.com:80/foo/'],
            ['https://xn--example.com:443/foo/'],
            ['http://xn-elsewhere.com/'],
            ['/path/to/content'],
            ['/path/to/content?query=foo#fragment'],
            ['/path/to/content/'],
            ['/foo/baz'],
            ['foo'],
            ['./bar'],
            ['bar'],
            ['bar/baz?query=string#fragment'],
            ['?query=string'],
            ['#fragment'],
            ['/absolute'],
            ['../'],
            ['xn--example.com/'],
            ['../bar'],
            ['../bar/foo'],
            ['http://example.com/foo'],
            ['http://xn--example.com/foo/bar'],
            ['http://xn--example.com/foo/bar/baz?query=string#fragment'],
            ['http://xn--example.com/bar/foo'],
            ['http://xn--example.com/foo/baz?query=string'],
            ['http://xn--example.com/foo/baz/?query=string'],
            ['http://xn--example.com/foo/baz/#fragment'],
            ['http://xn--example.com/foo/baz#fragment'],
            ['http://xn--example.com/absolute'],
            ['http://xn--example.com:80/'],
            ['https://xn--example.com:443/'],
            ['http://xn--example.com/'],
            ['/path/bar'],
            ['/path/to/bar'],
            ['/path/to/content/bar'],
            ['/bar/foo'],
        ];
    }

    public static function fromString(): array
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
                'url=http://news.bbc.co.uk/2/hi/entertainment/7619828.stm&amp;title=New%20Hitchhiker%27s%20author%20announced',
                '',
            ],
            [
                'mysql://root:root@127.0.0.1:3306/example',
                'mysql',
                'root',
                'root',
                '127.0.0.1',
                '3306',
                '/example',
                '',
                '',
            ],
            [
                'ssh://user@some-server:2222',
                'ssh',
                'user',
                '',
                'some-server',
                '2222',
                '/',
                '',
                '',
            ],
            [
                'http://user:password@a.org/',
                'http',
                'user',
                'password',
                'a.org',
                '',
                '/',
                '',
                '',
            ],
            [
                '/redirect?to=a.org',
                '',
                '',
                '',
                '',
                '',
                '/redirect',
                'to=a.org',
                '',
            ],
            [
                'file:///redirect?to=a.org',
                'file',
                '',
                '',
                '',
                '',
                '/redirect',
                'to=a.org',
                '',
            ],
            [
                'relative/path?with=query#and-fragment',
                '',
                '',
                '',
                '',
                '',
                'relative/path',
                'with=query',
                'and-fragment',
            ],
        ];
    }
}
