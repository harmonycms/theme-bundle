<?php

namespace Harmony\Bundle\ThemeBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class MenuConfiguration
 *
 * @package Harmony\Bundle\ThemeBundle\DependencyInjection
 */
class MenuConfiguration implements ConfigurationInterface
{

    /**
     * Generates the configuration tree builder.
     *
     * @return TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('menu');
        $rootNode    = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->arrayNode('childrenAttributes')
                    ->prototype('variable')
                    ->end()
                ->end()
                ->arrayNode('itemAttributes')
                    ->prototype('variable')
                    ->end()
                ->end()
                ->arrayNode('linkAttributes')
                    ->prototype('variable')
                    ->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}