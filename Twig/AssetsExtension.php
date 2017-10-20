<?php

namespace Harmony\Bundle\ThemeBundle\Twig;

use Harmony\Bundle\ThemeBundle\ActiveTheme;
use Harmony\Bundle\ThemeBundle\HarmonyThemeBundle;
use Symfony\Bridge\Twig\Extension\AssetExtension as BridgeAssetExtension;
use Symfony\Component\Asset\Packages;

/**
 * Class AssetsExtension
 *
 * @package Harmony\Bundle\ThemeBundle\Twig
 */
class AssetsExtension extends BridgeAssetExtension
{

    /** @var ActiveTheme */
    protected $activeTheme;

    /** @var string */
    protected $projectDir;

    /**
     * AssetsExtension constructor.
     *
     * @param Packages    $packages
     * @param ActiveTheme $activeTheme
     * @param string      $projectDir
     */
    public function __construct(Packages $packages, ActiveTheme $activeTheme, string $projectDir)
    {
        parent::__construct($packages);
        $this->activeTheme = $activeTheme;
        $this->projectDir  = $projectDir;
    }

    /**
     * Returns the public url/path of an asset.
     * If the package used to generate the path is an instance of
     * UrlPackage, you will always get a URL and not a path.
     *
     * @param string $path        A public path
     * @param string $packageName The name of the asset package to use
     *
     * @return string The public path of the asset
     */
    public function getAssetUrl($path, $packageName = null): string
    {
        $url = sprintf('%s/%s/%s', HarmonyThemeBundle::THEMES_DIR, $this->activeTheme->getName(), $path);
        if (is_file($this->projectDir . '/web/' . $url)) {
            return parent::getAssetUrl($url, $packageName);
        }

        return parent::getAssetUrl($path, $packageName);
    }
}