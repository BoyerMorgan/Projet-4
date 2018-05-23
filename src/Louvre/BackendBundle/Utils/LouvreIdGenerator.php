<?php

namespace Louvre\BackendBundle\Utils;

class LouvreIdGenerator
{
    function generateUniqueId()
    {
        $uniqueId = 0;
        srand((double)microtime(TRUE) * 1000000);
        $chars = array(
            'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'p',
            'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '1', '2', '3', '4', '5',
            '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K',
            'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');

        for ($rand = 0; $rand <= 20; $rand++) {
            $random = rand(0, count($chars) - 1);
            $uniqueId .= $chars[$random];
        }

        return $uniqueId;
    }
}