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
     * @var string $screenshot
     */
    private $screenshot;

    /**
     * @Serializer\Type(name="array<string>")
     * @var array $parents
     */
    private $parents = [];

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
     * @return ThemeExtra
     */
    public function setName(string $name): ThemeExtra
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
     * @return ThemeExtra
     */
    public function setDescription(string $description): ThemeExtra
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getScreenshot(): string
    {
        return $this->screenshot;
    }

    /**
     * @param string $screenshot
     *
     * @return ThemeExtra
     */
    public function setScreenshot(string $screenshot): ThemeExtra
    {
        $this->screenshot = $screenshot;

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