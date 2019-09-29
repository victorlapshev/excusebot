<?php

namespace App;

class Excuses
{
    protected $excuses = [];

    /**
     * Excuses constructor.
     */
    public function __construct()
    {
        $res = file_get_contents(__DIR__ . '/../resources/excuses.txt');

        $this->excuses = explode(PHP_EOL, $res);
    }

    public function getRandom(): string
    {
        return $this->excuses[rand(0, count($this->excuses) - 1)];
    }

    public function search(string $query, int $limit = 3): array
    {
        $result = [];
        $i = 0;

        while (++$i < count($this->excuses) && count($result) < $limit) {
            $excuse = $this->excuses[$i];
            if (stripos($excuse, $query) !== false) {
                $result[] = [
                    'text' => $excuse,
                ];
            }
        }

        return $result;
    }
}
