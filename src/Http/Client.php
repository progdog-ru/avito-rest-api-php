<?php

/*
 * Avito HttpClient
 *
 * Documentation
 * https://developers.avito.ru/api-catalog
 *
 */

declare(strict_types=1);

namespace Avito\RestApi\Http;

use Exception;
use Avito\RestApi\Storage\FileStorage;
use Avito\RestApi\Storage\TokenStorageInterface;
use stdClass;

class Client implements ClientInterface
{

    private string $apiUrl = 'https://api.avito.ru';

    private string $clientId;
    private string $secret;
    private string $token;

    private int $refreshToken = 0;

    /**
     * @var null|TokenStorageInterface
     */
    private TokenStorageInterface $tokenStorage;


    /**
     * Avito Http Client constructor
     *
     * @param string $clientId
     * @param string $secret
     * @param TokenStorageInterface $tokenStorage
     *
     * @throws Exception
     */
    public function __construct(string $clientId, string $secret, TokenStorageInterface $tokenStorage = null)
    {
        if ($tokenStorage === null) {
            $tokenStorage = new FileStorage();
        }
        if (empty($clientId) || empty($secret)) {
            throw new Exception('Empty ID or SECRET');
        }

        $this->clientId = $clientId;
        $this->secret = $secret;
        $this->tokenStorage = $tokenStorage;
        $hashName = md5($clientId . '::' . $secret);

        /** load token from storage */
        $this->token = $this->tokenStorage->get($hashName);

        if (empty($this->token) && !$this->getToken()) {
            throw new Exception('Could not connect to api, check your ID and SECRET');
        }
    }

    /**
     * @inheritDoc
     */
    public function getToken(): bool
    {
        $data = [
            'grant_type' => 'client_credentials',
            'client_id' => $this->clientId,
            'client_secret' => $this->secret,
        ];

        $requestResult = $this->sendRequest('token', 'POST', $data, false);

        if ($requestResult->http_code !== 200) {
            return false;
        }

        $this->refreshToken = 0;
        $this->token = $requestResult->data->access_token;

        $hashName = md5($this->clientId . '::' . $this->secret);
        /** Save token to storage */
        $this->tokenStorage->set($hashName, $this->token);

        return true;
    }

    /**
     * @inheritDoc
     */
    public function sendRequest($path, $method = 'GET', $data = array(), bool $useToken = true): stdClass
    {
        $url = $this->apiUrl . '/' . $path;
        $method = strtoupper($method);
        $curl = curl_init();

        if ($useToken && !empty($this->token)) {
            $headers = array('Authorization: Bearer ' . $this->token, 'Expect:');
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }

        switch ($method) {
            case 'POST':
                curl_setopt($curl, CURLOPT_POST, count($data));
                curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
                break;
            case 'PUT':
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
                break;
            case 'DELETE':
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
                curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
                break;
            default:
                if (!empty($data)) {
                    $url .= '?' . http_build_query($data);
                }
        }

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 300);
        curl_setopt($curl, CURLOPT_TIMEOUT, 300);

        $response = curl_exec($curl);
        $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $headerCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $responseBody = substr($response, $header_size);
        $responseHeaders = substr($response, 0, $header_size);
        $ip = curl_getinfo($curl, CURLINFO_PRIMARY_IP);
        $curlErrors = curl_error($curl);

        curl_close($curl);

        if ($headerCode === 401 && $this->refreshToken === 0) {
            ++$this->refreshToken;
            $this->getToken();
            $retval = $this->sendRequest($path, $method, $data);
        } else {
            $retval = new stdClass();
            $retval->data = json_decode($responseBody);
            $retval->http_code = $headerCode;
            $retval->headers = $responseHeaders;
            $retval->ip = $ip;
            $retval->curlErrors = $curlErrors;
            $retval->method = $method . ':' . $url;
            $retval->timestamp = date('Y-m-d h:i:sP');
        }

        return $retval;
    }

    /**
     * @inheritDoc
     */
    public function handleResult($data): stdClass
    {
        if (empty($data->data)) {
            $data->data = new stdClass();
        }
        if ($data->http_code !== 200) {
            $data->data->is_error = true;
            $data->data->http_code = $data->http_code;
            $data->data->headers = $data->headers;
            $data->data->curlErrors = $data->curlErrors;
            $data->data->ip = $data->ip;
            $data->data->method = $data->method;
            $data->data->timestamp = $data->timestamp;
        }

        return $data->data;
    }

    /**
     * @inheritDoc
     */
    public function handleError($customMessage = null): stdClass
    {
        $message = new stdClass();
        $message->is_error = true;
        if (null !== $customMessage) {
            $message->message = $customMessage;
        }

        return $message;
    }
}
