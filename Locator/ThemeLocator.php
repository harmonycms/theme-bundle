<?php

namespace Harmony\Bundle\ThemeBundle\Locator;

use Harmony\Bundle\ThemeBundle\HarmonyThemeBundle;
use Harmony\Bundle\ThemeBundle\Json\JsonFile;
use Harmony\Bundle\ThemeBundle\Model\Theme;
use JMS\Serializer\SerializerInterface;
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

    /** @var SerializerInterface $serializer */
    protected $serializer;

    /**
     * ThemeLocator constructor.
     *
     * @param string              $projectDir
     * @param SerializerInterface $serializer
     */
    public function __construct(string $projectDir, SerializerInterface $serializer)
    {
        $this->projectDir = $projectDir;
        $this->serializer = $serializer;
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
                    $data         = $json->read();
                    $data['dir']  = $file->getFilename();
                    $data['path'] = $file->getPathname();

                    $this->themeData[$file->getFilename()] = $this->serializer->deserialize(json_encode($data),
                        Theme::class, 'json');
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