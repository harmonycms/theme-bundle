<?php

namespace Harmony\Bundle\ThemeBundle\DependencyInjection;

use Harmony\Bundle\SettingsManagerBundle\Provider\DoctrineOrmSettingsProvider;
use Harmony\Bundle\ThemeBundle\Provider\ThemeProviderFactory;
use Harmony\Sdk\Theme\ThemeInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;
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

        $twigFilesystemLoaderDefinition = $container->getDefinition('twig.loader.filesystem');
        // register themes as Twig namespaces
        foreach ($container->getParameter('kernel.themes') as $namespace => $class) {
            /** @var ThemeInterface $themeClass */
            $themeClass = new $class();
            $twigFilesystemLoaderDefinition->addMethodCall('addPath', [$themeClass->getPath(), $namespace]);
        }

        // Register $provider for Doctrine ORM, fallback to lazy provider otherwise
        $themeProviderFactoryDefinition = $container->findDefinition(ThemeProviderFactory::class);
        if ($container->has(DoctrineOrmSettingsProvider::class)) {
            $themeProviderFactoryDefinition->setArgument('$provider',
                new Reference(DoctrineOrmSettingsProvider::class));
        } else {
            $themeProviderFactoryDefinition->setArgument('$provider',
                new Reference('settings_manager.provider.config'));
        }
    }

    /**
     * Allow an extension to prepend the extension configurations.
     *
     * @param ContainerBuilder $container
     *
     * @throws \Exception
     */
    public function prepend(ContainerBuilder $container)
    {
        // Generate a config array with the content of `liip_theme.yml` file
        $liipThemeConfig = Yaml::parse(file_get_contents(dirname(__DIR__) . '/Resources/config/liip_theme.yaml'));

        // Set available themes
        $liipThemeConfig['liip_theme']['themes'] = array_keys($container->getParameter('kernel.themes'));

        // Prepend the `liip_theme` settings
        $container->prependExtensionConfig('liip_theme', $liipThemeConfig['liip_theme']);

        // get all bundles
        $bundles = $container->getParameter('kernel.bundles');

        if (isset($bundles['HarmonySettingsManagerBundle'])) {
            // Generate a config array with the content of `settings_manager.yml` file
            $settings = Yaml::parse(file_get_contents(dirname(__DIR__) . '/Resources/config/settings_manager.yaml'));

            // Prepend the `harmony_settings_manager` settings
            $container->prependExtensionConfig('harmony_settings_manager', $settings['harmony_settings_manager']);
        }
    }
}