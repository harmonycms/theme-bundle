<?php

namespace Harmony\Bundle\ThemeBundle\Locator;

use Harmony\Bundle\ThemeBundle\HarmonyThemeBundle;
use Harmony\Bundle\ThemeBundle\Json\JsonFile;
use Harmony\Bundle\ThemeBundle\Model\Theme;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

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

    /** @var Serializer $serializer */
    protected $serializer;

    /**
     * ThemeLocator constructor.
     *
     * @param string $projectDir
     */
    public function __construct(string $projectDir = '')
    {
        $this->projectDir = $projectDir;
        $this->serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
    }

    /**
     * Returns array of Theme.
     *
     * @return Theme[]
     * @throws \Harmony\Bundle\ThemeBundle\Json\JsonValidationException
     * @throws \Seld\JsonLint\ParsingException
     */
    public function discoverThemes(): array
    {
        $finder    = new Finder();
        $themeList = $this->projectDir . DIRECTORY_SEPARATOR . HarmonyThemeBundle::THEMES_DIR . DIRECTORY_SEPARATOR;
        /** @var \FilesystemIterator $file */
        foreach ($finder->directories()->in($themeList)->depth('== 0') as $file) {
            $composer = $file->getPathname() . '/composer.json';
            if (file_exists($composer)) {
                $json = new JsonFile($composer);
                if ($json->validateSchema()) {
                    /** @var Theme $theme */
                    $theme = $this->serializer->deserialize(file_get_contents($json->getPath()), Theme::class, 'json');
                    $theme->setDir($file->getFilename())->setPath($file->getPathname());
                    $this->themeData[$file->getFilename()] = $theme;
                }
            }
        }

        return $this->getThemeData();
    }

    /**
     * @return array
     */
    public function getThemeData(): array
    {
        return $this->themeData;
    }
}