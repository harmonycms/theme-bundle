<?php

namespace Harmony\Bundle\ThemeBundle\Model;

use JMS\Serializer\Annotation as Serializer;

/**
 * Class ThemeExtra
 *
 * @package Harmony\Bundle\ThemeBundle\Model
 */
class ThemeExtra
{

    /**
     * @Serializer\Type(name="string")
     * @var null|string $name
     */
    private $name;

    /**
     * @Serializer\Type(name="string")
     * @var null|string $description
     */
    private $description;

    /**
     * @Serializer\Type(name="string")
     * @var null|string $preview
     */
    private $preview;

    /**
     * @Serializer\Type(name="array<string>")
     * @var array $parents
     */
    private $parents = [];

    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return ThemeExtra
     */
    public function setName(string $name): ThemeExtra
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return ThemeExtra
     */
    public function setDescription(string $description): ThemeExtra
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getPreview(): ?string
    {
        return $this->preview;
    }

    /**
     * @param string $preview
     *
     * @return ThemeExtra
     */
    public function setPreview(string $preview): ThemeExtra
    {
        $this->preview = $preview;

        return $this;
    }

    /**
     * @return array
     */
    public function getParents(): array
    {
        return $this->parents;
    }

    /**
     * @param array $parents
     *
     * @return ThemeExtra
     */
    public function setParents(array $parents): ThemeExtra
    {
        $this->parents = $parents;

        return $this;
    }
}