<?php

declare(strict_types=1);

namespace App\Importer;

use Doctrine\DBAL\Driver\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Iterator;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

abstract class Importer
{
    public const FORMAT_CSV = 'csv';
    protected static array $mapping = [
        // 'property' => 'row index'
    ];
    private static bool $_error = false;
    private static array $_entityCache = [];
    protected EntityManagerInterface $em;
    protected LoggerInterface $logger;

    public function __construct(EntityManagerInterface $em, LoggerInterface $logger)
    {
        $this->em = $em;
        $this->logger = $logger;
    }

    public function import(Iterator $data, string $format = self::FORMAT_CSV): ?int
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

            self::$_error = true;
        }

        return self::$_error ? null : count($entities);
    }

    protected function createEntityByCSVRowData(array $data): ?object
    {
        return $this->createEntityByMapping($data, static::$mapping);
    }

    abstract public function createEntityByMapping(array $data, array $mapping): ?object;

    protected function getAssociatedEntity(string $entityClassName, string $identifier, $property = 'identifier'): ?object
    {
        if (
            array_key_exists($entityClassName . $identifier, self::$_entityCache)
            && self::$_entityCache[$entityClassName . $identifier] instanceof $entityClassName) {
            $entity = self::$_entityCache[$entityClassName . $identifier];
        } else {
            $entity = $this->em->getRepository($entityClassName)->findOneBy([$property => $identifier]);
        }

        if (!$entity) {
            $entity = new $entityClassName();
            $entity->setName($identifier);
        }

        self::$_entityCache[$entityClassName . $identifier] = $entity;

        return $entity;
    }
}
