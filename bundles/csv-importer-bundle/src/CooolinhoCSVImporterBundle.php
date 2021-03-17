<?php

declare(strict_types=1);

namespace Cooolinho\CSVImporterBundle;

use Cooolinho\CSVImporterBundle\DependencyInjection\CooolinhoCSVImporterExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class CooolinhoCSVImporterBundle extends Bundle
{
    public const CONFIGURATION_KEY = 'cooolinho_csv_importer';

    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
    }

    /**
     * @return CooolinhoCSVImporterExtension
     */
    public function getContainerExtension(): CooolinhoCSVImporterExtension
    {
        return new CooolinhoCSVImporterExtension();
    }
}
