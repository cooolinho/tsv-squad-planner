<?php

namespace App\Controller\Admin\Traits;

use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use Symfony\Contracts\Translation\TranslatorInterface;

trait CrudFieldsTimestampTrait
{
    abstract public function getTranslator(): TranslatorInterface;

    public function getTimestampFields(bool $panel = true): array
    {
        $fields = [
            $this->getTimestampPanel(),
            $this->getCreatedAtField(),
            $this->getUpdatedAtField(),
        ];

        if (!$panel) {
            unset($fields[0]);
        }

        return $fields;
    }

    public function getTimestampPanel(): FormField
    {
        return FormField::addPanel($this->getTranslator()->trans('entity.panel.timestamps'));
    }

    public function getCreatedAtField(): DateTimeField
    {
        return DateTimeField::new('createdAt', $this->getTranslator()->trans('entity.created_at.label'));
    }

    public function getUpdatedAtField(): DateTimeField
    {
        return DateTimeField::new('updatedAt', $this->getTranslator()->trans('entity.updated_at.label'));
    }
}
