<?php

namespace App\Controller\Admin;

use App\Controller\Admin\Fields\AddressFields;
use App\Entity\Trainer;
use Cooolinho\Bundle\SecurityBundle\Form\Traits\RepeatedPasswordFormTypeTrait;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Contracts\Translation\TranslatorInterface;

class TrainerCrudController extends AbstractCrudController
{
    use RepeatedPasswordFormTypeTrait;

    protected TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public static function getEntityFqcn(): string
    {
        return Trainer::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield AssociationField::new('team', 'trainer.team.label');

        yield FormField::addPanel('Personal Information');
        yield TextField::new('firstname', 'player.firstname.label');
        yield TextField::new('lastname', 'player.lastname.label');
        AddressFields::addAll();

        yield FormField::addPanel('Contact Information');
        yield TextField::new('phone', 'player.phone.label');
        yield EmailField::new('email', 'trainer.email.label');

        yield FormField::addPanel('Password')->onlyOnForms();
        yield Field::new('plainPassword', 'trainer.password.label')
            ->setFormType(RepeatedType::class)
            ->setFormTypeOptions($this->getRepeatedPasswordTypeOptions($this->translator))
            ->onlyOnForms();
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add(Crud::PAGE_INDEX, Action::DETAIL);
    }
}
