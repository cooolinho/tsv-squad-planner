<?php

namespace App\Controller\Admin;

use App\Entity\Team;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Contracts\Translation\TranslatorInterface;

class TeamCrudController extends AbstractCrudController
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public static function getEntityFqcn(): string
    {
        return Team::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', $this->translator->trans('team.name.label')),
            TextField::new('identifier', $this->translator->trans('team.identifier.label')),
            AssociationField::new('players', $this->translator->trans('team.players.label')),
            AssociationField::new('trainer', $this->translator->trans('team.trainer.label')),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPaginatorPageSize(30)
            ->setPageTitle(Crud::PAGE_INDEX, $this->translator->trans('page.team.title.index'))
            ->setPageTitle(Crud::PAGE_DETAIL, $this->translator->trans('page.team.title.detail'))
            ->setPageTitle(Crud::PAGE_EDIT, $this->translator->trans('page.team.title.edit'))
            ->setPageTitle(Crud::PAGE_NEW, $this->translator->trans('page.team.title.new'))
            ->setEntityLabelInSingular($this->translator->trans('team.label.singular'))
            ->setEntityLabelInPlural($this->translator->trans('team.label.plural'));
    }
}
