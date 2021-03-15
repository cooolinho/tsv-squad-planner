<?php

namespace App\Controller\Trainer;

use App\Controller\Admin\PlayerCrudController as AdminPlayerCrudController;
use App\Entity\Player;
use App\Entity\Team;
use App\Exception\TeamNotFoundException;
use App\Helper\YouthClassHelper;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class PlayerCrudController extends AdminPlayerCrudController
{
    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);

        return $this->filterPlayersByTrainer($searchDto, $entityDto, $fields, $filters);
    }

    public function createNewFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface
    {
        $formBuilder = parent::createNewFormBuilder($entityDto, $formOptions, $context);

        $team = $this->getTeamByUser();
        $minAgeDate = YouthClassHelper::getMinAgeByYouthClass($team->getYouthClass());
        $maxAgeDate = YouthClassHelper::getMaxAgeByYouthClass($team->getYouthClass());

        $formBuilder->add('birthday', DateType::Class, [
            'widget' => 'choice',
            'years' => range(
                $minAgeDate->format('Y'),
                $maxAgeDate->format('Y'),
            ),
            'months' => range(date('m'), 12),
            'days' => range(date('d'), 31),
        ]);

        $formBuilder->get('team')->setDisabled(true);
        $formBuilder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            /** @var Player $player */
            $player = $event->getData();
            $player->setTeam($this->getTeamByUser());
        });

        return $formBuilder;
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
