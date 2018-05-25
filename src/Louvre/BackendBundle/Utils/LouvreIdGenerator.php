<?php

namespace Louvre\BackendBundle\Utils;

class LouvreIdGenerator
{
    public function generateUniqueId()
    {
         $uniqueId = random_bytes(20);
         return base64_encode($uniqueId);
    }
}