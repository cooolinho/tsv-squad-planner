<?php

declare(strict_types=1);

namespace Cooolinho\Bundle\FileImporterBundle\Importer;

use Cooolinho\Bundle\FileImporterBundle\Reader\CsvReader;
use Doctrine\DBAL\Driver\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Iterator;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

abstract class EntityEntityImporter implements EntityImporterInterface
{
    protected EntityManagerInterface $em;
    protected LoggerInterface $logger;
    protected static array $entityCache = [];
    protected static bool $error = false;

    public function __construct(EntityManagerInterface $em, LoggerInterface $logger)
    {
        $this->em = $em;
        $this->logger = $logger;
    }

    abstract public function createEntityByMapping(array $data, array $mapping): ?object;

    public function import(Iterator $data, string $format = CsvReader::TYPE): ?int
    {
        $entities = [];

        foreach ($data as $key => $row) {
            if ($key === 0 && !empty($row)) {
                continue;
            }

            $entity = $this->createEntityByCSVRowData($row);
            $this->em->persist($entity);
            $entities[] = $entity;
        }

        try {
            $this->em->flush();
        } catch (Exception $e) {
            $this->logger->log(LogLevel::ERROR, $e->getMessage(), [__CLASS__, __DIR__, __FUNCTION__]);

            self::$error = true;
        }

        return self::$error ? null : count($entities);
    }

    protected function createEntityByCSVRowData(array $data): ?object
    {
        return $this->createEntityByMapping($data, static::getMapping());
    }

    protected function getAssociatedEntity(string $entityClassName, string $identifier, $property = 'identifier'): ?object
    {
        if (
            array_key_exists($entityClassName . $identifier, self::$entityCache)
            && self::$entityCache[$entityClassName . $identifier] instanceof $entityClassName) {
            $entity = self::$entityCache[$entityClassName . $identifier];
        } else {
            $entity = $this->em->getRepository($entityClassName)->findOneBy([$property => $identifier]);
        }

        if (!$entity) {
            $entity = new $entityClassName();
            $entity->setName($identifier);
        }

        self::$entityCache[$entityClassName . $identifier] = $entity;

        return $entity;
    }
}
