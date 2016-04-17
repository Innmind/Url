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
}
