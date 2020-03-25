<?php

namespace App\Services;

class RpcResponse
{
    const ERROR_PARSE_ERROR = [
        'code' => -32700,
        'message' => 'Parse error',
    ];

    const ERROR_INVALID_REQUEST = [
        'code' => -32600,
        'message' => 'Invalid Request',
    ];

    const ERROR_METHOD_NOT_FOUND = [
        'code' => -32601,
        'message' => 'Method not found',
    ];

    const ERROR_INVALID_PARAMS = [
        'code' => -32602,
        'message' => 'Invalid params',
    ];

    /**
     * An error response.
     *
     * @param int|null $id
     * @param array $error
     * @return array
     */
    public static function error($id = null, array $error = null): array
    {
        return static::response($id, $error, null);
    }

    /**
     * A success response.
     *
     * @param int|null $id
     * @param mixed $result
     * @return array
     */
    public static function success($id = null, $result = null): array
    {
        return static::response($id, null, $result);
    }

    /**
     * An RPC response.
     *
     * @param int|null $id
     * @param array $error
     * @param mixed $result
     * @return array
     */
    protected static function response($id = null, array $error = null, $result = null): array
    {
        return [
            'id' => $id,
            'error' => $error,
            'result' => $result,
        ];
    }
}
