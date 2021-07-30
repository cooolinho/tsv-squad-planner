<?php

namespace App\Controller\Admin\Filter;

use App\Form\Type\Admin\ModifyYearsFilterType;
use App\Helper\YouthClassHelper;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Filter\FilterInterface;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\FieldDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\FilterDataDto;
use EasyCorp\Bundle\EasyAdminBundle\Filter\FilterTrait;

class ModifyYearsFilter implements FilterInterface
{
    use FilterTrait;

    public static function new(string $propertyName, $label = null): self
    {
        return (new self())
            ->setFilterFqcn(__CLASS__)
            ->setProperty($propertyName)
            ->setLabel($label)
            ->setFormType(ModifyYearsFilterType::class);
    }

    public function apply(QueryBuilder $queryBuilder, FilterDataDto $filterDataDto, ?FieldDto $fieldDto, EntityDto $entityDto): void
    {
        $modifyYearsSelected = $filterDataDto->getValue();
        $min = $queryBuilder->getParameter(YouthClassHelper::MIN_AGE);
        $max = $queryBuilder->getParameter(YouthClassHelper::MAX_AGE);

        if ($min && $max) {
            $modifyYears = "+$modifyYearsSelected years";

            /** @var \DateTime $minDate */
            $minDate = $min->getValue();
            $minDate->modify($modifyYears);

            /** @var \DateTime $maxDate */
            $maxDate = $max->getValue();
            $maxDate->modify($modifyYears);

            $queryBuilder->setParameter(YouthClassHelper::MIN_AGE, $minDate, 'datetime');
            $queryBuilder->setParameter(YouthClassHelper::MAX_AGE, $maxDate, 'datetime');
        }
    }
}
