<?php
/**
 * Created by PhpStorm.
 * User: arun
 * Date: 05/09/18
 * Time: 12:09 AM
 */

namespace Arrowak\Remourse;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Remourse
{
    private static $data;

    protected static $urlScheme = 'http';

    protected static $baseURL = null;

    protected static $relativePath = null;

    protected static $endpoint = null;

    protected static $httpMethod = 'get';

    private const VALID_HTTP_METHODS = ['get', 'post', 'put', 'patch', 'delete'];

    private static $queryParams = [];

    /**
     * @return mixed
     */
    protected static function getData(): string
    {
        return self::$data;
    }

    /**
     * @param mixed $data
     */
    private static function setData($data): void
    {
        self::$data = $data;
    }

    /**
     * @return string
     */
    protected static function getUrlScheme(): string
    {
        return self::$urlScheme;
    }

    /**
     * @param string $urlScheme
     */
    protected static function setUrlScheme(string $urlScheme): void
    {
        self::$urlScheme = $urlScheme;
    }

    /**
     * @return string
     */
    protected static function getBaseURL(): string
    {
        $baseUrl = static::$baseURL;
        if (strpos(strtolower(static::$baseURL), "http") === false)
            $baseUrl = static::getUrlScheme() . "://" . static::$baseURL;
        return $baseUrl;
    }

    /**
     * @param null $baseURL
     */
    protected static function setBaseURL($baseURL): void
    {
        self::$baseURL = $baseURL;
    }

    /**
     * @return string
     */
    protected static function getRelativePath(): string
    {
        return self::$relativePath;
    }

    /**
     * @param null $relativePath
     */
    protected static function setRelativePath($relativePath): void
    {
        self::$relativePath = $relativePath;
    }

    /**
     * @return string
     */
    protected static function getEndpoint(): string
    {
        return self::$endpoint;
    }

    /**
     * @param null $endpoint
     */
    protected static function setEndpoint($endpoint): void
    {
        self::$endpoint = $endpoint;
    }

    /**
     * @return string
     */
    protected static function getHttpMethod(): string
    {
        if (in_array(strtolower(self::$httpMethod), self::VALID_HTTP_METHODS))
            return self::$httpMethod;
        else
            return 'get';
    }

    /**
     * @param string $httpMethod
     */
    protected static function setHttpMethod(string $httpMethod): void
    {
        self::$httpMethod = $httpMethod;
    }

    /**
     * @return array
     */
    public static function getQueryParams(): array
    {
        return self::$queryParams;
    }

    /**
     * @param null $queryParams
     */
    public static function setQueryParams($queryParams): void
    {
        self::$queryParams = $queryParams;
    }

    /**
     * @return Remourse
     * @throws GuzzleException
     */
    public static function all(): self
    {
        self::setData(self::getResponse(self::getUrl()));
        return new self();
    }

    /**
     * @param $conditions
     * @return Remourse
     * @throws GuzzleException
     */
    public static function where($conditions): self
    {
        if (!empty($conditions)) {
            self::$queryParams = $conditions;
            self::setData(self::getResponse(self::getUrl()));
        }
        return new self();
    }

    /**
     * @return string
     */
    private static function getUrl(): string
    {
        $baseUrl = self::getBaseURL();
        if (!empty(static::$relativePath)) {
            $baseUrl .= "/" . static::$relativePath;
        }
        return $baseUrl;
    }

    /**
     * @param $baseUrl
     * @return string
     * @throws GuzzleException
     */
    private static function getResponse($baseUrl): string
    {
        $response = null;
        $gClient = new Client(['base_uri' => $baseUrl]);
        if (!empty($gClient)) {
            $response = $gClient->request(strtoupper(self::getHttpMethod()), self::getEndpoint(), ['query' => self::getQueryParams()]);
        }
        return $response->getBody()->getContents();
    }

    /**
     * @return string
     */
    public
    static function getRawData(): string
    {
        return self::getData();
    }

    /**
     * @return mixed|string
     */
    public
    function __toString()
    {
        return self::getData();
    }
}