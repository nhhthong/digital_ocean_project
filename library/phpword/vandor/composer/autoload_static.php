<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit173b02320bfe3c271c4e07d95db34224
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PhpOffice\\PhpWord\\' => 18,
        ),
        'L' => 
        array (
            'Laminas\\Escaper\\' => 16,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PhpOffice\\PhpWord\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpoffice/phpword/src/PhpWord',
        ),
        'Laminas\\Escaper\\' => 
        array (
            0 => __DIR__ . '/..' . '/laminas/laminas-escaper/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit173b02320bfe3c271c4e07d95db34224::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit173b02320bfe3c271c4e07d95db34224::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit173b02320bfe3c271c4e07d95db34224::$classMap;

        }, null, ClassLoader::class);
    }
}
