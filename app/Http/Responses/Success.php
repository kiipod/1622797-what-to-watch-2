<?php

namespace App\Http\Responses;

class Success extends Base
{
    /**
     * @return array|null
     */
    protected function makeResponseData(): ?array
    {
        return ['data' => $this->prepareData()];
    }
}
