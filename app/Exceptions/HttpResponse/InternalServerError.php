<?php

namespace App\Exceptions\HttpResponse;

use Symfony\Component\HttpFoundation\Response;

class InternalServerError extends BaseHttpException
{
    public function getDefaultErrorCode(): int
    {
        return Response::HTTP_INTERNAL_SERVER_ERROR;
    }

    public function getDefaultMessage(): string
    {
        return "There are some error on the server please try again later";
    }
}
