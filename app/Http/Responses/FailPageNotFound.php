<?php

namespace App\Http\Responses;

use Symfony\Component\HttpFoundation\Response;

class FailPageNotFound extends Base
{
    public int $statusCode = Response::HTTP_NOT_FOUND;
    protected string $message;

    /**
     * @param string $message
     * @param int $statusCode
     */
    public function __construct(
        string $message = 'Запрашиваемая страница не существует.',
        int $statusCode = Response::HTTP_NOT_FOUND
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
