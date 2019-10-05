<?php

namespace App\Service;


class SimpleBunchService
{
    const BUNCH_SIZE = 50;

    /**
     * @param $data
     * @param $callback
     * @param array $params
     * @return array
     */
    public static function execute($data, $callback, $params = [])
    {
        $offset = 0;
        $result = [];
        do {
            $bunchData = array_slice($data, $offset, self::BUNCH_SIZE, true);
            $bunchResult = $callback($bunchData, ...$params);
            $result = array_merge($result, $bunchResult);
            $offset += self::BUNCH_SIZE;
        } while ($offset < count($data) - 1);

        return $result;
    }
}
