<?php

namespace App\Controller\Admin;

use App\Controller\Admin\Traits\CrudFieldsAddressTrait;
use App\Controller\Admin\Traits\CrudFieldsTimestampTrait;
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
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class TrainerCrudController extends AbstractCrudController
{
    use RepeatedPasswordFormTypeTrait, CrudFieldsAddressTrait, CrudFieldsTimestampTrait;

    protected TranslatorInterface $translator;
    protected SessionInterface $session;
    protected UserPasswordHasherInterface $passwordEncoder;

    public function __construct(TranslatorInterface $translator, SessionInterface $session, UserPasswordHasherInterface $passwordEncoder)
    {
        $this->translator = $translator;
        $this->session = $session;
        $this->passwordEncoder = $passwordEncoder;
    }

    public static function getEntityFqcn(): string
    {
        return Trainer::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $trainerAssociationFields = [
            AssociationField::new('teams', $this->translator->trans('trainer.teams.label'))
                ->setRequired(true),
        ];

        $trainerPersonalInformation = array_merge([
            FormField::addPanel($this->translator->trans('trainer.panel.personal_information')),
            TextField::new('firstname', $this->translator->trans('contact.firstname.label')),
            TextField::new('lastname', $this->translator->trans('contact.lastname.label')),
        ], $this->getAddressFields());

        $trainerContactInformation = [
            FormField::addPanel($this->translator->trans('trainer.panel.contact_information')),
            TextField::new('phone', $this->translator->trans('contact.phone.label')),
            EmailField::new('email', $this->translator->trans('contact.email.label')),
        ];

        $trainerPassword = [
            FormField::addPanel($this->translator->trans('trainer.panel.password'))
                ->onlyOnForms()
                ->onlyWhenCreating(),
            Field::new('plainPassword', $this->translator->trans('security.user.plain_password'))
                ->setFormType(RepeatedType::class)
                ->setFormTypeOptions($this->getRepeatedPasswordTypeOptions($this->translator, true))
                ->onlyOnForms()
                ->onlyWhenCreating(),
        ];

        $timestampFields = [
            $this->getTimestampPanel()->hideOnIndex()->hideOnForm(),
            $this->getCreatedAtField()->hideOnIndex()->hideOnForm(),
            $this->getUpdatedAtField()->hideOnIndex()->hideOnForm(),
        ];

        return array_merge(
            $trainerAssociationFields,
            $trainerPersonalInformation,
            $trainerContactInformation,
            $trainerPassword,
            $timestampFields,
        );
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPaginatorPageSize(30)
            ->setPageTitle(Crud::PAGE_INDEX, $this->translator->trans('page.trainer.title.index'))
            ->setPageTitle(Crud::PAGE_DETAIL, $this->translator->trans('page.trainer.title.detail'))
            ->setPageTitle(Crud::PAGE_EDIT, $this->translator->trans('page.trainer.title.edit'))
            ->setPageTitle(Crud::PAGE_NEW, $this->translator->trans('page.trainer.title.new'))
            ->setEntityLabelInSingular($this->translator->trans('trainer.label.singular'))
            ->setEntityLabelInPlural($this->translator->trans('trainer.label.plural'));
    }

    public function getTranslator(): TranslatorInterface
    {
        return $this->translator;
    }
}
