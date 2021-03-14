<?php

namespace App\Exception;

class TeamNotFoundException extends \Exception
{
    public $message = 'Team not found';
}
