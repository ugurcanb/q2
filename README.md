# Q2

This script takes popular incorrectly spelled brand names and outputs their correct spelling (most of the time 😅).
## How does it work?
Script uses SerpAPI's Google Search API to get "did you mean" suggestions for keywords supplied. 
## How to use?

 1. Get an Api Key from SerpAPI, it is free for 100 searches in a month on Free plan - https://serpapi.com/ .
 2. Copy `.env.example` to same folder and rename it to `.env`.
 3. Put your api key inside of the 'SERPAPI_API_KEY' key on `.env` file.
 4. Run `composer install`

You can use the script in both CLI and in a PHP Web Server for example PHP Built-in Web Server.
 - CLI
	 - `php ./public/index.php`
	 - `php ./public/index.php keyword`
	 - `php ./public/index.php keyword1 keyword2 ...`
	 - `php ./public/LeastAmountOfCode.php`
	 - `php ./public/LeastAmountOfCode.php keyword`
	 - `php ./public/LeastAmountOfCode.php keyword1 keyword2`
 - PHP Built-in Server
	 - `php -S localhost:8080 -t ./public`
	 - http://localhost:8080
	 - http://localhost:8080?keywords='keyword'
	 - http://localhost:8080?keywords[]='keyword1'&keywords[]='keyword2'
	 - http://localhost:8080/LeastAmountOfCode.php
	 - http://localhost:8080/LeastAmountOfCode.php?keywords='keyword'
	 - http://localhost:8080/LeastAmountOfCode.php?keywords[]='keyword1'&keywords[]='keyword2'
