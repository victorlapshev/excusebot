<?php


namespace App;


class Excuses
{
    protected $excuses = [];

    /**
     * Excuses constructor.
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function __construct()
    {
        $res = file_get_contents(__DIR__ . '/../storage/app/excuses.txt');

        $this->excuses = explode(PHP_EOL, $res);
    }

    public function getRandom()
    {
        return $this->excuses[rand(0, count($this->excuses) - 1)];
    }
}
