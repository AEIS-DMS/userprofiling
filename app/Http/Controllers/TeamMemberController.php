<?php

namespace App\Http\Controllers;

use App\Services\DocumentService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TeamMemberController extends Controller
{
    /**
     * The service to consume the documents micro-service
     * @var DocumentService
     */
    public $documentService;

    /**
     * The service to consume the documents micro-service
     * @var UserService
     */
    public $userService;

    public function __construct(DocumentService $documentService, UserService $userService)
    {
        $this->documentService = $documentService;
        $this->userService = $userService;
    }


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $params     = $request->all();
            return $this->userService->obtainUsers($params);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $params = $request->all();
            return $this->userService->createUser($params);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        try {
            $params = $request->all();
            return $this->userService->obtainUser($params, $id);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $params = $request->all();
            return $this->userService->updateUser($params, $id);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Get the list of documents based on requested team member
     */
    public function getDocumentsByTeamMember(Request $request)
    {
        try {
            $params     = $request->all();
            $getDocuments = $this->documentService->obtainDocumentsByTeamMember($params);
            $documents    = json_decode($getDocuments, true);
            return $this->successResponse($documents);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
