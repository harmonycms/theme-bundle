<?php

namespace Harmony\Bundle\ThemeBundle\DependencyInjection;

use Harmony\Bundle\CoreBundle\DependencyInjection\HarmonyCoreExtension;
use Harmony\Bundle\CoreBundle\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 *
 * @package Harmony\Bundle\ThemeBundle\DependencyInjection
 */
class Configuration implements ConfigurationInterface
{

    /**
     * Generates the configuration tree builder.
     *
     * @return TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->setRoot(HarmonyCoreExtension::ALIAS);

        $rootNode
            ->children()
                ->scalarNode('theme')
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->defaultValue('default')
                    ->info('The theme used to render the frontend pages.')
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}