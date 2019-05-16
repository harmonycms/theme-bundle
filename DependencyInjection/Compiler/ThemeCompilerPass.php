<?php

namespace Harmony\Bundle\ThemeBundle\DependencyInjection\Compiler;

use Harmony\Bundle\ThemeBundle\Twig\Loader\FilesystemLoader;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class ThemeCompilerPass
 *
 * @package Harmony\Bundle\ThemeBundle\DependencyInjection\Compiler
 */
class ThemeCompilerPass implements CompilerPassInterface
{

    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $twigFilesystemLoaderDefinition = $container->findDefinition('twig.loader.filesystem');
        $twigFilesystemLoaderDefinition->setClass(FilesystemLoader::class);

        if (false === $container->has('templating')) {
            $twigFilesystemLoaderDefinition->replaceArgument(0,
                $container->getDefinition('liip_theme.templating_locator'));
            $twigFilesystemLoaderDefinition->replaceArgument(1,
                $container->getDefinition('templating.filename_parser'));
            $twigFilesystemLoaderDefinition->setArgument(2, new Reference('kernel'));
            $twigFilesystemLoaderDefinition->setArgument(3, $container->getParameter('harmony.theme_default'));
        }

        $twigFilesystemLoaderDefinition->addMethodCall('setActiveTheme', [new Reference('liip_theme.active_theme')]);
    }
}