<?php

declare(strict_types=1);

namespace Cooolinho\Bundle\FileImporterBundle\Importer;

use Cooolinho\Bundle\FileImporterBundle\Reader\CsvReader;
use Iterator;

interface ImporterInterface
{
    public function import(Iterator $data, string $format = CsvReader::TYPE);
}
