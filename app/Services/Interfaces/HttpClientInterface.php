<?php

namespace App\Services\Interfaces;

interface HttpClientInterface
{
    public function prepareRequest($filmId);

    public function sendRequest($filmId);
}
