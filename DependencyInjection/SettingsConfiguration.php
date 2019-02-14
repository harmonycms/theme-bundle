<?php

namespace Harmony\Bundle\ThemeBundle\DependencyInjection;

use Helis\SettingsManagerBundle\Model\DomainModel;
use Helis\SettingsManagerBundle\Model\Type;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class SettingsConfiguration
 *
 * @package Harmony\Bundle\ThemeBundle\DependencyInjection
 */
class SettingsConfiguration implements ConfigurationInterface
{

    /**
     * Generates the configuration tree builder.
     *
     * @return TreeBuilder The tree builder
     * @throws \ReflectionException
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('settings');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->arrayPrototype()
                ->children()
                    ->scalarNode('name')->isRequired()->end()
                    ->scalarNode('description')->end()
                    ->arrayNode('domain')
                        ->addDefaultsIfNotSet()
                        ->beforeNormalization()
                            ->ifString()
                            ->then(function ($v) {
                                return [
                                    'name' => $v,
                                    'enabled' => true, // domains from config are enabled by default
                                    'read_only' => true, // all config domains are read only
                                ];
                            })
                        ->end()
                        ->children()
                            ->scalarNode('name')->defaultValue(DomainModel::DEFAULT_NAME)->end()
                            ->booleanNode('enabled')->defaultTrue()->end()
                            ->booleanNode('read_only')->defaultTrue()->end()
                        ->end()
                    ->end()
                    ->arrayNode('tags')
                        ->arrayPrototype()
                            ->beforeNormalization()
                            ->ifString()
                            ->then(function ($v) {
                                return ['name' => $v];
                            })
                            ->end()
                            ->children()
                                ->scalarNode('name')->end()
                            ->end()
                        ->end()
                    ->end()
                    ->enumNode('type')
                        ->values(array_values(Type::toArray()))
                        ->isRequired()
                    ->end()
                    ->arrayNode('type_options')
                       ->variablePrototype()->end()
                    ->end()
                    ->arrayNode('data')
                        ->beforeNormalization()
                        ->always()
                        ->then(function ($v) {
                            if (is_string($v) || is_int($v) || is_float($v)) {
                                return ['value' => $v];
                            }

                            if (is_array($v) && isset($v['value'])) {
                                return $v;
                            }

                            return ['value' => $v];
                        })
                        ->end()
                        ->children()
                            ->variableNode('value')
                            ->isRequired()
                            ->end()
                        ->end()
                    ->end()
                    ->arrayNode('choices')
                        ->variablePrototype()->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}