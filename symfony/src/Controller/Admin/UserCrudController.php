<?php

namespace App\Controller\Admin;

use Cooolinho\Bundle\SecurityBundle\CooolinhoSecurityBundle;
use Cooolinho\Bundle\SecurityBundle\Entity\User;
use Cooolinho\Bundle\SecurityBundle\Form\Traits\RepeatedPasswordFormTypeTrait;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserCrudController extends AbstractCrudController
{
    use RepeatedPasswordFormTypeTrait;

    protected TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            EmailField::new('email'),
            Field::new('plainPassword')
                ->setFormType(RepeatedType::class)
                ->setFormTypeOptions($this->getRepeatedPasswordTypeOptions($this->translator, true))
                ->onlyOnForms(),
            ChoiceField::new('roles')
                ->setChoices($this->getUserRolesAsChoices())
                ->allowMultipleChoices(),
        ];
    }

    private function getUserRolesAsChoices(): array
    {
        $roles = [];
        foreach (User::CHOICE_ROLE as $label => $value) {
            $translatedLabel = $this->translator->trans($label, [], CooolinhoSecurityBundle::TRANSLATION_DOMAIN);
            $roles[$translatedLabel] = $value;
        }

        return $roles;
    }
}
