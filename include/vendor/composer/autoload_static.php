<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit0bb4b7a3e080d3ecb647b0d86f62974e
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Plasticbrain\\FlashMessages\\' => 27,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Plasticbrain\\FlashMessages\\' => 
        array (
            0 => __DIR__ . '/..' . '/plasticbrain/php-flash-messages/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit0bb4b7a3e080d3ecb647b0d86f62974e::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit0bb4b7a3e080d3ecb647b0d86f62974e::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit0bb4b7a3e080d3ecb647b0d86f62974e::$classMap;

        }, null, ClassLoader::class);
    }
}
