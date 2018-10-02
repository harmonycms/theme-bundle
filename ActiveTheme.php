<?php

namespace Harmony\Bundle\ThemeBundle;

use Harmony\Bundle\ThemeBundle\Locator\ThemeLocator;
use Liip\ThemeBundle\ActiveTheme as LiipActiveTheme;
use Liip\ThemeBundle\Helper\DeviceDetectionInterface;

/**
 * Class ActiveTheme
 *
 * @package Harmony\Bundle\ThemeBundle
 */
class ActiveTheme extends LiipActiveTheme
{

    /** @var array $themeData */
    protected $themeData = [];

    /**
     * ActiveTheme constructor.
     *
     * @param string                        $name
     * @param array                         $themes
     * @param DeviceDetectionInterface|null $deviceDetection
     * @param ThemeLocator                  $themeLocator
     *
     * @throws Json\JsonValidationException
     * @throws \Seld\JsonLint\ParsingException
     */
    public function __construct(string $name, array $themes, DeviceDetectionInterface $deviceDetection,
                                ThemeLocator $themeLocator)
    {
        $themes          = $themeLocator->discoverThemes();
        $this->themeData = $themeLocator->getThemeData();
        parent::__construct($name, $themes, $deviceDetection);
    }

    /**
     * Get themeData
     *
     * @return array
     */
    public function getThemeData(): array
    {
        return $this->themeData;
    }
}