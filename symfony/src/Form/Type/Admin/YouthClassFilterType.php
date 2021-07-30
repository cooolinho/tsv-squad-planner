<?php

namespace App\Form\Type\Admin;

use App\Helper\YouthClassHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class YouthClassFilterType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'choices' => YouthClassHelper::getClassesForChoiceType(),
        ]);
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }
}
