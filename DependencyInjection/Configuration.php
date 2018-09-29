<?php

namespace Harmony\Bundle\ThemeBundle\DependencyInjection;

use Harmony\Bundle\CoreBundle\DependencyInjection\Configuration as BaseConfiguration;
use Harmony\Bundle\CoreBundle\Component\Config\Definition\Builder\TreeBuilder;

/**
 * Class Configuration
 *
 * @package Harmony\Bundle\ThemeBundle\DependencyInjection
 */
class Configuration extends BaseConfiguration
{

    /**
     * Generates the configuration tree builder.
     *
     * @return TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = parent::getConfigTreeBuilder();
        $rootNode    = $treeBuilder->getRoot();

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