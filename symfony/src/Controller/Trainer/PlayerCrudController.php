<?php

namespace App\Controller\Trainer;

use App\Controller\Admin\PlayerCrudController as AdminPlayerCrudController;
use App\Entity\Team;
use App\Exception\TeamNotFoundException;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;

class PlayerCrudController extends AdminPlayerCrudController
{
    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);

        return $this->filterPlayersByTrainer($searchDto, $entityDto, $fields, $filters);
    }

    private function filterPlayersByTrainer(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        if (!$this->getTeamByUser() instanceof Team) {
            throw new TeamNotFoundException();
        }

        $response = $this->get(EntityRepository::class)->createQueryBuilder($searchDto, $entityDto, $fields, $filters);
        $response->andWhere('entity.team = :team');
        $response->setParameter('team', $this->getTeamByUser());

        return $response;
    }

    private function getTeamByUser(): ?Team
    {
        return $this->getUser()->getTeam();
    }
}
