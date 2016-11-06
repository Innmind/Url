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

    public function cases(): array
    {
        return [
            ['#foobar'],
            ['?some=query'],
        ];
    }
}
