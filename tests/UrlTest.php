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
    NullFragment
};

class UrlTest extends \PHPUnit_Framework_TestCase
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

    public function testFromString()
    {
        $u = Url::fromString('http://foo:bar@localhost:8080/foo?bar=baz#whatever');

        $this->assertInstanceOf(Url::class, $u);
        $this->assertSame('http', (string) $u->scheme());
        $this->assertSame('foo', (string) $u->authority()->userInformation()->user());
        $this->assertSame('bar', (string) $u->authority()->userInformation()->password());
        $this->assertSame('localhost', (string) $u->authority()->host());
        $this->assertSame('8080', (string) $u->authority()->port());
        $this->assertSame('/foo', (string) $u->path());
        $this->assertSame('bar=baz', (string) $u->query());
        $this->assertSame('whatever', (string) $u->fragment());

        $u = Url::fromString('/foo');

        $this->assertInstanceOf(Url::class, $u);
        $this->assertSame('', (string) $u->scheme());
        $this->assertSame('', (string) $u->authority()->userInformation()->user());
        $this->assertSame('', (string) $u->authority()->userInformation()->password());
        $this->assertSame('', (string) $u->authority()->host());
        $this->assertSame('', (string) $u->authority()->port());
        $this->assertSame('/foo', (string) $u->path());
        $this->assertSame('', (string) $u->query());
        $this->assertSame('', (string) $u->fragment());

        $this->assertSame(
            'foo',
            (string) Url::fromString('foo')->path()
        );
    }

    /**
     * @dataProvider cases
     */
    public function testFormatNotAltered(string $url)
    {
        $this->assertSame(
            $url,
            (string) Url::fromString($url)
        );
    }

    /**
     * @dataProvider parseable
     */
    public function testParse(string $url)
    {
        $this->assertInstanceOf(
            UrlInterface::class,
            Url::fromString($url)
        );
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
        ];
    }
}
