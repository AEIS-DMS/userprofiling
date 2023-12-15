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

    public function createUser($request)
    {
        return $this->performRequest('POST', 'create',  $request);
    }

    public function obtainUsers($request)
    {
        $queryString = http_build_query($request);
        return $this->performRequest('GET', 'list?' . $queryString);
    }

    public function updateUser($request, $id)
    {
        return $this->performRequest('PUT', "update/{$id}", $request);
    }

    public function obtainUser($request , $id)
    {
        //$queryString = http_build_query($request->all());
        return $this->performRequest('GET', "show/{$id}");
    }

    public function profileUpdate($request)
    {
        if (!empty($request['file'])) {
            return $this->sendFileWithRequest('POST', 'profile-update',  $request);
        } else {
            return $this->performRequest('POST', 'profile-update',  $request);
        }
    }
}
