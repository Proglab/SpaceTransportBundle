<?php

namespace Proglab\SpaceTransportBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('space_transport');
        $treeBuilder->getRootNode()
            ->children()
            ->scalarNode('url')->end()
            ->end();

        return $treeBuilder;
    }
}
