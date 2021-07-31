<?php

namespace App\Controller\Admin;

use App\Entity\Club;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Contracts\Translation\TranslatorInterface;

class ClubCrudController extends AbstractCrudController
{
    protected TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public static function getEntityFqcn(): string
    {
        return Club::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', $this->translator->trans('club.name.label')),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setDefaultSort(['name' => 'ASC'])
            ->setPaginatorPageSize(30)
            ->setPageTitle(Crud::PAGE_INDEX, $this->translator->trans('page.club.title.index'))
            ->setPageTitle(Crud::PAGE_DETAIL, $this->translator->trans('page.club.title.detail'))
            ->setPageTitle(Crud::PAGE_EDIT, $this->translator->trans('page.club.title.edit'))
            ->setPageTitle(Crud::PAGE_NEW, $this->translator->trans('page.club.title.new'))
            ->setEntityLabelInSingular($this->translator->trans('team.label'))
            ->setEntityLabelInPlural($this->translator->trans('team.label'));
    }
}
