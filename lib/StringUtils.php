<?php

namespace lib;

/**
 * Class StringUtils
 * @package lib
 */
class StringUtils {
    
    public static function CheckPhoneNumberFormat($phone){
        preg_match('/[78]{1}[0-9]{10}/', $phone, $phoneMatches);
        if( !empty($phoneMatches) )return true;
        
        return false;
    }
}