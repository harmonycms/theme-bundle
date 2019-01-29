<?php

namespace Harmony\Bundle\ThemeBundle\Model;

use JMS\Serializer\Annotation as Serializer;

/**
 * Class Theme
 *
 * @package Harmony\Bundle\ThemeBundle\Model
 */
class Theme
{

    /**
     * @Serializer\Type(name="string")
     * @var string $name
     */
    private $name;

    /**
     * @Serializer\Type(name="string")
     * @var string $description
     */
    private $description;

    /**
     * @Serializer\Type(name="string")
     * @var string $homepage
     */
    private $homepage;

    /**
     * @Serializer\Type(name="string")
     * @var string $license
     */
    private $license;

    /**
     * @Serializer\Type(name="string")
     * @var string $version
     */
    private $version;

    /**
     * @Serializer\Type(name="array<Harmony\Bundle\ThemeBundle\Model\ThemeAuthor>")
     * @var ThemeAuthor[] $authors
     */
    private $authors = [];

    /**
     * @Serializer\Type(name="string")
     * @var string $path
     */
    private $path;

    /**
     * @Serializer\Type(name="string")
     * @var string $dir
     */
    private $dir;

    /**
     * @Serializer\Type(name="Harmony\Bundle\ThemeBundle\Model\ThemeExtra")
     * @var null|ThemeExtra $extra
     */
    private $extra;

    /**
     * @var string $preview
     */
    private $preview = '/assets/preview.{jpg,jpeg,png,gif}';

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

    /**
     * @return null|ThemeExtra
     */
    public function getExtra(): ?ThemeExtra
    {
        return $this->extra;
    }

    /**
     * @param ThemeExtra $extra
     *
     * @return Theme
     */
    public function setExtra(ThemeExtra $extra): Theme
    {
        $this->extra = $extra;

        return $this;
    }

    /**
     * @return string
     */
    public function getPreview(): string
    {
        $array = glob($this->getPath() . $this->preview, GLOB_BRACE);
        if (isset($array[0])) {
            return sprintf('/themes/%s/%s', $this->getDir(), (new \SplFileInfo($array[0]))->getBasename());
        }

        return '';
    }

    /**
     * @param string $preview
     *
     * @return Theme
     */
    public function setPreview(string $preview): Theme
    {
        $this->preview = $preview;

        return $this;
    }
}