<?php

namespace App\Controller\Admin\Filter;

use App\Form\Type\Admin\YouthClassFilterType;
use App\Helper\YouthClassHelper;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Filter\FilterInterface;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\FieldDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\FilterDataDto;
use EasyCorp\Bundle\EasyAdminBundle\Filter\FilterTrait;

class YouthClassFilter implements FilterInterface
{
    use FilterTrait;

    const YOUTH_CLASS = 'youthClass';

    public static function new(string $propertyName, $label = null): self
    {
        return (new self())
            ->setFilterFqcn(__CLASS__)
            ->setProperty($propertyName)
            ->setLabel($label)
            ->setFormType(YouthClassFilterType::class);
    }


    public function apply(QueryBuilder $queryBuilder, FilterDataDto $filterDataDto, ?FieldDto $fieldDto, EntityDto $entityDto): void
    {
        $youthClass = $filterDataDto->getValue();

        $min = YouthClassHelper::getDateByYouthClass($youthClass);
        $max = YouthClassHelper::getDateByYouthClass($youthClass, '+0', false);

        $minKey = YouthClassHelper::MIN_AGE;
        $maxKey = YouthClassHelper::MAX_AGE;

        $queryBuilder->andWhere(sprintf("%s.%s BETWEEN :$minKey AND :$maxKey", $filterDataDto->getEntityAlias(), 'birthday'))
            ->setParameter($minKey, $min)
            ->setParameter($maxKey, $max);
    }
}
