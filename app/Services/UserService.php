<?php

namespace App\Services;

use App\Traits\ConsumeExternalService;

class UserService
{
    use ConsumeExternalService;

    /**
     * The base uri to consume projects service
     * @var string
     */
    public $baseUri;

    /**
     * Authorization secret to pass to project api
     * @var string
     */
    public $secret;

    public function __construct()
    {
        $this->baseUri = config('base-url.userrole_url');
        $this->secret = '';
    }
}
