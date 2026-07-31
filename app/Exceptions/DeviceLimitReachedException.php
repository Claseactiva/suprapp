<?php

namespace App\Exceptions;

use Exception;

class DeviceLimitReachedException extends Exception
{
    public $limit;
    public $activeSessions;

    public function __construct($limit, $activeSessions)
    {
        $this->limit = $limit;
        $this->activeSessions = $activeSessions;

        parent::__construct("Alcanzaste el limite de {$limit} dispositivos permitidos.");
    }
}
