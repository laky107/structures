<?php
/**
 * Created by PhpStorm.
 * User: zuffik
 * Date: 4.9.2016
 * Time: 13:49
 */

namespace Zuffik\Structures\Formats;


use Zuffik\Data\Structures\HashMap;

class URL
{
    const PATTERN = '/^((https?):\/\/)?(([a-z0-9_-]+)\.)?(([a-z0-9_-]+)\.([a-z.0-9]+)|localhost)(\/[^?]*)?(\?.*)?$/';

    /** @var string */
    private $domain;
    /** @var string */
    private $protocol;
    /** @var string */
    private $subdomain;
    /** @var string */
    private $tld;
    /** @var string */
    private $requestedURL;
    /** @var HashMap */
    private $queryParams;
    /** @var string */
    private $fullUrl;

    /**
     * URL constructor.
     * @param string $url
     */
    public function __construct($url = '')
    {
        if($url != '') {
            $this->parseUrl($url);
        }
    }

    public function parseUrl($url)
    {
        preg_match(self::PATTERN, $url, $m);
        if(empty($m)) {
            $this->fullUrl = $url;
            $this->queryParams = new HashMap();
        } else {
            $this->protocol = $m[2] == '' ? 'http' : $m[2];
            $this->domain = $m[5] == 'localhost' ? $m[5] : $m[6];
            $this->subdomain = $m[4];
            $this->tld = $m[7];
            $this->requestedURL = isset($m[8]) && strpos($m[8], '/') === 0 && ltrim($m[8], '/') != '' ? $m[8] : '';
            parse_str(isset($m[9]) && strpos($m[9], '?') === 0 && ltrim($m[9], '?') != '' ? substr($m[9], 1) : '', $params);
            $this->queryParams = new HashMap($params);
        }
    }

    public function getAbsoluteUrl()
    {
        return empty($this->fullUrl) ? sprintf(
            '%s://%s%s%s%s%s%s',
            $this->protocol,
            $this->subdomain == '' ? '' : ($this->subdomain . '.'),
            $this->domain,
            $this->domain == 'localhost' ? '' : '.',
            $this->tld,
            $this->requestedURL,
            empty($this->queryParams) || empty($this->queryParams->toArray()) ? '' : ('?' . http_build_query($this->queryParams->toArray()))
        ) : $this->fullUrl;
    }

    /**
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @param string $domain
     * @return URL
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
        return $this;
    }

    /**
     * @return string
     */
    public function getProtocol()
    {
        return $this->protocol;
    }

    /**
     * @param string $protocol
     * @return URL
     */
    public function setProtocol($protocol)
    {
        $this->protocol = $protocol;
        return $this;
    }

    /**
     * @return string
     */
    public function getSubdomain()
    {
        return $this->subdomain;
    }

    /**
     * @param string $subdomain
     * @return URL
     */
    public function setSubdomain($subdomain)
    {
        $this->subdomain = $subdomain;
        return $this;
    }

    /**
     * @return string
     */
    public function getTld()
    {
        return $this->tld;
    }

    /**
     * @param string $tld
     * @return URL
     */
    public function setTld($tld)
    {
        $this->tld = $tld;
        return $this;
    }

    /**
     * @return string
     */
    public function getRequestedURL()
    {
        return $this->requestedURL;
    }

    /**
     * @param string $requestedURL
     * @return URL
     */
    public function setRequestedURL($requestedURL)
    {
        $this->requestedURL = $requestedURL;
        return $this;
    }

    /**
     * @return HashMap
     */
    public function getQueryParams()
    {
        return $this->queryParams;
    }

    /**
     * @param HashMap $queryParams
     * @return URL
     */
    public function setQueryParams($queryParams)
    {
        if(is_string($queryParams)) {
            parse_str($queryParams, $params);
        } else {
            $params = $queryParams;
        }
        $this->queryParams = new HashMap($params);
        return $this;
    }

    /**
     * @param string $name
     * @param string $value
     * @return URL
     */
    public function addQueryParam($name, $value)
    {
        $this->queryParams->put($name, $value);
        return $this;
    }


}