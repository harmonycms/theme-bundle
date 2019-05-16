<?php

namespace Harmony\Bundle\ThemeBundle\DependencyInjection;

use Exception;
use Harmony\Sdk\Theme\ThemeInterface;
use InvalidArgumentException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Yaml\Yaml;
use function array_keys;
use function dirname;
use function file_get_contents;

/**
 * Class HarmonyThemeExtension
 *
 * @package Harmony\Bundle\ThemeBundle\DependencyInjection
 */
class HarmonyThemeExtension extends Extension implements PrependExtensionInterface
{

    /**
     * Loads a specific configuration.
     *
     * @param array            $configs   An array of configuration values
     * @param ContainerBuilder $container A ContainerBuilder instance
     *
     * @throws InvalidArgumentException When provided tag is not defined in this extension
     * @throws Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(dirname(__DIR__) . '/Resources/config'));
        $loader->load('services.yaml');

        $twigFilesystemLoaderDefinition = $container->getDefinition('twig.loader.filesystem');
        // register themes as Twig namespaces
        foreach ($container->getParameter('kernel.themes') as $namespace => $class) {
            /** @var ThemeInterface $themeClass */
            $themeClass = new $class();
            $twigFilesystemLoaderDefinition->addMethodCall('addPath', [$themeClass->getPath(), $namespace]);
        }
    }

    /**
     * Allow an extension to prepend the extension configurations.
     *
     * @param ContainerBuilder $container
     *
     * @throws Exception
     */
    public function prepend(ContainerBuilder $container)
    {
        // Generate a config array with the content of `liip_theme.yml` file
        $liipThemeConfig = Yaml::parse(file_get_contents(dirname(__DIR__) . '/Resources/config/liip_theme.yaml'));

        // Set available themes
        $liipThemeConfig['liip_theme']['themes'] = array_keys($container->getParameter('kernel.themes'));

        // Prepend the `liip_theme` settings
        $container->prependExtensionConfig('liip_theme', $liipThemeConfig['liip_theme']);
    }
}