<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit62e10e6c8df9afc192311b196c75bb33
{
    public static $prefixLengthsPsr4 = array (
        'Z' => 
        array (
            'ZipStream\\' => 10,
        ),
        'P' => 
        array (
            'Psr\\SimpleCache\\' => 16,
            'Psr\\Http\\Message\\' => 17,
            'Psr\\Http\\Client\\' => 16,
            'PhpOffice\\PhpSpreadsheet\\' => 25,
        ),
        'M' => 
        array (
            'MyCLabs\\Enum\\' => 13,
            'Matrix\\' => 7,
        ),
        'H' => 
        array (
            'Hashids\\' => 8,
        ),
        'C' => 
        array (
            'Composer\\Pcre\\' => 14,
            'Complex\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'ZipStream\\' => 
        array (
            0 => __DIR__ . '/..' . '/maennchen/zipstream-php/src',
        ),
        'Psr\\SimpleCache\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/simple-cache/src',
        ),
        'Psr\\Http\\Message\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/http-factory/src',
            1 => __DIR__ . '/..' . '/psr/http-message/src',
        ),
        'Psr\\Http\\Client\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/http-client/src',
        ),
        'PhpOffice\\PhpSpreadsheet\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpoffice/phpspreadsheet/src/PhpSpreadsheet',
        ),
        'MyCLabs\\Enum\\' => 
        array (
            0 => __DIR__ . '/..' . '/myclabs/php-enum/src',
        ),
        'Matrix\\' => 
        array (
            0 => __DIR__ . '/..' . '/markbaker/matrix/classes/src',
        ),
        'Hashids\\' => 
        array (
            0 => __DIR__ . '/..' . '/hashids/hashids/src',
        ),
        'Composer\\Pcre\\' => 
        array (
            0 => __DIR__ . '/..' . '/composer/pcre/src',
        ),
        'Complex\\' => 
        array (
            0 => __DIR__ . '/..' . '/markbaker/complex/classes/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'Stringable' => __DIR__ . '/..' . '/myclabs/php-enum/stubs/Stringable.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit62e10e6c8df9afc192311b196c75bb33::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit62e10e6c8df9afc192311b196c75bb33::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit62e10e6c8df9afc192311b196c75bb33::$classMap;

        }, null, ClassLoader::class);
    }
}
