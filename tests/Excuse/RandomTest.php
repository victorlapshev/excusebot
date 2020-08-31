<?php

namespace App\Tests\Excuse;

use App\Entity\Excuse;
use App\Excuse\Random;
use App\Repository\ExcuseRepository;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;

class RandomTest extends TestCase
{
    public function testGet()
    {
        $text = 'Mocked Random text from database';

        $excuseEntity = $this->createMock(Excuse::class);
        $excuseEntity->expects($this->any())
            ->method('getText')
            ->willReturn($text);
        $excuseRepository = $this->createMock(ExcuseRepository::class);
        $excuseRepository->expects($this->any())
            ->method('findRandomOne')
            ->willReturn($excuseEntity);
        $entityManager = $this->createMock(EntityManager::class);
        $entityManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($excuseRepository);

        $randomExcuse = new Random($entityManager);

        echo $randomExcuse->get();

        $this->assertEquals($randomExcuse->get(), $text);
    }
}
