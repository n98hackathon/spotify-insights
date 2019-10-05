<?php

namespace App\Service;


class BeforeTimestampBunchService
{
    const BUNCH_SIZE = 1;

    /**
     * @param $callback
     * @param int $count
     * @param null $beforeTimestamp
     * @param array $params
     * @return array
     */
    public static function execute($callback, $count = self::BUNCH_SIZE, $beforeTimestamp = null, $params = []) : array
    {
        $beforeTimestamp = $beforeTimestamp ?? now();
        $result = [];
        $dataToLoad = $count;

        do {
            $limit = ($dataToLoad > self::BUNCH_SIZE) ? self::BUNCH_SIZE : $dataToLoad;

            $params = array_merge(
                $params,
                [
                    'before' => $beforeTimestamp,
                    'limit' => $limit
                ]
            );

            $bunchResult = $callback($params);
            $items = $bunchResult->items;
            $beforeTimestamp = $bunchResult->cursors->before;

            $result = array_merge($result, $items);
            $dataToLoad -= count($items);
        } while ($dataToLoad > 0);

        return $result;
    }
}
