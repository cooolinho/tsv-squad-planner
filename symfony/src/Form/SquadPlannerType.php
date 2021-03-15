<?php

namespace App\Form;

use App\Helper\YouthClassHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class SquadPlannerType extends AbstractType
{
    public const CHILD_YOUTH_CLASS = 'youth_class';
    public const CHILD_MODIFY_YEARS = 'modify_years';

    public const COUNT_FUTURE_YEARS = 10;

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(static::CHILD_YOUTH_CLASS, ChoiceType::class, [
                'choices' => YouthClassHelper::getClassesForChoiceType(),
                'required' => true,
            ])
            ->add(static::CHILD_MODIFY_YEARS, ChoiceType::class, [
                'choices' => self::getFutureYears(),
                'required' => true,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Find Players',
            ]);
    }

    private static function getFutureYears(): array
    {
        $years = [];
        $currentYear = date('Y');

        for ($y = 1; $y <= self::COUNT_FUTURE_YEARS; $y++) {
            $year = (int)$currentYear + $y;
            $years[$year] = '+' . $y;
        }

        return $years;
    }
}
