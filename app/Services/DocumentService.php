<?php

namespace App\Services;

use App\Traits\ConsumeExternalService;

class DocumentService
{
    use ConsumeExternalService;

    /**
     * The base uri to consume projects service
     * @var string
     */
    public $baseUri;

    /**
     * Authorization secret to pass to document api
     * @var string
     */
    public $secret;

    public function __construct()
    {
        $this->baseUri = config('base-url.documents_url');
        $this->secret = '';
    }

    public function obtainDocumentsByTeamMember($payload)
    {
        $queryString = http_build_query($payload);
        return $this->performRequest('GET', 'documents-by-team-member?' . $queryString);
    }
}
