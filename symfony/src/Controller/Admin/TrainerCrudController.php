<?php

namespace App\Controller\Admin;

use App\Entity\Trainer;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;

class TrainerCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Trainer::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            EmailField::new('email'),
            AssociationField::new('team'),
        ];
    }
}
