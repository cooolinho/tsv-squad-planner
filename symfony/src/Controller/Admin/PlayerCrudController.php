<?php

namespace App\Controller\Admin;

use App\Controller\Admin\Traits\CrudFieldsAddressTrait;
use App\Controller\Admin\Traits\CrudFieldsTimestampTrait;
use App\Entity\Player;
use App\Form\PlayerUploadType;
use App\Importer\PlayerImporter;
use Cooolinho\Bundle\FileImporterBundle\Reader\CsvReader;
use Cooolinho\Bundle\FileImporterBundle\Service\UploadedFileService;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use Psr\Log\LogLevel;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

class PlayerCrudController extends AbstractCrudController
{
    use CrudFieldsAddressTrait, CrudFieldsTimestampTrait;

    private UploadedFileService $uploadedFileService;
    private CsvReader $csvReader;
    private PlayerImporter $importer;
    private TranslatorInterface $translator;

    public function __construct(
        UploadedFileService $uploadedFileService,
        CsvReader           $csvImporter,
        PlayerImporter      $importer,
        TranslatorInterface $translator
    )
    {
        $this->uploadedFileService = $uploadedFileService;
        $this->csvReader = $csvImporter;
        $this->importer = $importer;
        $this->translator = $translator;
    }

    public static function getEntityFqcn(): string
    {
        return Player::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $playerAssociationFields = [
            AssociationField::new('team', $this->translator->trans('player.team.label')),
            AssociationField::new('club', $this->translator->trans('player.club.label')),
        ];

        $playerPersonalInformation = array_merge([
            FormField::addPanel($this->translator->trans('player.panel.personal_information')),
            TextField::new('firstname', $this->translator->trans('contact.firstname.label')),
            TextField::new('lastname', $this->translator->trans('contact.lastname.label')),
        ], $this->getAddressFields());

        $playerContactInformation = [
            FormField::addPanel($this->translator->trans('player.panel.contact_information')),
            TextField::new('phone', $this->translator->trans('contact.phone.label')),
        ];

        $playerAttributes = [
            FormField::addPanel($this->translator->trans('player.panel.attributes')),
            ChoiceField::new('foot', $this->translator->trans('player.foot.label'))
                ->autocomplete()
                ->setChoices(Player::$footChoices)
                ->hideOnIndex(),
        ];

        $playerClothings = [
            FormField::addPanel($this->translator->trans('player.panel.clothing')),
            ChoiceField::new('trainingsJacket', $this->translator->trans('player.clothing.trainings_jacket.label'))
                ->autocomplete()
                ->setChoices(Player::$clothingFitSizeChoices)
                ->hideOnIndex(),
            ChoiceField::new('trainingsTrousers', $this->translator->trans('player.clothing.trainings_trousers.label'))
                ->autocomplete()
                ->setChoices(Player::$clothingFitSizeChoices)
                ->hideOnIndex(),
            ChoiceField::new('warmUpShirt', $this->translator->trans('player.clothing.warm_up_shirt.label'))
                ->autocomplete()
                ->setChoices(Player::$clothingFitSizeChoices)
                ->hideOnIndex(),
            ChoiceField::new('warmUpSweater', $this->translator->trans('player.clothing.warm_up_sweater.label'))
                ->autocomplete()
                ->setChoices(Player::$clothingFitSizeChoices)
                ->hideOnIndex(),
            TextField::new('clothingDesiredSize', $this->translator->trans('player.clothing.desired_size.label'))
                ->setHelp($this->translator->trans('player.clothing.desired_size.help'))
                ->hideOnIndex(),
        ];

        $timestampFields = [
            $this->getTimestampPanel()->hideOnIndex()->hideOnForm(),
            $this->getCreatedAtField()->hideOnIndex()->hideOnForm(),
            $this->getUpdatedAtField()->hideOnIndex()->hideOnForm(),
        ];

        return array_merge(
            $playerAssociationFields,
            $playerPersonalInformation,
            $playerContactInformation,
            $playerAttributes,
            $playerClothings,
            $timestampFields,
        );
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(EntityFilter::new('team', $this->translator->trans('player.team.label')))
            ->add(EntityFilter::new('club', $this->translator->trans('player.club.label')))
            ->add(DateTimeFilter::new('birthday', $this->translator->trans('contact.birthday.label')))
            ->add(
                ChoiceFilter::new('foot', $this->translator->trans('player.foot.label'))
                    ->setChoices(Player::$footChoices)
            )
            ->add(
                ChoiceFilter::new('trainingsJacket', $this->translator->trans('player.clothing.trainings_jacket.label'))
                    ->setChoices(Player::$clothingFitSizeChoices)
            )
            ->add(
                ChoiceFilter::new('trainingsTrousers', $this->translator->trans('player.clothing.trainings_trousers.label'))
                    ->setChoices(Player::$clothingFitSizeChoices)
            )
            ->add(
                ChoiceFilter::new('warmUpShirt', $this->translator->trans('player.clothing.warm_up_shirt.label'))
                    ->setChoices(Player::$clothingFitSizeChoices)
            )
            ->add(
                ChoiceFilter::new('warmUpSweater', $this->translator->trans('player.clothing.warm_up_sweater.label'))
                    ->setChoices(Player::$clothingFitSizeChoices)
            );
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setDefaultSort(['firstname' => 'ASC', 'lastname' => 'ASC', 'birthday' => 'ASC'])
            ->setPaginatorPageSize(30)
            ->setPageTitle(Crud::PAGE_INDEX, $this->translator->trans('page.player.title.index'))
            ->setPageTitle(Crud::PAGE_DETAIL, $this->translator->trans('page.player.title.detail'))
            ->setPageTitle(Crud::PAGE_EDIT, $this->translator->trans('page.player.title.edit'))
            ->setPageTitle(Crud::PAGE_NEW, $this->translator->trans('page.player.title.new'))
            ->setEntityLabelInSingular($this->translator->trans('player.label.singular'))
            ->setEntityLabelInPlural($this->translator->trans('player.label.plural'));
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function index(AdminContext $context): Response
    {
        return $this->render(
            '@admin/pages/player/index.twig',
            array_merge(parent::index($context)->all(), [])
        );
    }

    public function import(Request $request, AdminContext $context): Response
    {
        $form = $this->createForm(PlayerUploadType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $file */
            $file = $form->get(PlayerUploadType::FIELD_FILE)->getData();
            $format = $form->get(PlayerUploadType::FIELD_FORMAT)->getData();

            if ($file = $this->uploadedFileService->upload($file, $this->getParameter('csv_file_upload_directory'))) {
                $data = $this->csvReader->getIterableData($file);

                $events = $this->importer->import($data, $format);

                if ($events === null) {
                    $this->addFlash(LogLevel::ERROR, $this->translator->trans('import.error.file_import_error'));
                }

                if (is_int($events)) {
                    if ($events === 0) {
                        $this->addFlash(LogLevel::WARNING, $this->translator->trans('import.notify.no_entries_found'));
                    } else {
                        $this->addFlash(LogLevel::WARNING, $this->translator->trans('import.notify.events_imported', [
                            '%count%' => $events,
                        ]));
                    }
                }
            }

            return $this->redirect($this->get(CrudUrlGenerator::class)->build()
                ->setAction(Action::INDEX)
                ->generateUrl());
        }

        $players = $this->getDoctrine()->getManager()->getRepository(Player::class)->findAll();

        return $this->render('@admin/pages/player/import.twig', [
            'form' => $form->createView(),
            'players' => $players,
        ]);
    }

    public function getTranslator(): TranslatorInterface
    {
        return $this->translator;
    }
}
