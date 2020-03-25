<?php

namespace App\Services;

class RpcRequest
{
    protected $request;

    protected $error = null;

    protected $format = [
        'jsonrpc' => '2.0',
        'method' => 'required',
        'params' => 'required',
        'id' => 'required',
    ];

    /**
     * Initialize a request object.
     *
     * @param mixed $request
     */
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * Validate the request.
     *
     * @return boolean
     */
    public function validate(): bool
    {
        if ($this->request === null || json_last_error() !== JSON_ERROR_NONE) {
            $this->error = RpcResponse::ERROR_PARSE_ERROR;

            return false;
        }

        foreach ($this->format as $field => $value) {
            if (!isset($this->request[$field]) && $value === 'required') {
                $this->error = RpcResponse::ERROR_INVALID_REQUEST;

                return false;
            }

            if ($value !== 'required' && (!isset($this->request[$field]) || $value !== $this->request[$field])) {
                $this->error = RpcResponse::ERROR_INVALID_REQUEST;

                return false;
            }
        }
        
        return true;
    }

    /**
     * Get the request error.
     *
     * @return void
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Get the request id.
     *
     * @return void
     */
    public function getId()
    {
        return $this->request['id'] ?? null;
    }

    /**
     * Get the request params.
     *
     * @return array
     */
    public function getParams(): array
    {
        return $this->request['params'] ?? [];
    }
}