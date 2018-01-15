<?php

namespace DB\ServiceBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

class LogController extends FOSRestController {

    public function writelog($array) {
        $str = "";
        foreach ($array as $key => $value) {
            if (is_array($value))
                $str .= $key . " => [ " . $this->writelog( $value). " ]";
            else
                $str .= $key . " => " . $value . ", ";
        }
        return $str;
    }
    
}
