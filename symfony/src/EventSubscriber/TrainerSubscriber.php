<?php

namespace App\EventSubscriber;

use App\Entity\Team;
use App\Entity\Trainer;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeCrudActionEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class TrainerSubscriber implements EventSubscriberInterface
{
    public const OLD_TEAMS = 'oldTeams';

    protected EntityManagerInterface $entityManager;
    protected SessionInterface $session;
    protected UserPasswordHasherInterface $passwordEncoder;

    public function __construct(
        EntityManagerInterface      $entityManager,
        SessionInterface            $session,
        UserPasswordHasherInterface $passwordEncoder
    )
    {
        $this->entityManager = $entityManager;
        $this->session = $session;
        $this->passwordEncoder = $passwordEncoder;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            BeforeCrudActionEvent::class => 'onBeforeCrudActionEvent',
            BeforeEntityPersistedEvent::class => 'onBeforeEntityPersistedEvent',
            BeforeEntityUpdatedEvent::class => 'onBeforeEntityUpdatedEvent',
        ];
    }

    public function onBeforeCrudActionEvent(BeforeCrudActionEvent $event): void
    {
        $entity = $event->getAdminContext()->getEntity()->getInstance();
        $crudAction = $event->getAdminContext()->getCrud()->getCurrentAction();

        if ($entity instanceof Trainer && $crudAction !== Action::EDIT) {
            $this->onBeforeTrainerCrudActionEdit($entity);
        }
    }

    private function onBeforeTrainerCrudActionEdit(Trainer $trainer): void
    {
        $this->session->set(self::OLD_TEAMS, new ArrayCollection($trainer->getTeams()->toArray()));
    }

    public function onBeforeEntityPersistedEvent(BeforeEntityPersistedEvent $event): void
    {
        if (!$event->getEntityInstance() instanceof Trainer) {
            return;
        }

        $this->persistTrainerTeamsRelations($event->getEntityInstance());
        $this->hashTrainerPassword($event->getEntityInstance());
    }

    private function persistTrainerTeamsRelations(Trainer $entityInstance): void
    {
        foreach ($entityInstance->getTeams() as $team) {
            $team->addTrainer($entityInstance);
        }
    }

    private function hashTrainerPassword(Trainer $trainer): void
    {
        $trainer->setPassword($this->passwordEncoder->hashPassword($trainer, $trainer->getPlainPassword()));
    }

    public function onBeforeEntityUpdatedEvent(BeforeEntityUpdatedEvent $event): void
    {
        if (!$event->getEntityInstance() instanceof Trainer) {
            return;
        }

        $this->updateTrainerTeamsRelations($event->getEntityInstance());
    }

    private function updateTrainerTeamsRelations(Trainer $entityInstance): void
    {
        $oldTeams = $this->session->get(self::OLD_TEAMS);
        $newTeams = $entityInstance->getTeams();

        /** @var Team $team */
        foreach ($oldTeams as $oldTeam) {
            if (!$newTeams->contains($oldTeam)) {
                $oldTeam->removeTrainer($entityInstance);
            }
        }

        /** @var Team $newTeam */
        foreach ($newTeams as $newTeam) {
            $newTeam->addTrainer($entityInstance);
        }

        $this->session->remove(self::OLD_TEAMS);
    }
}
