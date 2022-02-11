<?php

namespace App\Service\Hiboutik;

use App\Entity\Store;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Request
{
    private $client;
    private $maxItems;

    public function __construct($maxItems, HttpClientInterface $client)
    {
        $this->client = $client;
        $this->maxItems = $maxItems;
    }

    public function send(Store $store, string $type, string $request, array $body = [])
    {
        $page = 0;
        $response = [];
        $parameters['auth_basic'] = [$store->getUser(), $store->getApiKey()];
        if (!is_null($body) && count($body) > 0) {
            $parameters['json'] = $body;
        }
        do {
            $page++;
            $pageParameter = $type == 'GET' ? '?p=' . $page : '';
            $stream = $this->client->request($type, $request . $pageParameter, $parameters);
            $partialResponse = !in_array($type, ['PUT']) ? $stream->toArray() : [];
            $response = array_merge($response, $partialResponse);
        } while (count($partialResponse) >= $this->maxItems);

        return $response;
    }

    public function get(Store $store, string $request, $pagination = true)
    {
        $page = 0;
        $response = [];
        $parameters['auth_basic'] = [$store->getUser(), $store->getApiKey()];

        do {
            $page++;
            $pageParameter = $pagination ? '?p=' . $page : '';
            $stream = $this->client->request('GET', $request . $pageParameter, $parameters);
            $partialResponse = $stream->toArray();
            $response = array_merge($response, $partialResponse);
        } while (count($partialResponse) >= $this->maxItems);

        return $response;
    }
}