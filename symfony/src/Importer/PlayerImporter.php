<?php

declare(strict_types=1);

namespace App\Importer;

use App\Entity\Player;
use App\Entity\Team;
use Cooolinho\CSVImporterBundle\Importer\EntityEntityImporter;
use Exception;
use Psr\Log\LogLevel;

class PlayerImporter extends EntityEntityImporter
{
    private const PROPERTY_LASTNAME = 'lastname';
    private const PROPERTY_FIRSTNAME = 'firstname';
    private const PROPERTY_BIRTHDAY = 'birthday';
    private const PROPERTY_TEAM = 'team';

    public function createEntityByMapping(array $data, array $mapping): Player
    {
        $player = new Player();

        try {
            $player->setFirstname(utf8_encode($data[$mapping[self::PROPERTY_FIRSTNAME]]));
            $player->setLastname(utf8_encode($data[$mapping[self::PROPERTY_LASTNAME]]));
        } catch (Exception $e) {
            $this->logger->log(LogLevel::WARNING, $e->getMessage(), [$data]);
        }

        if (
            $data[$mapping[self::PROPERTY_TEAM]] !== ''
            && $team = $this->getAssociatedEntity(Team::class, utf8_encode($data[$mapping[self::PROPERTY_TEAM]]))) {
            $player->setTeam($team);
        }

        try {
            $player->setBirthday(new \DateTime((string)utf8_encode($data[$mapping[self::PROPERTY_BIRTHDAY]])));
        } catch (Exception $e) {
            $this->logger->log(LogLevel::WARNING, $e->getMessage(), [__CLASS__, __DIR__, __FUNCTION__]);
        }

        return $player;
    }

    public static function getMapping(): array
    {
        return [
            self::PROPERTY_LASTNAME => 0,
            self::PROPERTY_FIRSTNAME => 1,
            self::PROPERTY_BIRTHDAY => 2,
            self::PROPERTY_TEAM => 3,
        ];
    }
}
