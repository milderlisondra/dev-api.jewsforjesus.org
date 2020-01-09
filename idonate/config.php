<?php $base_url = "https://api.jewsforjesus.org/events/live/"; 

function setAccess()
{
    $allowed_origins = array(
        'https://jewsforjesus.ca',
        'https://devwp.jewsforjesus.org',
        'https://stagingwp.jewsforjesus.org',
        'https://www.jewsforjesus.org',
        'https://jewsforjesus.org',
        'https://jewsforjesus.org.uk',
        'https://www.jewsforjesus.org.uk',
        'https://juifspourjesus.org',
        'https://sandbox.jewsforjesus.org',
        'https://jewsforjesus.co.za',
        'https://chiton-rabbit-rf49.squarespace.com',
        'https://semicircle-lettuce-7sgd.squarespace.com',
        'https://www.jewsforjesus.org.au',
        'http://www.jewsforjesus.org.au',
        'https://jewsforjesus.org.au',
        'http://jewsforjesus.org.au',
        'http://m.jewsforjesus.org',
        'https://m.jewsforjesus.org'
    );
    $http_origin = $_SERVER['HTTP_ORIGIN'];
    
    if (in_array($http_origin,$allowed_origins)) {
        header("Access-Control-Allow-Origin: {$http_origin}");
    }
}

function getApiSettings()
{
    $settings = array(
        'database'  => 'Jews For Jesus',
        'app_name'  => 'PostiDonateData',
        'list_id'   => '2318875c-b0fc-4e23-8f38-2020b3dda761',
        'wsdl'      => 'https://bbecrig05bo3.blackbaudhosting.com/17112_c45ea4a9-6acc-417a-9b46-03139f3c9575/AppFxWebService.asmx?wsdl',
        'username'  => 'BLACKBAUDHOST\WebAPIUser17112',
        'password'  => '9OgcY81pyC^l',
        'timeout'   => '120'
    );
    
    return $settings;
}
