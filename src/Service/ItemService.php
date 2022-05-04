<?php

/*
 * Avito ItemService
 * Методы API для работы с объявлениями
 *
 * Documentation
 * https://developers.avito.ru/api-catalog/item/documentation
 *
 */

declare(strict_types=1);

namespace Avito\RestApi\Service;

use Avito\RestApi\Http\ClientInterface;
use Exception;
use stdClass;

class ItemService implements ServiceInterface
{
    /**
     * @var string
     */
    private string $base_path = '/core/v1/accounts';

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
     * ## Получение информации по объявлению
     * Возвращает данные об объявлении - его статус, список примененных услуг
     * 
     * Внимание: для получения статистики объявления должен использоваться метод: получение статистики по списку объявлений: https://developers.avito.ru/api-catalog/item/documentation#operation/itemStatsShallow
     * 
     * @param int $user_id - Номер пользователя в Личном кабинете Авито
     * @param int $item_id - Идентификатор объявления на сайте
     *
     * @return mixed
     */
    public function getItemInfo(
        int $user_id,
        int $item_id,
    ) {
        $path = $this->getServiceBasePath() . '/' . $user_id . '/items/' . $item_id . '/';

        $requestResult = $this->http_client->sendRequest($path, 'GET');

        return $this->http_client->handleResult($requestResult);
    }
}
