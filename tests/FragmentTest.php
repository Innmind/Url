<?php
declare(strict_types = 1);

namespace Innmind\Url\Tests;

use Innmind\Url\{
    Fragment,
    FragmentInterface
};

class FragmentTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $f = new Fragment('foo');

        $this->assertInstanceOf(FragmentInterface::class, $f);
        $this->assertSame('foo', (string) $f);
    }

    /**
     * @expectedException Innmind\Url\Exception\InvalidArgumentException
     */
    public function testThrowWhenInvalidFragment()
    {
        new Fragment('foo bar');
    }
}
