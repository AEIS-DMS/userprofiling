<?php

namespace App\Traits;

use GuzzleHttp\Client;

trait ConsumeExternalService
{
    /**
     * Send request to any service
     * @param $method
     * @param $requestUrl
     * @param array $formParams
     * @param array $headers
     * @return string
     */
    public function performRequest($method, $requestUrl, $formParams = [], $headers = [])
    {
        $client = new Client([
            'base_uri'  =>  $this->baseUri,
        ]);

        $userData = json_decode(request()->header('user-data'), true);

        $response = $client->request($method, $requestUrl, [
            'form_params' => $formParams,
            'headers'     => [
                'user-data' => request()->header('user-data'),
                'user-id' => $userData ? $userData['id'] : 0
            ],
        ]);
        return $response->getBody()->getContents();
    }

    public function sendFileWithRequest($method, $requestUrl, $formParams = [], $headers = [])
    {
        $client = new Client([
            'base_uri' => $this->baseUri,
        ]);

        // Retrieve user-related data from the request headers
        $userData = json_decode(request()->header('user-data'), true);
        // Initialize the array for multipart data
        $multipartData = [];

        // Get the uploaded file
        $file = request()->file('file');

        // Prepare the multipart data
        foreach ($formParams as $key => $data) {
            $part = [
                'name' => $key,
                'contents' => $key === 'file' ? fopen($file->path(), 'r') : $data,
            ];

            if ($key === 'file') {
                $part['filename'] = $file->getClientOriginalName();
            }

            $multipartData[] = $part;
        }

        // Send the request
        $response = $client->request($method, $requestUrl, [
            'headers'     => [
                'user-id' => $userData ? $userData['id'] : 0,
                'user-data' => request()->header('user-data'),
            ],
            'multipart' => $multipartData,
        ]);

        return $response->getBody()->getContents();
    }
}