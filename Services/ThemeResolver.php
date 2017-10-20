<?php

namespace Harmony\Bundle\ThemeBundle\Services;

use Harmony\Bundle\CoreBundle\DependencyInjection\HarmonyCoreExtension;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Determines the theme that should currently be used.
 *
 * @package Harmony\Bundle\ThemeBundle\Services
 */
class ThemeResolver
{

    /** @var ContainerInterface */
    protected $container;

    /**
     * ThemeResolver constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Get current active theme from parameter: `harmony.theme`.
     *
     * @return string
     */
    public function getActiveTheme(): string
    {
        return $this->container->getParameter(HarmonyCoreExtension::ALIAS . '.theme');
    }
}