<?php

namespace App\Service;


class BeforeTimestampBunchService
{
    const BUNCH_SIZE = 10;

    /**
     * @param $callback
     * @param int $count
     * @param null $beforeTimestamp
     * @param array $params
     * @return array
     */
    public static function execute($callback, $count = self::BUNCH_SIZE, $beforeTimestamp = null, $params = []): array
    {
        $beforeTimestamp = $beforeTimestamp ?? now();
        $result = [];
        $dataToLoad = $count;

        do {
            echo $dataToLoad . "\n";
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

            $result = array_merge($result, $items);
            $dataToLoad -= count($items);

            if (count($items) !== $limit) {
                ddd($bunchResult);
            }

            $beforeTimestamp = $bunchResult->cursors->before;

        } while ($dataToLoad > 0);

        return $result;
    }
}
