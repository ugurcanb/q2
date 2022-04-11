<?php

require_once './vendor/autoload.php';
require_once './helper.php';


class Main
{
    /**
     * @var array<string>
     */
    private array $keywords;

    private GuzzleHttp\Client $http;
    private mixed $apiKey;

    /**
     * @param array<string> $args
     */
    public function __construct(array $args)
    {
        try {
            $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
            $dotenv->load();
            $dotenv->required('SERPAPI_API_KEY')->notEmpty();

            $this->apiKey = $_ENV['SERPAPI_API_KEY'];
            $httpConfig=[
                'base_uri'=>'https://serpapi.com'
            ];
            $this->http = new GuzzleHttp\Client($httpConfig);

            $this->keywords = array_splice($args, 1);
            if (count($this->keywords) == 0) {
                $hint=php_sapi_name()=='cli'?
                    "\nUsage: \n\n\tphp index.php keyword1 keyword2  \n\n\tyou can suply as many as you like.\n":
                    "\nUsage: \n\n\t?keywords[]=keyword1&keywords[]=keyword2 \n\n\tyou can suply as many as you like.\n";
                echo "You should supply some brand names as arguments to fix them.\n${hint}\nSince you haven't, script will use the default list. Which is ...\n";
                $this->keywords = [
                    'Automattia',   // Automattic
                    'WordPresq',    // WordPress
                    /*'Jetpaci',      // Jetpack
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
                    'hugo bos',     // hugo boss*/
                ];

                echo "[\n\t'" . implode("',\n\t'", $this->keywords) . "'\n]\n";
            }
        } catch (\Dotenv\Exception\InvalidPathException) {
            echo 'Could not load .env file.';
            exit();
        } catch (Exception $e) {
            echo $e->getMessage();
            exit();
        }
    }

    public function run(): void
    {
        echo "Running...\n\n";
        try {
            foreach ($this->keywords as $brandName) {
                $result = $this->search($brandName);
                $correctedBrandName = $brandName;
                if (isset($result->search_information) && isset($result->search_information->spelling_fix)) {
                    $correctedBrandName = '* ' . $result->search_information->spelling_fix;
                }

                echo "$brandName => $correctedBrandName \n";
            }
            echo "\nFinished.";
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    private function search(string $keyword): object
    {
        if ('' === $keyword) {
            throw new Exception('Keywords parameter is empty');
        }

        $request = $this->http->get('/search.json', [
            'query'=>[
                'q'=>$keyword,
                'api_key'=>$this->apiKey
            ]
        ]);

        return json_decode($request->getBody()->getContents());
    }
}

$args = [];

if (php_sapi_name() == 'cli') {
    $args = $argv;
} else {
    if (isset($_GET['keywords'])) {
        $keywords = $_GET['keywords'];
        if (is_array($keywords)) {
            $args = ['', ...$keywords];
        } else {
            $args = ['',$keywords];
        }
    }
    ob_start(callback: fn ($buffer) =>prepareString($buffer));
}

$app = new Main($args);

$app->run();
