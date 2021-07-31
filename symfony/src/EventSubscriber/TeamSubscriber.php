<?php

namespace App\EventSubscriber;

use App\Entity\Player;
use App\Entity\Team;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityDeletedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class TeamSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityDeletedEvent::class => 'onBeforeEntityDeletedEvent',
        ];
    }

    public function onBeforeEntityDeletedEvent(BeforeEntityDeletedEvent $event): void
    {
        if ($event->getEntityInstance() instanceof Team) {
            $this->unsetPlayersByTeam($event->getEntityInstance());
        }
    }

    private function unsetPlayersByTeam(Team $team): void
    {
        /** @var Player $player */
        foreach ($team->getPlayers() as $player) {
            $player->setTeam(null);
        }
    }
}
