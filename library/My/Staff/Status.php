<?php
class My_Staff_Status
{
    const On            = 1;
    const Off           = 0;
    const Temporary_Off = 2;
    const Childbearing  = 3;

    public static $names = array(
        self::On            => 'On',
        self::Off           => 'Off',
        self::Temporary_Off => 'Temporary Off',
        self::Childbearing  => 'Childbearing',
    );
}