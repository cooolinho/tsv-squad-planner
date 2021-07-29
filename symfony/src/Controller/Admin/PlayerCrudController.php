<?php

namespace App\Controller\Admin;

use App\Controller\Admin\Fields\AddressFields;
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
        yield AssociationField::new('team', 'player.team.label');
        yield AssociationField::new('club', 'player.club.label');

        yield FormField::addPanel('Personal Information');
        yield TextField::new('firstname', 'player.firstname.label');
        yield TextField::new('lastname', 'player.lastname.label');
        AddressFields::addAll();

        yield FormField::addPanel('Contact Information');
        yield TextField::new('phone', 'player.phone.label');

        yield FormField::addPanel('Attributes');
        yield ChoiceField::new('foot', 'player.foot.label')
            ->autocomplete()
            ->setChoices(Player::$footChoices)
            ->hideOnIndex();

        yield FormField::addPanel('Clothing');
        yield ChoiceField::new('trainingsJacket', 'player.clothing.trainings_jacket')
            ->autocomplete()
            ->setChoices(Player::$clothingFitSizeChoices)
            ->hideOnIndex();
        yield ChoiceField::new('trainingsTrousers', 'player.clothing.trainings_trousers')
            ->autocomplete()
            ->setChoices(Player::$clothingFitSizeChoices)
            ->hideOnIndex();
        yield ChoiceField::new('warmUpShirt', 'player.clothing.warm_up_shirt')
            ->autocomplete()
            ->setChoices(Player::$clothingFitSizeChoices)
            ->hideOnIndex();
        yield ChoiceField::new('warmUpSweater', 'player.clothing.warm_up_sweater')
            ->autocomplete()
            ->setChoices(Player::$clothingFitSizeChoices)
            ->hideOnIndex();
        yield TextField::new('clothingDesiredSize', 'player.clothing.desired_size.label')
            ->setHelp('player.clothing.desired_size.help')
            ->hideOnIndex();
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(EntityFilter::new('team', 'player.team.label'))
            ->add(EntityFilter::new('club', 'player.club.label'))
            ->add(DateTimeFilter::new('birthday', 'player.birthday.label'))
            ->add(
                ChoiceFilter::new('foot', 'player.foot.label')
                    ->setChoices(Player::$footChoices)
            )
            ->add(
                ChoiceFilter::new('trainingsJacket', 'player.clothing.trainings_jacket')
                    ->setChoices(Player::$clothingFitSizeChoices)
            )
            ->add(
                ChoiceFilter::new('trainingsTrousers', 'player.clothing.trainings_trousers')
                    ->setChoices(Player::$clothingFitSizeChoices)
            )
            ->add(
                ChoiceFilter::new('warmUpShirt', 'player.clothing.warm_up_shirt')
                    ->setChoices(Player::$clothingFitSizeChoices)
            )
            ->add(
                ChoiceFilter::new('warmUpSweater', 'player.clothing.warm_up_sweater')
                    ->setChoices(Player::$clothingFitSizeChoices)
            );
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setDefaultSort(['firstname' => 'ASC', 'lastname' => 'ASC', 'birthday' => 'ASC'])
            ->setPaginatorPageSize(30);
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
                    $this->addFlash(LogLevel::ERROR, $this->translator->trans('error.file_import'));
                }

                if (is_int($events)) {
                    if ($events === 0) {
                        $this->addFlash(LogLevel::WARNING, $this->translator->trans('label.no_entries_found'));
                    } else {
                        $this->addFlash(LogLevel::WARNING, $this->translator->trans('notify.events_imported', [
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
}
