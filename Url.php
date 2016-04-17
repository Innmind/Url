<?php
declare(strict_types = 1);

namespace Innmind\Url;

use Innmind\Url\{
    Authority\UserInformation,
    Authority\UserInformation\User,
    Authority\UserInformation\NullUser,
    Authority\UserInformation\Password,
    Authority\UserInformation\NullPassword,
    Authority\Host,
    Authority\NullHost,
    Authority\Port,
    Authority\NullPort,
    Exception\InvalidArgumentException
};

final class Url implements UrlInterface
{
    private $scheme;
    private $authority;
    private $path;
    private $query;
    private $fragment;

    public function __construct(
        SchemeInterface $scheme,
        AuthorityInterface $authority,
        PathInterface $path,
        QueryInterface $query,
        FragmentInterface $fragment
    ) {
        $this->scheme = $scheme;
        $this->authority = $authority;
        $this->path = $path;
        $this->query = $query;
        $this->fragment = $fragment;
    }

    public function scheme(): SchemeInterface
    {
        return $this->scheme;
    }

    public function authority(): AuthorityInterface
    {
        return $this->authority;
    }

    public function path(): PathInterface
    {
        return $this->path;
    }

    public function query(): QueryInterface
    {
        return $this->query;
    }

    public function fragment(): FragmentInterface
    {
        return $this->fragment;
    }

    public function __toString(): string
    {
        return sprintf(
            '%s%s%s%s%s',
            $this->scheme,
            !$this->scheme instanceof NullScheme ? '://' . $this->authority : '',
            $this->path,
            !$this->query instanceof NullQuery ? '?' . $this->query : '',
            !$this->fragment instanceof NullFragment ? '#' . $this->fragment : ''
        );
    }

    /**
     * Build a url out of the given string
     *
     * @param string $string
     *
     * @return self
     */
    public static function fromString(string $string): self
    {
        $data = parse_url($string);

        if ($data === false) {
            throw new InvalidArgumentException;
        }

        return new self(
            isset($data['scheme']) ? new Scheme($data['scheme']) : new NullScheme,
            new Authority(
                new UserInformation(
                    isset($data['user']) ? new User($data['user']) : new NullUser,
                    isset($data['pass']) ? new Password($data['pass']) : new NullPassword
                ),
                isset($data['host']) ? new Host($data['host']) : new NullHost,
                isset($data['port']) ? new Port((int) $data['port']) : new NullPort
            ),
            isset($data['path']) && !empty($data['path']) ? new Path($data['path']) : new NullPath,
            isset($data['query']) ? new Query($data['query']) : new NullQuery,
            isset($data['fragment']) ? new Fragment($data['fragment']) : new NullFragment
        );
    }
}
