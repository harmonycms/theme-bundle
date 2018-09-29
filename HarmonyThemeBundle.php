<?php

namespace Harmony\Bundle\ThemeBundle;

use Harmony\Bundle\ThemeBundle\DependencyInjection\HarmonyThemeExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
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
     * Returns the bundle's container extension.
     *
     * @return ExtensionInterface|null The container extension
     * @throws \LogicException
     */
    public function getContainerExtension(): ExtensionInterface
    {
        return new HarmonyThemeExtension();
    }
}