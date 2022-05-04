<?php

/*
 * SendPulse REST API Interface
 *
 * Documentation
 * https://developers.avito.ru/api-catalog
 *
 */

declare(strict_types=1);

namespace Avito\RestApi;

use Avito\RestApi\Service\AutoloadService;
use Avito\RestApi\Service\MessengerService;

interface ApiInterface
{
    /**
     * Доступ к сервису для работы с апи сервиса Автозагрузки
     * 
     * @see https://developers.avito.ru/api-catalog/autoload/documentation
     *
     * @return \Avito\RestApi\Service\AutoloadService
     */
    public function getAutoloadService(): AutoloadService;

    /**
     * Доступ к сервису для работы с апи сервиса Мессенджер
     * 
     * @see https://developers.avito.ru/api-catalog/messenger/documentation
     *
     * @return \Avito\RestApi\Service\MessengerService
     */
    public function getMessengerService(): MessengerService;
}
