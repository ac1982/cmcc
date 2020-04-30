<?php

namespace AC1982;

use GuzzleHttp\Client;


class CMCCClient
{
    protected $companyId = ''; //集团编号
    protected $appKey = '';    //移动物联网分发的APPKEY
    protected $secret = '';    //移动物联网分发的Secret
    protected $host = '';
    protected $version = '3.0';
    protected $format = 'json';//XML or JSON
    protected $key = '';

    /**
     * CMCCClient constructor.
     * You can get the config from CMCC.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->companyId=$config['companyId'];
        $this->appKey=$config['appKey'];
        $this->secret=$config['secret'];
        $this->host=$config['host'];
        $this->version=$config['version'];
        $this->format=$config['format'];
        $this->key = substr($this->secret, 0, 24);
    }

    /**
     * @param array $parameters
     * @return string
     */
    protected function signature(array $parameters): string
    {
        $string = '';
        ksort($parameters);
        foreach ($parameters as $k => $v) {
            $string .= $k . $v;
        }
        $string = $this->secret . $string . $this->secret;

        return strtoupper(sha1($string));
    }

    public function query(string $method, string $iccid)
    {
        $client = new Client();
        $params = [
            'appKey' => $this->appKey,
            'method' => $method,
            'iccid' => $iccid,
            'format' => $this->format,
            'v' => $this->version,
            'transID' => $this->companyId . date('YmdHsiv') . random_int(1000, 9999),
        ];
        $params += ['sign' => $this->signature($params)];

        $res = $client->post($this->host, ['form_params' => $params]);

        $message = $res->getBody()->getContents();

        return $this->decrypt($message);
    }

    protected function decrypt($message): string
    {
        return openssl_decrypt($message, "DES-EDE3", $this->key);
    }
}
