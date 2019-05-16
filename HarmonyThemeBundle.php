<?php

namespace Harmony\Bundle\ThemeBundle;

use Harmony\Bundle\ThemeBundle\DependencyInjection\Compiler\ThemeCompilerPass;
use Harmony\Bundle\ThemeBundle\DependencyInjection\HarmonyThemeExtension;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class HarmonyThemeBundle
 *
 * @package Harmony\Bundle\ThemeBundle
 */
class HarmonyThemeBundle extends Bundle
{

    /** Constants */
    const THEMES_DIR = 'themes';

    /**
     * Builds the bundle.
     * It is only ever called once when the cache is empty.
     *
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ThemeCompilerPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION, - 10);
    }

    /**
     * Returns the container extension that should be implicitly loaded.
     *
     * @return HarmonyThemeExtension The default extension or null if there is none
     */
    public function getContainerExtension()
    {
        return new HarmonyThemeExtension();
    }
}