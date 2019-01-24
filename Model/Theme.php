<?php

namespace Harmony\Bundle\ThemeBundle\Model;

/**
 * Class Theme
 *
 * @package Harmony\Bundle\ThemeBundle\Model
 */
class Theme
{

    /**
     * @var string $name
     */
    private $name;

    /**
     * @var string $description
     */
    private $description;

    /**
     * @var string $homepage
     */
    private $homepage;

    /**
     * @var string $license
     */
    private $license;

    /**
     * @var string $version
     */
    private $version;

    /**
     * @var ThemeAuthor[] $authors
     */
    private $authors = [];

    /**
     * @var string $path
     */
    private $path;

    /** @var string $dir */
    private $dir;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Theme
     */
    public function setName(string $name): Theme
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return Theme
     */
    public function setDescription(string $description): Theme
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getHomepage(): string
    {
        return $this->homepage;
    }

    /**
     * @param string $homepage
     *
     * @return Theme
     */
    public function setHomepage(string $homepage): Theme
    {
        $this->homepage = $homepage;

        return $this;
    }

    /**
     * @return string
     */
    public function getLicense(): string
    {
        return $this->license;
    }

    /**
     * @param string $license
     *
     * @return Theme
     */
    public function setLicense(string $license): Theme
    {
        $this->license = $license;

        return $this;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @param string $version
     *
     * @return Theme
     */
    public function setVersion(string $version): Theme
    {
        $this->version = $version;

        return $this;
    }

    /**
     * @return ThemeAuthor[]
     */
    public function getAuthors(): array
    {
        return $this->authors;
    }

    /**
     * @param ThemeAuthor[] $authors
     *
     * @return Theme
     */
    public function setAuthors(array $authors): Theme
    {
        $this->authors = $authors;

        return $this;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     *
     * @return Theme
     */
    public function setPath(string $path): Theme
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return string
     */
    public function getDir(): string
    {
        return $this->dir;
    }

    /**
     * @param string $dir
     *
     * @return Theme
     */
    public function setDir(string $dir): Theme
    {
        $this->dir = $dir;

        return $this;
    }
}