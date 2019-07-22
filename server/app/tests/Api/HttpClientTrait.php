<?php

namespace App\Tests\Api;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\TransferStats;

trait HttpClientTrait
{
    /**
     * @var Client
     */
    private static $httpClient;

    protected function post(
        string $url,
        array $body,
        int $code = 201
    ): array {
        return $this->httpClientMakeRequest('post', $url, $code, [], $body);
    }

    protected function put(
        string $url,
        array $body = [],
        int $code = 200
    ): array {
        return $this->httpClientMakeRequest('put', $url, $code, [], $body);
    }

    protected function delete(
        string $url,
        array $body = [],
        int $code = 200
    ): array {
        return $this->httpClientMakeRequest('delete', $url, $code, [], $body);
    }

    protected function get(
        string $url,
        int $code = 200,
        array $urlParams = []
    ) {
        return $this->httpClientMakeRequest('get', $url, $code, $urlParams);
    }

    protected function paginatedGet(
        string $url,
        array $urlParams = []
    ) {
        $res = $this->httpClientMakeRequest(
            'get',
            $url,
            200,
            array_merge(
                $urlParams,
                ['limit' => 0, 'offset' => 0,]
            )
        );

        $this->assertArray(
            $res,
            ResponseBodies::PAGINATED_RESPONSE
        );

        return $res;
    }

    protected function httpClientMakeRequest(
        string $type,
        string $url,
        int $code,
        array $urlParams = [],
        array $body = []
    ): array {
        $response = $this
            ->getHttpClient()
            ->request(
                $type,
                "/api/$url" . $this->urlParams($urlParams),
                [
                    'headers' => $this->loggedUserToken
                        ? [
                            'Authorization' => 'Bearer ' . $this->loggedUserToken,
                        ]
                        : [],
                    RequestOptions::JSON => $body,
                    RequestOptions::HTTP_ERRORS => false,
                    'on_stats' => function (TransferStats $stats) use ($type) {
                        $this->logRequest(
                            strtoupper($type),
                            $stats->getResponse()->getStatusCode(),
                            $stats->getEffectiveUri(),
                            $stats->getHandlerStats()['starttransfer_time']
                        );
                    }
                ]
            );

        $this->assertSame(
            $code,
            $response->getStatusCode(),
            'Wrong response code!'
        );

        $contents = $response->getBody()->getContents();

        if ($code === 204) {
            $this->assertSame(
                '',
                $contents,
                'Response body should be empty!'
            );
        }

        return (array) json_decode($contents, true);
    }

    private function urlParams(array $params = []): string
    {
        return '?' . http_build_query(array_merge(
                $params,
                [
                    'appLanguage' => 'ru',
                    'appTimezone' => '0',
                ]
            ));
    }

    private function logRequest(string $method, int $code, string $url, float $time)
    {
        file_put_contents(
            '/app/var/log/api_tests.log',
            "$time - $method ($code) $url\r\n",
            FILE_APPEND
        );
    }

    private function getHttpClient(): Client
    {
        if (!self::$httpClient) {
            self::$httpClient = new Client([
                'base_uri' => 'http://front',
            ]);
        }

        return self::$httpClient;
    }
}