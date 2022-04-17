<?php

/*
 * Avito REST API Client
 *
 * Documentation
 * https://developers.avito.ru/api-catalog
 *
 */

declare(strict_types=1);

namespace Avito\RestApi;

use Avito\RestApi\Http\Client;
use Avito\RestApi\Http\ClientInterface;
use Avito\RestApi\Service\AutoloadService;
use Exception;
use Avito\RestApi\Storage\FileStorage;
use Avito\RestApi\Storage\TokenStorageInterface;
use stdClass;

class ApiClient implements ApiInterface
{
    /**
     * @var ClientInterface
     */
    private ClientInterface $http_client;

    private $autoload_service;

    /**
     * Avito API constructor
     *
     * @param string $clientId
     * @param string $secret
     * @param TokenStorageInterface $tokenStorage
     *
     * @throws Exception
     */
    public function __construct(string $clientId, string $secret, TokenStorageInterface $tokenStorage = null)
    {
        $this->http_client = new Client($clientId, $secret, $tokenStorage);
    }

    /**
     * @inheritDoc
     */
    public function getAutoloadService(): AutoloadService
    {
        if ($this->autoload_service instanceof AutoloadService) {
            return $this->autoload_service;
        }

        $this->autoload_service = new AutoloadService($this->http_client);

        return $this->autoload_service;
    }
}
