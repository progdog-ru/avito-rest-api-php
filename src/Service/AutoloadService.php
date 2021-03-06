<?php

/*
 * Avito AutoloadService
 * Методы API для получения информации об автозагрузке объявлений
 *
 * Documentation
 * https://developers.avito.ru/api-catalog/autoload/documentation#tag/Autoload
 *
 */

declare(strict_types=1);

namespace Avito\RestApi\Service;

use Avito\RestApi\Http\ClientInterface;
use Exception;
use stdClass;

class AutoloadService implements ServiceInterface
{
    /**
     * @var string
     */
    private string $base_path = 'autoload/v1/accounts';

    /**
     * @var ClientInterface
     */
    private ClientInterface $http_client;

    /**
     * Avito AutoloadService constructor
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
     * ## Получение информации о выгрузке объявления 
     * Возвращает данные отчета о выгрузке объявления на сайт avito.ru
     *
     * @param int $user_id - Номер пользователя в Личном кабинете Авито
     * @param string $ad_id - Идентификатор объявления из XML-файла автозагрузки
     *
     * @return mixed
     */
    public function getItemInfo(int $user_id, string $ad_id)
    {
        $path = $this->getServiceBasePath() . '/' . $user_id . '/items/' . $ad_id . '/';
        $requestResult = $this->http_client->sendRequest($path, 'GET');

        return $this->http_client->handleResult($requestResult);
    }

    /**
     * ## Список отчетов об автозагрузке
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
