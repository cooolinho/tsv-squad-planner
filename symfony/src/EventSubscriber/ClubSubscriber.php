<?php

namespace App\EventSubscriber;

use App\Entity\Club;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityDeletedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ClubSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityDeletedEvent::class => 'onBeforeEntityDeletedEvent',
        ];
    }

    public function onBeforeEntityDeletedEvent(BeforeEntityDeletedEvent $event): void
    {
        if ($event->getEntityInstance() instanceof Club) {
            $this->unsetPlayersByTeam($event->getEntityInstance());
        }
    }

    private function unsetPlayersByTeam(Club $club): void
    {
        foreach ($club->getPlayers() as $player) {
            $player->setClub(null);
        }
    }
}
