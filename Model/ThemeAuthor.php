<?php

namespace Harmony\Bundle\ThemeBundle\Model;

use JMS\Serializer\Annotation as Serializer;

/**
 * Class ThemeAuthor
 *
 * @package Harmony\Bundle\ThemeBundle\Model
 */
class ThemeAuthor
{

    /**
     * @Serializer\Type(name="string")
     * @var string $name
     */
    private $name;

    /**
     * @Serializer\Type(name="string")
     * @var string $email
     */
    private $email;

    /**
     * @Serializer\Type(name="string")
     * @var string $homepage
     */
    private $homepage;

    /**
     * @Serializer\Type(name="string")
     * @var string $role
     */
    private $role;

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
     * @return ThemeAuthor
     */
    public function setName(string $name): ThemeAuthor
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return ThemeAuthor
     */
    public function setEmail(string $email): ThemeAuthor
    {
        $this->email = $email;

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
     * @return ThemeAuthor
     */
    public function setHomepage(string $homepage): ThemeAuthor
    {
        $this->homepage = $homepage;

        return $this;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @param string $role
     *
     * @return ThemeAuthor
     */
    public function setRole(string $role): ThemeAuthor
    {
        $this->role = $role;

        return $this;
    }
}