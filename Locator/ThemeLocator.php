<?php

namespace Harmony\Bundle\ThemeBundle\Locator;

use Harmony\Bundle\ThemeBundle\HarmonyThemeBundle;
use Symfony\Component\Finder\Finder;

/**
 * Class ThemeLocator
 *
 * @package Harmony\Bundle\ThemeBundle\Locator
 */
class ThemeLocator
{

    /** @var string $projectDir */
    protected $projectDir;

    /**
     * ThemeLocator constructor.
     *
     * @param string $projectDir
     */
    public function __construct(string $projectDir = '')
    {
        $this->projectDir = $projectDir;
    }

    /**
     * @return array
     */
    public function discoverThemes(): array
    {
        $finder    = new Finder();
        $themes    = [];
        $themeList = $this->projectDir . DIRECTORY_SEPARATOR . HarmonyThemeBundle::THEMES_DIR . DIRECTORY_SEPARATOR;
        /** @var \FilesystemIterator $file */
        foreach ($finder->directories()->in($themeList)->depth('== 0') as $file) {
            $themes[$file->getPathname()] = $file->getFilename();
        }

        return $themes;
    }
}