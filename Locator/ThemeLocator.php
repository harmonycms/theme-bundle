<?php

namespace Harmony\Bundle\ThemeBundle\Locator;

use Harmony\Bundle\ThemeBundle\HarmonyThemeBundle;
use Harmony\Bundle\ThemeBundle\Json\JsonFile;
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

    /** @var array $themeData */
    protected $themeData = [];

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
     * @throws \Harmony\Bundle\ThemeBundle\Json\JsonValidationException
     * @throws \Seld\JsonLint\ParsingException
     */
    public function discoverThemes(): array
    {
        $finder    = new Finder();
        $themes    = [];
        $themeList = $this->projectDir . DIRECTORY_SEPARATOR . HarmonyThemeBundle::THEMES_DIR . DIRECTORY_SEPARATOR;
        /** @var \FilesystemIterator $file */
        foreach ($finder->directories()->in($themeList)->depth('== 0') as $file) {
            $composer = $file->getPathname() . '/composer.json';
            if (file_exists($composer)) {
                $json = new JsonFile($composer);
                if ($json->validateSchema()) {
                    $this->themeData[$file->getFilename()]             = $json->read();
                    $this->themeData[$file->getFilename()]['pathname'] = $file->getPathname();
                    $themes[$file->getPathname()]                      = $file->getFilename();
                }
            }
        }

        return $themes;
    }

    /**
     * @return array
     */
    public function getThemeData(): array
    {
        return $this->themeData;
    }
}