<?php

namespace Harmony\Bundle\ThemeBundle\DependencyInjection;

use Harmony\Bundle\CoreBundle\Component\Config\Definition\Builder\TreeBuilder;
use Harmony\Bundle\CoreBundle\DependencyInjection\HarmonyCoreExtension;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 *
 * @package Harmony\Bundle\ThemeBundle\DependencyInjection
 */
class Configuration implements ConfigurationInterface
{

    /** @var array $themes */
    protected $themes;

    /**
     * Configuration constructor.
     *
     * @param array $themes
     */
    public function __construct(array $themes)
    {
        $this->themes = $themes;
    }

    /**
     * Generates the configuration tree builder.
     *
     * @return TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder(HarmonyCoreExtension::ALIAS);
        $rootNode    = $treeBuilder->getRoot();

        $rootNode
            ->children()
                ->arrayNode('settings')
                    ->children()
                        ->arrayNode('theme')
                            ->beforeNormalization()
                                ->ifString()
                                ->then(function($value) { return ['value' => $value]; })
                            ->end()
                            ->children()
                                ->scalarNode('value')
                                    ->defaultNull()
                                    ->validate()
                                        ->ifNotInArray($this->themes)
                                        ->thenInvalid('Invalid theme %s. Valid themes are: '.implode(', ', array_map(function($s) { return '"'.$s.'"'; }, $this->themes)).'.')
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                    ->ignoreExtraKeys(true)
                ->end()
            ->end()
            ->ignoreExtraKeys(true)
        ;

        return $treeBuilder;
    }
}