<?php

/*
 * Avito MessengerService
 * Методы API для работы с мессенджером
 *
 * Documentation
 * https://developers.avito.ru/api-catalog/messenger/documentation
 *
 */

declare(strict_types=1);

namespace Avito\RestApi\Service;

use Avito\RestApi\Http\ClientInterface;
use Exception;
use stdClass;

class MessengerService implements ServiceInterface
{
    /**
     * @var string
     */
    private string $base_path = 'messenger';

    /**
     * @var ClientInterface
     */
    private ClientInterface $http_client;

    /**
     * Avito MessengerService constructor
     *
     * @param string $clientId
     * @param string $secret
     * @param TokenStorageInterface $tokenStorage
     *
     * @throws Exception
     */
    public function __construct(ClientInterface $http_client)
    {
        $this->http_client = $http_client;
    }

    /**
     * @inheritDoc
     */
    public function getServiceBasePath(): string
    {
        return $this->base_path;
    }

    /**
     * ## Добавление пользователя в blacklist 
     * Добавляет пользователя в blacklist
     *
     * @param int $user_id - Номер пользователя в Личном кабинете Авито
     *
     * @return mixed
     */
    public function addToBlacklist(int $user_id)
    {
        $path = $this->getServiceBasePath() . '/v1/accounts/' . $user_id . '/blacklist';
        $requestResult = $this->http_client->sendRequest($path, 'POST');

        return $this->http_client->handleResult($requestResult);
    }

    /**
     * ## Включение уведомлений V2 (webhooks)
     *
     * @param string $url - Url на который будут отправляться нотификации
     *
     * @return mixed
     */
    public function v2Webhook(string $url)
    {
        $path = $this->getServiceBasePath() . '/v2/webhook';
        $data = [
            'url' => $url,
        ];
        $requestResult = $this->http_client->sendRequest($path, 'POST', $data);

        return $this->http_client->handleResult($requestResult);
    }
}
