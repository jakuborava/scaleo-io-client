<?php

declare(strict_types=1);

namespace JakubOrava\ScaleoIoClient;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use JakubOrava\ScaleoIoClient\Exceptions\ApiErrorException;
use JakubOrava\ScaleoIoClient\Exceptions\AuthenticationException;
use JakubOrava\ScaleoIoClient\Exceptions\UnexpectedResponseException;
use JakubOrava\ScaleoIoClient\Exceptions\ValidationException;

class BaseScaleoClient
{
    private string $apiKey;

    private string $baseUrl;

    public function __construct()
    {
        $apiKey = config('scaleo-io-client.api_key', '');
        assert(is_string($apiKey));
        $this->apiKey = $apiKey;

        $baseUrl = config('scaleo-io-client.base_url', '');
        assert(is_string($baseUrl));
        $this->baseUrl = rtrim($baseUrl, '/');
    }

    /**
     * @param  array<string, mixed>  $queryParams
     * @return array{data: array<mixed>, headers: array<string, array<int, string>>}
     *
     * @throws AuthenticationException
     * @throws ValidationException
     * @throws ApiErrorException
     * @throws UnexpectedResponseException
     */
    public function get(string $path, array $queryParams = []): array
    {
        $url = $this->buildUrl($path);
        $response = $this->makeRequest('GET', $url, $queryParams);

        return $this->handleResponse($response);
    }

    /**
     * @param  array<string, mixed>  $data
     * @param  array<string, mixed>  $queryParams
     * @return array{data: array<mixed>, headers: array<string, array<int, string>>}
     *
     * @throws AuthenticationException
     * @throws ValidationException
     * @throws ApiErrorException
     * @throws UnexpectedResponseException
     */
    public function post(string $path, array $data = [], array $queryParams = []): array
    {
        $url = $this->buildUrl($path);
        $response = $this->makeRequest('POST', $url, $queryParams, $data);

        return $this->handleResponse($response);
    }

    /**
     * @param  array<string, mixed>  $data
     * @param  array<string, mixed>  $queryParams
     * @return array{data: array<mixed>, headers: array<string, array<int, string>>}
     *
     * @throws AuthenticationException
     * @throws ValidationException
     * @throws ApiErrorException
     * @throws UnexpectedResponseException
     */
    public function patch(string $path, array $data = [], array $queryParams = []): array
    {
        $url = $this->buildUrl($path);
        $response = $this->makeRequest('PATCH', $url, $queryParams, $data);

        return $this->handleResponse($response);
    }

    /**
     * @param  array<string, mixed>  $data
     * @param  array<string, mixed>  $queryParams
     * @return array{data: array<mixed>, headers: array<string, array<int, string>>}
     *
     * @throws AuthenticationException
     * @throws ValidationException
     * @throws ApiErrorException
     * @throws UnexpectedResponseException
     */
    public function put(string $path, array $data = [], array $queryParams = []): array
    {
        $url = $this->buildUrl($path);
        $response = $this->makeRequest('PUT', $url, $queryParams, $data);

        return $this->handleResponse($response);
    }

    /**
     * @param  array<string, mixed>  $queryParams
     * @return array{data: array<mixed>, headers: array<string, array<int, string>>}
     *
     * @throws AuthenticationException
     * @throws ValidationException
     * @throws ApiErrorException
     * @throws UnexpectedResponseException
     */
    public function delete(string $path, array $queryParams = []): array
    {
        $url = $this->buildUrl($path);
        $response = $this->makeRequest('DELETE', $url, $queryParams);

        return $this->handleResponse($response);
    }

    /**
     * Download a binary file (e.g., PDF)
     *
     * @param  array<string, mixed>  $queryParams
     *
     * @throws AuthenticationException
     * @throws ApiErrorException
     * @throws UnexpectedResponseException
     */
    public function download(string $path, array $queryParams = []): string
    {
        $url = $this->buildUrl($path);
        $queryParams['api-key'] = $this->apiKey;

        $client = $this->prepareClient();
        $response = $client->get($url, $queryParams);

        if ($response->status() === 401) {
            throw new AuthenticationException('Authentication failed. Please check your API key.');
        }

        if (! $response->successful()) {
            throw new ApiErrorException("Failed to download file. Status: {$response->status()}", $response->status());
        }

        return $response->body();
    }

    private function buildUrl(string $path): string
    {
        $path = ltrim($path, '/');

        return "$this->baseUrl/$path";
    }

    /**
     * @param  array<string, mixed>  $queryParams
     * @param  array<string, mixed>  $data
     */
    private function makeRequest(string $method, string $url, array $queryParams = [], array $data = []): Response
    {
        $client = $this->prepareClient();

        // Add API key to query parameters
        $queryParams['api-key'] = $this->apiKey;

        return match (strtoupper($method)) {
            'GET' => $client->get($url, $queryParams),
            'POST' => $client->post($url, array_merge($data, $queryParams)),
            'PATCH' => $client->patch($url, array_merge($data, $queryParams)),
            'PUT' => $client->put($url, array_merge($data, $queryParams)),
            'DELETE' => $client->delete($url, $queryParams),
            default => throw new UnexpectedResponseException("Unsupported HTTP method: $method"),
        };
    }

    private function prepareClient(): PendingRequest
    {
        return Http::acceptJson()
            ->contentType('application/json')
            ->timeout(30);
    }

    /**
     * @return array{data: array<mixed>, headers: array<string, array<int, string>>}
     *
     * @throws AuthenticationException
     * @throws ValidationException
     * @throws ApiErrorException
     * @throws UnexpectedResponseException
     */
    private function handleResponse(Response $response): array
    {
        // Handle authentication errors
        if ($response->status() === 401) {
            throw new AuthenticationException('Authentication failed. Please check your API key.');
        }

        // Handle validation errors
        if ($response->status() === 422) {
            $message = $this->extractErrorMessage($response);
            throw new ValidationException("Validation failed: $message");
        }

        // Handle forbidden
        if ($response->status() === 403) {
            $message = $this->extractErrorMessage($response);
            throw new ApiErrorException("Access forbidden: $message", 403);
        }

        // Handle not found
        if ($response->status() === 404) {
            throw new ApiErrorException('Resource not found', 404);
        }

        // Handle other client errors
        if ($response->clientError()) {
            $message = $this->extractErrorMessage($response);
            throw new ApiErrorException("Client error: $message", $response->status());
        }

        // Handle server errors
        if ($response->serverError()) {
            throw new ApiErrorException('Server error occurred', $response->status());
        }

        // Ensure successful response
        if (! $response->successful()) {
            throw new ApiErrorException("Request failed with status {$response->status()}", $response->status());
        }

        $data = $response->json();

        if (! is_array($data)) {
            throw new UnexpectedResponseException('Response is not an array');
        }

        // Unwrap the 'info' object from Scaleo's response structure
        // Scaleo wraps responses in {status, code, name, message, info}
        $unwrappedData = $data['info'] ?? $data;

        if (! is_array($unwrappedData)) {
            throw new UnexpectedResponseException('Response info is not an array');
        }

        return [
            'data' => $unwrappedData,
            'headers' => $response->headers(),
        ];
    }

    private function extractErrorMessage(Response $response): string
    {
        $data = $response->json();

        if (is_array($data) && isset($data['message']) && is_string($data['message'])) {
            return $data['message'];
        }

        if (is_array($data) && isset($data['error']) && is_string($data['error'])) {
            return $data['error'];
        }

        return $response->body();
    }
}
