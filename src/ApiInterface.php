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

interface ApiInterface
{
    /**
     * Доступ к сервису для работы с апи сервиса Автозагрузки
     * 
     * @see https://developers.avito.ru/api-catalog/autoload/documentation
     *
     * @param $campaignID
     * @return mixed
     */
    public function getAutoloadService(): AutoloadService;
}
