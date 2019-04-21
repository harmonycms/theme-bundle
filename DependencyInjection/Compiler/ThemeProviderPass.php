<?php

namespace Harmony\Bundle\ThemeBundle\DependencyInjection\Compiler;

use Harmony\Bundle\SettingsManagerBundle\Provider\DoctrineOdmSettingsProvider;
use Harmony\Bundle\SettingsManagerBundle\Provider\DoctrineOrmSettingsProvider;
use Harmony\Bundle\ThemeBundle\Provider\ThemeProviderFactory;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class ThemeProviderPass
 *
 * @package Harmony\Bundle\ThemeBundle\DependencyInjection\Compiler
 */
class ThemeProviderPass implements CompilerPassInterface
{

    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        // Register $provider for Doctrine ORM or ODM, fallback to lazy provider otherwise
        $themeProviderFactoryDefinition = $container->findDefinition(ThemeProviderFactory::class);

        if ($container->hasDefinition(DoctrineOrmSettingsProvider::class)) {
            $themeProviderFactoryDefinition->setArgument('$provider',
                new Reference(DoctrineOrmSettingsProvider::class));
        } elseif ($container->hasDefinition(DoctrineOdmSettingsProvider::class)) {
            $themeProviderFactoryDefinition->setArgument('$provider',
                new Reference(DoctrineOdmSettingsProvider::class));
        } else {
            $themeProviderFactoryDefinition->setArgument('$provider',
                new Reference('settings_manager.provider.config'));
        }
    }
}