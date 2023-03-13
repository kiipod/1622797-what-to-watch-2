<?php

namespace App\Http\Responses;

use Symfony\Component\HttpFoundation\Response;

class FailValidation extends Base
{
    public int $statusCode = Response::HTTP_UNPROCESSABLE_ENTITY;
    protected string $message;

    /**
     * @param $data
     * @param string $message
     * @param int $statusCode
     */
    public function __construct(
        $data,
        string $message = 'Переданные данные не корректны.',
        int $statusCode = Response::HTTP_UNPROCESSABLE_ENTITY
    ) {
        $this->message = $message;

        parent::__construct([], $statusCode);
    }

    /**
     * @return array|null
     */
    protected function makeResponseData(): ?array
    {
        return [
            'message' => $this->message,
            'errors' => $this->prepareData()
        ];
    }
}
