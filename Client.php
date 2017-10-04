<?php

namespace AllegroApi;

class Client
{
    const WSDL_ADDRESS = 'http://webapi.allegro.pl/service.php?wsdl';
    const WEB_API_KEY = '2560abb4';

    const SANDBOX_WSDL_ADDRESS = 'https://webapi.allegro.pl.webapisandbox.pl/service.php?wsdl';
    const SANDBOX_WEB_API_KEY = '2560abb4';

    private $pass;

    public function __construct($productionConnection = false)
    {
        $wsdl = self::SANDBOX_WSDL_ADDRESS;
        $this->pass = self::SANDBOX_WEB_API_KEY;

        if ($productionConnection) {
            $wsdl = self::WSDL_ADDRESS;
            $this->pass = self::WEB_API_KEY;
        }

        $options = [
            'features' => SOAP_SINGLE_ELEMENT_ARRAYS,
            'exceptions' => true,
            'cache_wsdl' => WSDL_CACHE_NONE,
            'trace' => 1,
        ];

        $this->client = new \SoapClient($wsdl, $options);
    }

    private function getData($methodName)
    {
        $method = 'get' . ucfirst($methodName) . 'Data';
        return $this->$method();
    }

    public function getDoGetCountriesData()
    {
        return [
            'countryCode' => 1,
            'webapiKey' => $this->pass,
        ];
    }

    public function getDoGetCatsDataData()
    {
        return [
            'countryId' => 1,
            'localVersion' => 0,
            'webapiKey' => $this->pass,
        ];
    }

    public function getDoGetStatesInfoData()
    {
        return [
            'countryCode' => 1,
            'webapiKey' => $this->pass,
        ];
    }

    public function getDoQueryAllSysStatusData()
    {
        return [
            'countryId' => 1,
            'webapiKey' => $this->pass,
        ];
    }

    public function request($methodName = '')
    {
        if ($_SERVER['argc'] > 1) {
            $methodName = $_SERVER['argv'][1];
        }

        if (isset($_GET['method'])) {
            $methodName = $_GET['method'];
        }

        if (empty($methodName)) {
            throw new \Exception('Nie podales nazwy metody allegro web api!');
        }

        $data = $this->getData($methodName);
        return $this->client->$methodName($data);
    }

}

//$allegroApi = new Client(true);
$allegroApi = new Client();
try {
    $response = $allegroApi->request();
    echo '<pre>';
    print_r($response);
} catch (\Exception $e) {
    echo $e->getMessage() . "\n";
}

echo 'super poprawna zmiana, przeszla testy na qa i idzie do mastaera';