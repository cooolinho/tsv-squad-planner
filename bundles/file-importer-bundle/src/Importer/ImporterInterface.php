<?php

declare(strict_types=1);

namespace Cooolinho\FileImporterBundle\Importer;

use Cooolinho\FileImporterBundle\Reader\CsvReader;
use Iterator;

interface ImporterInterface
{
    public function import(Iterator $data, string $format = CsvReader::TYPE);
}
