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
     * Authorization on lingualeo service
     */
    public function authorize()
    {
        try {
            $request = $this->client->request('GET', 'http://lingualeo.com/api/login?' . \GuzzleHttp\Psr7\build_query(
                    [
                        'email' => $this->userEmail,
                        'password' => $this->userPassword
                    ]
                )
            );
        } catch (RequestException $ex) {
            echo $ex->getCode();
            if ($ex->hasResponse()) {
                echo \GuzzleHttp\Psr7\str($ex->getResponse());
            }
        }
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
     * Get word translation
     *
     * @param $word
     */
    public function getTranslation($word)
    {
        $res = $this->client->request('GET', 'http://api.lingualeo.com/gettranslates?' . \GuzzleHttp\Psr7\build_query(['word' => $word]))->getBody()->getContents();
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
}