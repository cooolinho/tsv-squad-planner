<?php

namespace Cooolinho\CSVImporterBundle\DependencyInjection;

use Cooolinho\CSVImporterBundle\CooolinhoCSVImporterBundle;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder(CooolinhoCSVImporterBundle::CONFIGURATION_KEY);

        return $treeBuilder;
    }
}
