<?php

declare(strict_types=1);

namespace Cooolinho\CSVImporterBundle\Reader;

use Iterator;
use League\Csv\Exception;
use League\Csv\Reader;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Symfony\Component\HttpFoundation\File\File;

class CsvReader
{
    public const TYPE = 'csv';
    public const DEFAULT_DELIMETER = ';';

    private LoggerInterface $logger;
    private string $delimeter = self::DEFAULT_DELIMETER;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function getIterableData(File $file): Iterator
    {
        $filePath = $file->getPath() . DIRECTORY_SEPARATOR . $file->getFilename();

        return $this->readFile($filePath)->getIterator();
    }

    private function readFile(string $filePath): Reader
    {
        $reader = Reader::createFromPath($filePath);

        try {
            $reader->setDelimiter($this->delimeter);
        } catch (Exception $e) {
            $this->logger->log(LogLevel::WARNING, $e->getMessage(), [__CLASS__, __DIR__, __METHOD__]);
        }

        return $reader;
    }

    public function setDelimeter(string $delimeter): self
    {
        $this->delimeter = $delimeter;
        return $this;
    }
}
