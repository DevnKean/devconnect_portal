<?php
namespace AppBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 * @package AppBundle\DependencyInjection
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $treeBuilder->root('activity_log');

        $rootNode = $treeBuilder->root('cethyworks_google_place_autocomplete');
        $rootNode
            ->children()
            ->arrayNode('google')
            ->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('api_key')
            ->isRequired()
            ->cannotBeEmpty()
            ->defaultValue('')
            ->end()
            ->end()
            ->end() // google
            ->end();

        return $treeBuilder;
    }
}