<?php

namespace Harmony\Bundle\ThemeBundle\Twig;

use Harmony\Bundle\ThemeBundle\HarmonyThemeBundle;
use Helis\SettingsManagerBundle\Settings\SettingsRouter;
use Liip\ThemeBundle\ActiveTheme;
use Symfony\Component\Asset\Packages;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Twig_Extension_GlobalsInterface;

/**
 * Class Extension
 *
 * @package HarmonyCore\WebBundle\Twig
 */
class Extension extends AbstractExtension implements Twig_Extension_GlobalsInterface
{

    /** @var Packages $packages */
    protected $packages;

    /** @var ActiveTheme $activeTheme */
    protected $activeTheme;

    /** @var SettingsRouter $settingsRouter */
    protected $settingsRouter;

    /**
     * Extension constructor.
     *
     * @param Packages       $packages
     * @param ActiveTheme    $activeTheme
     * @param SettingsRouter $settingsRouter
     */
    public function __construct(Packages $packages, ActiveTheme $activeTheme, SettingsRouter $settingsRouter)
    {
        $this->packages       = $packages;
        $this->activeTheme    = $activeTheme;
        $this->settingsRouter = $settingsRouter;
    }

    /**
     * @return array
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('asset_theme', [$this, 'getThemeUrl']),
        ];
    }

    /**
     * @return array
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('theme', [$this, 'getThemeUrl'])
        ];
    }

    /**
     * Returns a list of global variables to add to the existing list.
     *
     * @return array An array of global variables
     */
    public function getGlobals(): array
    {
        return [
            'harmony' => [
                'site_name' => $this->settingsRouter->get('site_name')
            ]
        ];
    }

    /**
     * @param string $path
     * @param string $packageName
     *
     * @return string
     */
    public function getThemeUrl(string $path, string $packageName = null): string
    {
        $path = sprintf('%s/%s/%s', HarmonyThemeBundle::THEMES_DIR, $this->activeTheme->getName(), $path);

        return $this->packages->getUrl($path, $packageName);
    }
}