<?php

namespace Paygreen;

class PaygreenAPI
{
    private $ui;
    private $cp;
    private $host;

    /**
     * PaygreenAPI constructor.
     * @param string|mixed $ui
     * @param string|mixed $cp
     * @param string|mixed $host
     */
    public function __construct($ui = null, $cp = null, $host = null)
    {
        $this->ui = $ui == null ? $_ENV['UI'] : $ui;
        $this->cp = $cp == null ? $_ENV['CP'] : $cp;
        $this->host = $host == null ? $_ENV['HOST'] : $host;
    }

    /**
     * @param mixed|string|null $ui
     */
    public function setUi($ui): void
    {
        $this->ui = $ui;
    }

    /**
     * @return string|mixed
     */
    public function getUi()
    {
        return $this->ui;
    }

    /**
     * @param mixed|string|null $cp
     */
    public function setCp($cp): void
    {
        $this->cp = $cp;
    }

    /**
     * @return mixed
     */
    public function getCp()
    {
        return $this->cp;
    }

    /**
     * @return string|mixed
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param mixed $host
     */
    public function setHost($host = null)
    {
        $this->host = $host == null ? $host : 'https://paygreen.fr';
    }

    /**
     * @param mixed $var
     * @return bool
     */
    public function isContainsError($var)
    {
        return isset($var->error);
    }

    /**
     * @param string $action
     * @param array|null $allData
     * @return mixed|object
     */
    public function requestApi($action, $allData = null)
    {
        $allData_request = $this->getAction($action, $allData);
        $content = isset($allData['content']) ? json_encode($allData['content']) : '';

        if (extension_loaded('curl')) {
            $page = $this->request_api_curl($allData_request, $content);
        } elseif (ini_get('allow_url_fopen')) {
            $page = $this->request_api_fopen($allData_request, $content);
        } else {
            return ((object)array('error' => 0));
        }
        if ($page === false) {
            return ((object)array('error' => 1));
        }
        return json_decode($page);
    }

    /**
     * @param array|$allData_request
     * @param mixed $content
     * @return bool|string
     */
    private function request_api_curl($allData_request, $content)
    {
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_URL => $allData_request['url'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $allData_request['method'],
            CURLOPT_POSTFIELDS => $content,
            CURLOPT_HTTPHEADER => array(
                "accept: application/json",
                $allData_request['http'],
                "cache-control: no-cache",
                "content-type: application/json",
            ),
        ));
        $page = curl_exec($ch);
        curl_close($ch);
        return ($page);
    }

    /**
     * @param array $allData_request
     * @param mixed $content
     * @return bool|string
     */
    private function request_api_fopen($allData_request, $content)
    {
        $opts = array(
            'http' => array(
                'method' => $allData_request['method'],
                'header' => "Accept: application/json\r\n" .
                    "Content-Type: application/json\r\n" .
                    $allData_request['http'],
                'content' => $content
            )
        );
        $context = stream_context_create($opts);
        $page = @file_get_contents($allData_request['url'], false, $context);
        return ($page);
    }

    /**
     * @param string $action
     * @param array $allData
     * @return array
     */
    private function getAction($action, $allData)
    {
        $patchActions = ['validate-shop,validate-rounding'];
        $deleteActions = ['refund', 'refund-rounding'];
        $putActions = ['delivery'];
        $getActions = ['are-valid-ids', 'get-data', 'get-datas', 'get-rounding'];

        $http = $action == 'oAuth-access' ? '' : 'Authorization: Bearer ' . $this->cp;
        $method = 'POST';
        if (in_array($action, $patchActions)) {
            $method = 'PATCH';
        }
        if (in_array($action, $deleteActions)) {
            $method = 'DELETE';
        }
        if (in_array($action, $putActions)) {
            $method = 'PUT';
        }
        if (in_array($action, $getActions)) {
            $method = 'GET';
        }

        $url = $this->getHost() . '/api/' . $this->getUi();
        switch ($action) {
            case 'oAuth-access':
                $url = $this->getHost() . '/auth';
                break;
            case 'validate-shop':
                $url = $url . '/shop';
                break;
            case 'refund':
                $url = $url . '/payins/transaction/' . $allData['pid'];
                break;
            case 'are-valid-ids':
                $url = $url;
                break;
            case 'get-data':
                $url = $url . '/' . $allData['type'];
                break;
            case 'delivery':
                $url = $url . '/payins/transaction/' . $allData['pid'];
                break;
            case 'create-cash':
                $url = $url . '/payins/transaction/cash';
                break;
            case 'create-subscription':
                $url = $url . '/payins/transaction/tokenize';
                break;
            case 'create-tokenize':
                $url = $url . '/payins/transaction/tokenize';
                break;
            case 'create-xtime':
                $url = $url . '/payins/transaction/xTime';
                break;
            case 'get-datas':
                $url = $url . '/payins/transaction/' . $allData['pid'];
                break;
            case 'send-ccarbone':
                $url = $url . '/payins/ccarbone';
                break;
            default:
                $url = $url . '/solidarity/' . $allData['paymentToken'];
                break;
        }
        return [
            'method' => $method,
            'url' => $url,
            'http' => $http
        ];
    }
}