<?php

namespace App\Http\Responses;

use Symfony\Component\HttpFoundation\Response;

class FailAuthResponse extends BaseResponse
{
    public int $statusCode = Response::HTTP_UNAUTHORIZED;
    protected string $message;

    /**
     * @param string $message
     * @param int $statusCode
     */
    public function __construct(
        string $message = 'Запрос требует аутентификации.',
        int $statusCode = Response::HTTP_UNAUTHORIZED
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
            'message' => $this->message
        ];
    }
}
