<?php

namespace App\Controller\Admin;

use App\Entity\Player;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PlayerCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Player::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('firstname'),
            TextField::new('lastname'),
            DateField::new('birthday'),
            AssociationField::new('team')->onlyOnForms(),
            ChoiceField::new('foot', 'player.foot.label')
                ->autocomplete()
                ->setChoices(Player::$availableFoots),
        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('birthday')
            ->add('foot');
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setDefaultSort(['firstname' => 'ASC', 'lastname' => 'ASC', 'birthday' => 'ASC'])
            ->setPaginatorPageSize(30);
    }
}
