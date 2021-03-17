<?php

declare(strict_types=1);

namespace Cooolinho\FileImporterBundle\Importer;

interface EntityImporterInterface extends ImporterInterface
{
    public static function getMapping(): array;
}
