<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit0eada5ddf9c81f18ca757c82ad003b98
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Src\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Src\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'AltoRouter' => __DIR__ . '/..' . '/altorouter/altorouter/AltoRouter.php',
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit0eada5ddf9c81f18ca757c82ad003b98::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit0eada5ddf9c81f18ca757c82ad003b98::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit0eada5ddf9c81f18ca757c82ad003b98::$classMap;

        }, null, ClassLoader::class);
    }
}
