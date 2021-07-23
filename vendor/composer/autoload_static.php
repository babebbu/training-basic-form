<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitcdf7283cc81747a72b5336366a35dc18
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitcdf7283cc81747a72b5336366a35dc18::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitcdf7283cc81747a72b5336366a35dc18::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitcdf7283cc81747a72b5336366a35dc18::$classMap;

        }, null, ClassLoader::class);
    }
}
