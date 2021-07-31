<?php

namespace App\Controller\Trainer;

use App\Controller\Admin\PlayerCrudController as AdminPlayerCrudController;
use App\Entity\Player;
use App\Entity\Trainer;
use App\Exception\TeamsNotFoundException;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class PlayerCrudController extends AdminPlayerCrudController
{
    /**
     * @throws TeamsNotFoundException
     */
    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $queryBuilder = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);

        return $this->filterPlayersByTrainer($queryBuilder);
    }

    /**
     * @throws TeamsNotFoundException
     */
    private function filterPlayersByTrainer(QueryBuilder $queryBuilder): QueryBuilder
    {
        if (empty($this->getTeamsByUser())) {
            throw new TeamsNotFoundException();
        }

        $queryBuilder->andWhere('entity.team IN (:ids)');
        $queryBuilder->setParameter('ids', array_column($this->getTeamsByUser()->toArray(), 'id'));

        return $queryBuilder;
    }

    private function getTeamsByUser(): Collection
    {
        /** @var Trainer $trainer */
        $trainer = $this->getUser();
        return $trainer->getTeams();
    }

    public function createNewFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface
    {
        $formBuilder = parent::createNewFormBuilder($entityDto, $formOptions, $context);
        $formBuilder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            /** @var Player $player */
            $form = $event->getForm();

            /** @var QueryBuilder $qb */
            $qb = $form->get('team')->getConfig()->getOption('query_builder');
            $qb->leftJoin('entity.trainer', 'et');
            $qb->andWhere('et.id = :id');
            $qb->setParameter('id', $this->getUser()->getId());
        });

        return $formBuilder;
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions = parent::configureActions($actions);

        return $actions
            ->setPermission(Action::EDIT, Trainer::ROLE_TRAINER)
            ->setPermission(Action::DELETE, Trainer::ROLE_TRAINER);
    }
}
