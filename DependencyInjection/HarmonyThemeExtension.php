<?php

namespace Harmony\Bundle\ThemeBundle\DependencyInjection;

use Harmony\Bundle\ThemeBundle\Locator\ThemeLocator;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Yaml\Yaml;

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
     * @throws \InvalidArgumentException When provided tag is not defined in this extension
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(dirname(__DIR__) . '/Resources/config'));
        $loader->load('services.yaml');
    }

    /**
     * Allow an extension to prepend the extension configurations.
     *
     * @param ContainerBuilder $container
     *
     * @throws \Harmony\Bundle\ThemeBundle\Json\JsonValidationException
     * @throws \Exception
     */
    public function prepend(ContainerBuilder $container)
    {
        // Generate a config array with the content of `liip_theme.yml` file
        $liipThemeConfig = Yaml::parse(file_get_contents(dirname(__DIR__) . '/Resources/config/liip_theme.yaml'));
        // Prepend the `liip_theme` settings
        $container->prependExtensionConfig('liip_theme', $liipThemeConfig['liip_theme']);

        // get all bundles
        $bundles = $container->getParameter('kernel.bundles');

        if (isset($bundles['HelisSettingsManagerBundle'])) {
            // Generate a config array with the content of `settings_manager.yml` file
            $settings = Yaml::parse(file_get_contents(dirname(__DIR__) . '/Resources/config/settings_manager.yaml'));

            // TODO: inject service instead
            $themeLocator = new ThemeLocator($container->getParameter('kernel.project_dir'));

            $settings['helis_settings_manager']['settings'][0]['choices'] = $themeLocator->discoverThemes();

            // Prepend the `helis_settings_manager` settings
            $container->prependExtensionConfig('helis_settings_manager', $settings['helis_settings_manager']);
        }
    }
}