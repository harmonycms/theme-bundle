<?php

namespace Harmony\Bundle\ThemeBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class ThemeCompilerPass
 *
 * @package Harmony\Bundle\ThemeBundle\DependencyInjection\Compiler
 */
class ThemeCompilerPass implements CompilerPassInterface
{

    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     *
     * @throws \Exception
     */
    public function process(ContainerBuilder $container)
    {
        $themeLocator = $container->get('harmony.theme.theme_locator');
        $container->setParameter('liip_theme.themes', $themeLocator->discoverThemes());
    }
}