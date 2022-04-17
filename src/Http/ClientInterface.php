<?php

/**
 * Interface ClientInterface
 */

declare(strict_types=1);

namespace Avito\RestApi\Http;

use stdClass;

interface ClientInterface
{
    /**
     * Get token and store it
     *
     * @return bool
     */
    public function getToken(): bool;

    /**
     * Form and send request to API service
     *
     * @param $path
     * @param string $method
     * @param array $data
     * @param bool $useToken
     *
     * @return stdClass
     */
    public function sendRequest($path, $method = 'GET', $data = [], bool $useToken = true): stdClass;

    /**
     * Process results
     *
     * @param $data
     *
     * @return stdClass
     */
    public function handleResult($data): stdClass;

    /**
     * Process errors
     *
     * @param null $customMessage
     *
     * @return stdClass
     */
    public function handleError($customMessage = null): stdClass;
}
