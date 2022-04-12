<?php

if (php_sapi_name()!='cli') {
    require_once __DIR__ . "/helper.php";
    ob_start(fn ($buffer) =>prepareString($buffer));
}

$envPath = __DIR__ . '/../.env';

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

if (isset($argc) && $argc > 1) {
    $keywords = array_splice($argv, 1);
}

if (isset($_GET['keywords'])) {
    $keywordsArguments = $_GET['keywords'];
    $keywords = is_array($keywordsArguments) ? $keywordsArguments : [$keywords];
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
