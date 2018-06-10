<?php

namespace Tests\Louvre\BackendBundle\Utils;

use Louvre\BackendBundle\Utils\LouvreIdGenerator;
use PHPUnit\Framework\TestCase;

class LouvreIdGeneratorTest extends TestCase
{

    public function testGenerateUniqueId()
    {

        $generator = new LouvreIdGenerator();
        $uniqueId1 = $generator->generateUniqueId();
        $uniqueId2 = $generator->generateUniqueId();

        $this->assertNotEquals($uniqueId1, $uniqueId2);

    }
}
