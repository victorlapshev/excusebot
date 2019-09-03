<?php

namespace App;

use stdClass;

class TelegramUser
{
    /**
     * @var int
     */
    private $id;
    /**
     * @var string
     */
    private $name;
    /**
     * @var int
     */
    private $calls;

    public function __construct(int $id, string $name, $calls = 1)
    {
        $this->id = $id;
        $this->name = $name;
        $this->calls = $calls;
    }

    public static function load(string $json): self
    {
        $stdObject = json_decode($json, false);
        $modelObject = new self($stdObject->id, $stdObject->name, $stdObject->calls);

        return $modelObject;
    }

    /**
     * @return mixed
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName(): string
    {
        return $this->name;
    }

    public function incrementCalls()
    {
        $this->calls++;
    }

    public function __toString()
    {
        $stdObject = new stdClass();

        $stdObject->id = $this->id;
        $stdObject->name = $this->name;
        $stdObject->calls = $this->calls;

        return json_encode($stdObject);
    }
}
