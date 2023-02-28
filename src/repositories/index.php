<?php

require_once('vendor/autoload.php');

use src\repositories\OmdbHttpClient;
use src\Services\GetFilmService;

$client = new OmdbHttpClient();
$services = new GetFilmService($client);

$movies = $services->getFilm('tt3896198');
var_dump($movies);
