<?php

namespace AppBundle\Service;

class BoldService
{
    public function boldAction($string)
    {
        return '<strong>' . $string . '</strong>' ;
    }
}
