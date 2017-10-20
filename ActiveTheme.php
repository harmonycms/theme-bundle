<?php

namespace Harmony\Bundle\ThemeBundle;

use Harmony\Bundle\ThemeBundle\Locator\ThemeLocator;
use Harmony\Bundle\ThemeBundle\Services\ThemeResolver;
use Liip\ThemeBundle\ActiveTheme as LiipActiveTheme;
use Liip\ThemeBundle\Helper\DeviceDetectionInterface;

/**
 * Class ActiveTheme
 *
 * @package Harmony\Bundle\ThemeBundle
 */
class ActiveTheme extends LiipActiveTheme
{

    /**
     * ActiveTheme constructor.
     *
     * @param string                        $name
     * @param array                         $themes
     * @param DeviceDetectionInterface|null $deviceDetection
     * @param ThemeLocator                  $themeLocator
     * @param ThemeResolver                 $themeResolver
     */
    public function __construct($name, array $themes = [], DeviceDetectionInterface $deviceDetection = null,
                                ThemeLocator $themeLocator, ThemeResolver $themeResolver)
    {
        $themes = $themeLocator->discoverThemes();
        $name   = $themeResolver->getActiveTheme();

        parent::__construct($name, $themes, $deviceDetection);
    }
}