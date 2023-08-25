<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitd0d8942009aae57774a4d4f01528cf1d
{
    public static $prefixLengthsPsr4 = array (
        'T' => 
        array (
            'Tech\\DigitalOceanProject\\' => 25,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Tech\\DigitalOceanProject\\' => 
        array (
            0 => __DIR__ . '/../..' . '/public',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitd0d8942009aae57774a4d4f01528cf1d::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitd0d8942009aae57774a4d4f01528cf1d::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitd0d8942009aae57774a4d4f01528cf1d::$classMap;

        }, null, ClassLoader::class);
    }
}