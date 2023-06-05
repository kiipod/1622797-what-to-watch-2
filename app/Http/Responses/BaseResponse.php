<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Support\Arrayable;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseResponse implements Responsable
{
    protected mixed $data = [];
    public int $statusCode;

    /**
     * @param mixed $data
     * @param int $statusCode
     */
    public function __construct(mixed $data = [], int $statusCode = Response::HTTP_OK)
    {
        $this->data = $data;
        $this->statusCode = $statusCode;
    }

    /**
     * @param $request
     * @return JsonResponse|Response
     */
    public function toResponse($request): JsonResponse|Response
    {
        return response()->json($this->makeResponseData(), $this->statusCode);
    }

    /**
     * @return array|null
     */
    protected function prepareData(): ?array
    {
        if ($this->data instanceof Arrayable) {
            return $this->data->toArray();
        }
        return $this->data;
    }

    /**
     * @return array|null
     */
    abstract protected function makeResponseData(): ?array;
}
