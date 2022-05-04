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
use Avito\RestApi\Service\ItemService;
use Avito\RestApi\Service\MessengerService;
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
    private $messenger_service;
    private $item_service;

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

    /**
     * @inheritDoc
     */
    public function getMessengerService(): MessengerService
    {
        if ($this->messenger_service instanceof MessengerService) {
            return $this->messenger_service;
        }

        $this->messenger_service = new MessengerService($this->http_client);

        return $this->messenger_service;
    }

    /**
     * @inheritDoc
     */
    public function getItemService(): ItemService
    {
        if ($this->item_service instanceof ItemService) {
            return $this->item_service;
        }

        $this->item_service = new ItemService($this->http_client);

        return $this->item_service;
    }
}
