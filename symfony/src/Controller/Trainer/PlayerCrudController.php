<?php

namespace App\Controller\Trainer;

use App\Controller\Admin\PlayerCrudController as AdminPlayerCrudController;
use App\Entity\Player;
use App\Entity\Team;
use App\Entity\Trainer;
use App\Exception\TeamNotFoundException;
use App\Helper\YouthClassHelper;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class PlayerCrudController extends AdminPlayerCrudController
{
    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $queryBuilder = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);

        return $this->filterPlayersByTrainer($queryBuilder);
    }

    private function filterPlayersByTrainer(QueryBuilder $queryBuilder): QueryBuilder
    {
        if (!$this->getTeamByUser() instanceof Team) {
            throw new TeamNotFoundException();
        }

        $queryBuilder->andWhere('entity.team = :team');
        $queryBuilder->setParameter('team', $this->getTeamByUser());

        return $queryBuilder;
    }

    private function getTeamByUser(): ?Team
    {
        return $this->getUser()->getTeam();
    }

    public function createNewFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface
    {
        $formBuilder = parent::createNewFormBuilder($entityDto, $formOptions, $context);

        $team = $this->getTeamByUser();
        $youthClass = strtoupper($team->getIdentifier());

        $minAgeDate = YouthClassHelper::getMinAgeByYouthClass($youthClass);
        $maxAgeDate = YouthClassHelper::getMaxAgeByYouthClass($youthClass);

        $formBuilder->add('birthday', DateType::Class, [
            'widget' => 'choice',
            'years' => range(
                $minAgeDate->format('Y'),
                $maxAgeDate->format('Y'),
            ),
            'months' => range(1, 12),
            'days' => range(1, 31),
        ]);

        $formBuilder->get('team')->setDisabled(true);
        $formBuilder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            /** @var Player $player */
            $player = $event->getData();
            $player->setTeam($this->getTeamByUser());
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
