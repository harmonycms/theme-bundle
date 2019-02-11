<?php

namespace Harmony\Bundle\ThemeBundle\DependencyInjection\Compiler;

use Harmony\Bundle\ThemeBundle\Twig\Loader\FilesystemLoader;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

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
    }
}