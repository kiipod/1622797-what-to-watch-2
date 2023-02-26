<?php

require_once('vendor/autoload.php');

use src\repositories\MovieRepository;
use src\repositories\OmdbHttpClient;

$client = new OmdbHttpClient();
$repository = new MovieRepository($client);

$movies = $repository->getMoviesInfo('tt3896198');
var_dump($movies);
