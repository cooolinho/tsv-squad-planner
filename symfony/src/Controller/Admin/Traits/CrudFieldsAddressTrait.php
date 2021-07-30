<?php

namespace App\Controller\Admin\Traits;

use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Contracts\Translation\TranslatorInterface;

trait CrudFieldsAddressTrait
{
    abstract public function getTranslator(): TranslatorInterface;

    public function getAddressFields(): array
    {
        return [
            $this->getStreet(),
            $this->getStreetNr(),
            $this->getZip(),
            $this->getCity(),
        ];
    }

    public function getStreet(): TextField
    {
        return TextField::new('street', $this->getTranslator()->trans('contact.street.label'));
    }

    public function getStreetNr(): TextField
    {
        return TextField::new('streetNr', $this->getTranslator()->trans('contact.street_nr.label'));
    }

    public function getZip(): TextField
    {
        return TextField::new('zip', $this->getTranslator()->trans('contact.zip.label'));
    }

    public function getCity(): TextField
    {
        return TextField::new('city', $this->getTranslator()->trans('contact.city.label'));
    }

    public function getPhone(): TextField
    {
        return TextField::new('phone', $this->getTranslator()->trans('contact.phone.label'));
    }
}
