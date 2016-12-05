<?php
namespace libs\components;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class LinguaLeo
{
    /**
     * @var $apiUrl - Url for LinguaLeo api
     */
    private $apiUrl = 'http://api.lingualeo.com/';

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
    public function __construct($userEmail, $userPassword)
    {
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
     * Get list of word translations
     *
     * @param $word - Word needed to be translate
     * @throws LinguaLeoApiException
     * @throws \InvalidArgumentException - Valid response from /gettranslates method of LinguaLeo API must
     * contain 'translate' key and if is not specified InvalidArgumentException will be thrown
     * @return array - Array with translation variants
     */
    public function getTranslations($word)
    {
        $translationResult = $this->getResponse($this->apiUrl . '/gettranslates', ['word' => $word]);

        if (!isset($translationResult['translate']) || !is_array($translationResult['translate'])) {
            throw new \InvalidArgumentException('Invalid translation object:' . var_export($translationResult, true));
        }

        if (isset($translationResult['error_msg']) && $translationResult['error_msg']) {
            throw new LinguaLeoApiException('Can\'t translate: ' . $translationResult['error_msg']);
        }

        return $translationResult['translate'];
    }

    /**
     * Get top rated word translation
     *
     * @param $word - Word needed to be translate
     * @throws LinguaLeoApiException
     * @throws \InvalidArgumentException
     * @return null|string - Translation
     */
    public function getTopRatedTranslation($word)
    {
        if (($translations = $this->getTranslations($word))
            && isset($translations[0])
            && isset($translations[0]['value'])
        ) {
            return $translations[0]['value'];
        }
        return null;
    }

    /**
     * @param $word - Word needed to be translate
     * @param $translation - Translation
     * @param $context - Word context. E.g Part of sentence
     * @throws LinguaLeoApiException
     * @return array - Result
     */
    public function addWord($word, $translation, $context)
    {
        $result = $this->getResponse(
            $this->apiUrl . '/addword',
            ['word' => $word, 'tword' => $translation, 'context' => $context]
        );

        if (!isset($result['word_id'])) {
            throw new \InvalidArgumentException('Invalid translation object:' . var_export($result, true));
        }

        if (isset($result['error_msg']) && $result['error_msg']) {
            throw new LinguaLeoApiException('Can\'t translate: ' . $result['error_msg']);
        }

        return $result;
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
     * @param $apiUrl
     */
    public function setApiUrl($apiUrl)
    {
        $this->apiUrl = $apiUrl;
    }

    /**
     * @param $url
     * @param array|null $params
     * @param bool $decodeJSON
     * @return mixed|string
     * @throws LinguaLeoApiException
     */
    protected function getResponse($url, array $params = null, $decodeJSON = true)
    {
        $queryString = $params ? '?' . \GuzzleHttp\Psr7\build_query($params) : '';

        try {
            $response = $this->getContentByUrl($url . $queryString);
        } catch (RequestException $ex) {
            throw new LinguaLeoApiException("Server Error", null, $ex);
        }

        if ($decodeJSON) {
            try {
                $response = \GuzzleHttp\json_decode($response, true);
            } catch (\InvalidArgumentException $ex) {
                throw new LinguaLeoApiException(
                    "Invalid response format. Response:" . var_export($response, true),
                    null,
                    $ex
                );
            }
        }
        return $response;
    }

    /**
     * Get contents by specified url using 'GET' HTTP method
     *
     * @param $url - Target url
     * @throws RequestException
     *
     * @return string - Content got by url
     */
    public function getContentByUrl($url)
    {
        return $this->client->get($url)->getBody()->getContents();
    }
}