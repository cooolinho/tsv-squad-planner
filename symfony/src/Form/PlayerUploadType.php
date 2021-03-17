<?php

namespace App\Form;

use Cooolinho\FileImporterBundle\Reader\CsvReader;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlayerUploadType extends AbstractType
{
    public const FIELD_FILE = 'file';
    public const FIELD_FORMAT = 'format';

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(self::FIELD_FILE, FileType::class, [
                'label' => 'label.file',
                'required' => true,
            ])
            ->add(self::FIELD_FORMAT, ChoiceType::class, [
                'label' => 'label.format',
                'required' => true,
                'choices' => [
                    'csv_delimetered' => CsvReader::TYPE,
                ],
                'placeholder' => 'label.choose',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'label.import',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
