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

    public function getRandom()
    {
        return $this->excuses[rand(0, count($this->excuses) - 1)];
    }
}
