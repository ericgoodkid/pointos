<?php

namespace Libraries;

class StringLibrary
{

    public static function isEmpty($sString)
    {
        return (strlen(trim($sString)) === 0);
    }
}
