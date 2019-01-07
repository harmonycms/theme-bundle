<?php

namespace Harmony\Bundle\ThemeBundle\Twig;

use Harmony\Bundle\CoreBundle\DependencyInjection\HarmonyCoreExtension;
use Harmony\Bundle\ThemeBundle\ActiveTheme;
use Harmony\Bundle\ThemeBundle\HarmonyThemeBundle;
use Symfony\Component\Asset\Packages;
use Symfony\Component\DependencyInjection\ContainerInterface;
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

    /** @var ContainerInterface */
    protected $container;

    /**
     * Extension constructor.
     *
     * @param Packages           $packages
     * @param ActiveTheme        $activeTheme
     * @param ContainerInterface $container
     */
    public function __construct(Packages $packages, ActiveTheme $activeTheme, ContainerInterface $container)
    {
        $this->packages    = $packages;
        $this->activeTheme = $activeTheme;
        $this->container   = $container;
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
                'site_name' => $this->container->getParameter(HarmonyCoreExtension::ALIAS)['site_name']
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