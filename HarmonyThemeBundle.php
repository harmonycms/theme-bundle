<?php

namespace Harmony\Bundle\ThemeBundle;

use Harmony\Bundle\ThemeBundle\DependencyInjection\HarmonyThemeExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

/**
 * Class HarmonyThemeBundle
 *
 * @package Harmony\Bundle\ThemeBundle
 */
class HarmonyThemeBundle extends Bundle
{

    /** Constants */
    const THEMES_DIR = 'themes';
}