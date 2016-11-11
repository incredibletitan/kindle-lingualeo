<?php
namespace libs\components;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class LinguaLeo
{
    /**
     * @var $apiUrl - Url for LinguaLeo api
     */
    private $apiUrl;

    /**
     * @var $userEmail - Email for authentication
     */
    private $userEmail;

    /**
     * @var $userPassword - Password for authentication
     */
    private $userPassword;

    /**
     * @var Client - Guzzle client
     */
    private $client;

    /**
     * Lingualeo constructor.
     */
    public function __construct($apiUrl, $userEmail, $userPassword)
    {
        $this->setApiUrl($apiUrl);
        $this->setUserEmail($userEmail);
        $this->setUserPassword($userPassword);
        $this->initClient();
        $this->authorize();
    }

    /**
     * Initializing Guzzle client. If client not specified, use client with default params
     *
     * @param Client|null $client
     */
    public function initClient(Client $client = null)
    {
        $this->client = $client ? $client : new Client(['timeout' => 10.0, 'cookies' => true]);
    }

    /**
     * Authorization on lingualeo service
     */
    private function authorize()
    {
        $this->getResponse('http://lingualeo.com/api/login', [
            'email' => $this->userEmail,
            'password' => $this->userPassword
        ]);
    }

    /**
     * Get word translation
     *
     * @param $word
     */
    public function getTranslation($word)
    {
        return $this->getResponse('http://api.lingualeo.com/gettranslates', ['word' => $word]);
    }

    /**
     * @param $apiUrl
     */
    public function setApiUrl($apiUrl)
    {
        $this->apiUrl = $apiUrl;
    }

    /**
     * @param $userEmail
     */
    public function setUserEmail($userEmail)
    {
        $this->userEmail = $userEmail;
    }

    /**
     * @param $userPassword
     */
    public function setUserPassword($userPassword)
    {
        $this->userPassword = $userPassword;
    }

    /**
     * @param $url
     * @param array|null $params
     * @param bool $decodeJSON
     * @return mixed|string
     * @throws \Exception
     */
    private function getResponse($url, array $params = null, $decodeJSON = true)
    {
        $queryString = $params ? '?' . \GuzzleHttp\Psr7\build_query($params) : '';

        try {
            $response = $this->client->get($url . $queryString)->getBody()->getContents();
        } catch (RequestException $ex) {
            $exceptionMessage = 'Code: ' . $ex->getCode() . ';';

            if ($ex->hasResponse()) {
                $exceptionMessage .= 'Response:' . \GuzzleHttp\Psr7\str($ex->getResponse());
            }
            throw new \Exception($exceptionMessage);
        }
        return $decodeJSON ? \GuzzleHttp\json_decode($response) : $response;
    }
}