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
    private string $base_path = 'messenger/v1/accounts';

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
     * ##Добавление пользователя в blacklist 
     * Добавляет пользователя в blacklist
     *
     * @param int $user_id - Номер пользователя в Личном кабинете Авито
     * @param 
     *
     * @return mixed
     */
    public function addToBlacklist(int $user_id, string $ad_id)
    {
        $path = $this->getServiceBasePath() . '/' . $user_id . '/blacklist';
        $requestResult = $this->http_client->sendRequest($path, 'POST');

        return $this->http_client->handleResult($requestResult);
    }

    /**
     * ##Список отчетов об автозагрузке
     * Отчеты отсортированы в порядке убывания даты загрузки, т.е. самый свежий отчет будет в самом начале списка
     *
     * @param int $user_id - Номер пользователя в Личном кабинете Авито
     * @param int $per_page - Количество ресурсов на страницу
     * @param int $page - Номер страницы
     *
     * @return mixed
     */
    public function getReports(int $user_id, int $per_page = null, int $page = null)
    {
        $path = $this->getServiceBasePath() . '/' . $user_id . '/reports/';
        $data = [];

        if ($per_page !== null) {
            $data['per_page'] = $per_page;
        }

        if ($page !== null) {
            $data['page'] = $page;
        }

        $requestResult = $this->http_client->sendRequest($path, 'GET', $data);

        return $this->http_client->handleResult($requestResult);
    }
}