<?php

/**
 * Interface TokenStorageInterface
 */

declare(strict_types=1);

namespace Avito\RestApi\Service;

interface ServiceInterface
{
    /**
     * Возвращает базовый путь для запросов к этому сервису
     * 
     * @return string
     */
    public function getServiceBasePath(): string;
}
