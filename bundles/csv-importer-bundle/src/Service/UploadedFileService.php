<?php

declare(strict_types=1);

namespace Cooolinho\CSVImporterBundle\Service;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class UploadedFileService
{
    private LoggerInterface $logger;
    private SluggerInterface $slugger;

    public function __construct(LoggerInterface $logger, SluggerInterface $slugger)
    {
        $this->logger = $logger;
        $this->slugger = $slugger;
    }

    public function upload(UploadedFile $file, string $uploadDirectory): ?File
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $fileExtension = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);

        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename . '-' . uniqid('', true) . '.' . strtolower($fileExtension);

        try {
            return $file->move(
                $uploadDirectory,
                $newFilename
            );
        } catch (FileException $e) {
            $this->logger->log(LogLevel::ERROR, $e->getMessage(), [__CLASS__, __DIR__]);
        }

        return null;
    }
}
