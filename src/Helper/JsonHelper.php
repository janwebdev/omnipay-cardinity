<?php

namespace Omnipay\Cardinity\Helper;

final class JsonHelper
{
    /**
     * @param array $data
     * @return string
     * @throws \JsonException
     */
    public static function encode(array $data): string
    {
        return json_encode($data, JSON_THROW_ON_ERROR);
    }

    /**
     * @param string $json
     * @param bool $assoc
     * @param int $depth
     * @param int $option
     * @return array
     * @throws \JsonException
     */
    public static function decode(
        string $json,
        bool $assoc = true,
        int $depth = 512,
        int $option = JSON_THROW_ON_ERROR
    ): array {
        $data = json_decode($json, $assoc, $depth, $option);
        $jsonLastError = json_last_error();

        if ($jsonLastError !== JSON_ERROR_NONE) {
            throw new \JsonException(json_last_error_msg(), $jsonLastError);
        }

        return $data;
    }
}
