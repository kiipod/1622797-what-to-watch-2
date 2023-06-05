<?php

namespace App\Http\Responses;

use Symfony\Component\HttpFoundation\Response;

class FailResponse extends BaseResponse
{
    public int $statusCode = Response::HTTP_REQUEST_TIMEOUT;
    protected string $message;

    /**
     * @param string $message
     * @param int $statusCode
     */
    public function __construct(
        string $message = 'Истекло время ожидания, повторите попытку.',
        int $statusCode = Response::HTTP_REQUEST_TIMEOUT
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
