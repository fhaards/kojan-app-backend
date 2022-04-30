<?php

namespace App\Exceptions\HttpResponse;

use Symfony\Component\HttpFoundation\Response;

class NotFound extends BaseHttpException
{
    public function getDefaultErrorCode(): int
    {
        return Response::HTTP_NOT_FOUND;
    }

    public function getDefaultMessage(): string
    {
        return "Not Found";
    }
}
