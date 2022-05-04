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
     * ## Получение информации по чатам
     * Возвращает список чатов
     * 
     * @param int $user_id - Идентификатор пользователя (клиента)
     * @param array $data - массив с query parameters.
     * 
     * ### Список query parameters:
     * 
     *  `item_ids` - string  
     *  Example: `item_ids=12345,6789`
     *  `unread_only` - boolean
     *  Example: `unread_only=true`
     *  `limit` - integer <int32>
     *  Default: `100`
     *  Example: `limit=50`
     *  Количество чатов на странице (положительное число больше 0 и меньше 100). 
     *  `offset` - integer <int32>
     *  Default: `0`
     *  Example: `offset=50`
     *
     * @return mixed
     */
    public function chats(
        int $user_id,
        array $data = [],
    ) {
        $path = $this->getServiceBasePath() . '/v1/accounts/' . $user_id . '/chats';

        $requestResult = $this->http_client->sendRequest($path, 'GET', $data);

        return $this->http_client->handleResult($requestResult);
    }

    /**
     * ## Получение информации по чату
     * Возвращает данные чата и последнее сообщение в нем
     * 
     * @param int $user_id - Идентификатор пользователя (клиента)
     * @param string $chat_id - Идентификатор чата (клиента)
     *
     * @return mixed
     */
    public function chat(
        int $user_id,
        string $chat_id,
    ) {
        $path = $this->getServiceBasePath() . '/v1/accounts/' . $user_id . '/chats/' . $chat_id;

        $requestResult = $this->http_client->sendRequest($path, 'GET');

        return $this->http_client->handleResult($requestResult);
    }

    /**
     * ## Получение списка сообщений
     * Получение списка сообщений, используйте только для изначальной загрузки сообщений на экране, 
     * для получения новых сообщений в реальном времени используйте webhooks.
     * 
     * @param int $user_id - Идентификатор пользователя (клиента)
     * @param string $chat_id - Идентификатор чата (клиента)
     * @param array $data - массив с query parameters.
     * 
     * ### Список query parameters:
     * 
     *  `limit` - integer <int32>
     *  Default: `100`
     *  Example: `limit=50`
     *  Количество сообщений на странице (положительное число больше 0 и меньше 100). 
     *  `offset` - integer <int32>
     *  Default: `0`
     *  Example: `offset=50`
     *
     * @return mixed
     */
    public function messages(
        int $user_id,
        string $chat_id,
        array $data = [],
    ) {
        $path = $this->getServiceBasePath() . '/v1/accounts/' . $user_id . '/chats/' . $chat_id . '/messages/';

        $requestResult = $this->http_client->sendRequest($path, 'GET', $data);

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
