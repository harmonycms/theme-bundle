<?php

namespace Harmony\Bundle\ThemeBundle\DependencyInjection;

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

    /** Constant */
    const CONFIG_FILE = 'config.yml';

    /**
     * Loads a specific configuration.
     *
     * @param array            $configs   An array of configuration values
     * @param ContainerBuilder $container A ContainerBuilder instance
     *
     * @throws \InvalidArgumentException When provided tag is not defined in this extension
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new loader\YamlFileLoader($container,
            new FileLocator(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'Resources/config'));
        $loader->load('services.yml');
    }

    /**
     * Allow an extension to prepend the extension configurations.
     *
     * @param ContainerBuilder $container
     */
    public function prepend(ContainerBuilder $container)
    {
        // generate a config array with the content of `config.yml` file
        $configArray = Yaml::parse(file_get_contents(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'Resources' .
            DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . self::CONFIG_FILE));

        // prepend the `liip_theme` settings
        $container->prependExtensionConfig('liip_theme', $configArray['liip_theme']);
    }
}