<?php

declare(strict_types=1);

namespace Cooolinho\FileImporterBundle;

use Cooolinho\FileImporterBundle\DependencyInjection\CooolinhoFileImporterExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class CooolinhoFileImporterBundle extends Bundle
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
     * @return CooolinhoFileImporterExtension
     */
    public function getContainerExtension(): CooolinhoFileImporterExtension
    {
        return new CooolinhoFileImporterExtension();
    }
}
