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

    public function getUserListBasedOnGroup($id, $page)
    {
        $pageNumber = ['page' => $page];
        $queryString = http_build_query($pageNumber);
        return $this->performRequest('GET', "user-list/{$id}?" . $queryString);
    }
}
