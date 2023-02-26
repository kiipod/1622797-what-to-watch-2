<?php

namespace src\repositories\Interfaces;

interface HttpClientInterface
{
    public function prepareRequest($filmId);

    public function sendRequest($filmId);
}
