<?php

namespace App\Controller\Admin;

use App\Entity\Player;
use App\Form\PlayerUploadType;
use App\Importer\PlayerImporter;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PlayerCrudController extends AbstractCrudController
{
//    private UploadedFileService $uploadedFileService;
//    private CsvReader $csvReader;
//    private PlayerImporter $importer;
//    private TranslatorInterface $translator;
//
//    public function __construct(
//        UploadedFileService $uploadedFileService,
//        CsvReader $csvImporter,
//        PlayerImporter $importer,
//        TranslatorInterface $translator
//    )
//    {
//        $this->uploadedFileService = $uploadedFileService;
//        $this->csvReader = $csvImporter;
//        $this->importer = $importer;
//        $this->translator = $translator;
//    }

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

//    public function index(AdminContext $context): Response
//    {
//        return $this->render(
//            '@admin/pages/player/index.twig',
//            array_merge(parent::index($context)->all(), [])
//        );
//    }
//
//    public function import(Request $request, AdminContext $context): Response
//    {
//        $form = $this->createForm(PlayerUploadType::class);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//
//            /** @var UploadedFile $file */
//            $file = $form->get(PlayerUploadType::FIELD_FILE)->getData();
//            $format = $form->get(PlayerUploadType::FIELD_FORMAT)->getData();
//
//            if ($file = $this->uploadedFileService->upload($file, $this->getParameter('csv_file_upload_directory'))) {
//                $data = $this->csvReader->getIterableData($file);
//
//                $events = $this->importer->import($data, $format);
//
//                if ($events === null) {
//                    $this->addFlash(LogLevel::ERROR, $this->translator->trans('error.file_import'));
//                }
//
//                if (is_int($events)) {
//                    if ($events === 0) {
//                        $this->addFlash(LogLevel::WARNING, $this->translator->trans('label.no_entries_found'));
//                    } else {
//                        $this->addFlash(LogLevel::WARNING, $this->translator->trans('notify.events_imported', [
//                            '%count%' => $events,
//                        ]));
//                    }
//                }
//            }
//
//            return $this->redirect($this->get(CrudUrlGenerator::class)->build()
//                ->setAction(Action::INDEX)
//                ->generateUrl());
//        }
//
//        $players = $this->getDoctrine()->getManager()->getRepository(Player::class)->findAll();
//
//        return $this->render('@admin/pages/player/import.twig', [
//            'form' => $form->createView(),
//            'players' => $players,
//        ]);
//    }
}
