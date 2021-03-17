<?php

declare(strict_types=1);

namespace Cooolinho\CSVImporterBundle\Importer;

use Cooolinho\CSVImporterBundle\Reader\CsvReader;
use Iterator;

interface ImporterInterface
{
    public function import(Iterator $data, string $format = CsvReader::TYPE);
}
