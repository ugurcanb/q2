<?php

if (php_sapi_name()!='cli') {
    echo 'You can only use the script on CLI';
    exit();
}

$envPath = __DIR__.'/.env';

if (!file_exists($envPath)) {
    echo '.env File not found -> '.$envPath;
    exit();
}
if (!is_readable($envPath)) {
    echo '.env File not readable -> '.$envPath;
    exit();
}
$apiKey='';

if (false !== $file = file($envPath)) {
    foreach ($file as $line) {
        if ($line&&str_starts_with($line, 'SERPAPI_API_KEY')) {
            $apiKey = str_replace("\"", "", explode('=', $line)[1]??"");
            break;
        }
    }
}

if (empty($apiKey)) {
    echo 'Could not find "SERPAPI_API_KEY" value in .env file';
    exit();
}

$keywords = [
    'Automattia',   // Automattic
    'WordPresq',    // WordPress
    'Jetpaci',      // Jetpack
    'WooCommercc',  // WooCommerce
    'fortinitee',   // fortnite
    'Acxer',        // Acxer it should be Acer the computer brand but Google is not fixing it.
    'Adidbas',      // Adidas
    'Nikxe',        // Nike
    'deloit',       // deloitte
    'ubiquti',      // ubiquiti
    'Band-adi',     // Band-aid
    'Hundai',       // hyundai
    'Mitsubisi',    // mitsubishi
    'Nesquick',     // Nesquik
    'hugo bos',     // hugo boss
];

if ($argc>1) {
    $keywords = array_splice($argv, 1);
}

echo "Running...\n\n";

foreach ($keywords as $keyword) {
    $response = file_get_contents(
        sprintf('https://serpapi.com/search.json?api_key=%s&q=%s', $apiKey, urlencode($keyword))
    );

    $responseJson = false !== $response ? json_decode($response):(object)[];
    $suggestion = $keyword;
    if (isset($responseJson->search_information)&&isset($responseJson->search_information->spelling_fix)) {
        $suggestion = $responseJson->search_information->spelling_fix;
        echo "$keyword => * $suggestion\n";
    } else {
        echo "Couldn't get a response from api.\n";
    }
}

echo "\nFinished";
