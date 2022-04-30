<?php

namespace App\Exceptions\HttpResponse;

use Exception;
use Throwable;

abstract class BaseHttpException extends Exception
{
    abstract public function getDefaultErrorCode(): int;
    abstract public function getDefaultMessage(): string;

    public function __construct(string $messageStringOrJson = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($messageStringOrJson, $code, $previous);
    }

    /**
     * Report the exception.
     *
     * @return void
     */
    public function report()
    {
        //
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        $message = (empty($this->getMessage()))
            ? $this->getDefaultMessage()
            : $this->getMessage();
        $errorCode = ($this->getCode() === 0)
            ? $this->getDefaultErrorCode()
            : $this->getCode();

        if ($this->isJson($message)) {
            return response()->json(
                json_decode($message, true),
                $errorCode
            );
        }

        return response()->json([
            'errors' => [
                'message' => $message,
            ]
        ], $errorCode);
    }

    protected function isJson(string $message): bool
    {
        return (!is_null(json_decode($message, true)));
    }
}
