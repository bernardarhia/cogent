<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit067de9a1d0b583fb240da014560d42a6
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'Articulate\\' => 11,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Articulate\\' => 
        array (
            0 => __DIR__ . '/../..' . '/lib/Articulate',
        ),
    );

    public static $classMap = array (
        'Articulate\\Interfaces\\SchemaInterface' => __DIR__ . '/../..' . '/lib/Articulate/Interfaces/SchemaInterface.php',
        'Articulate\\Models\\Model' => __DIR__ . '/../..' . '/lib/Articulate/Models/Model.php',
        'Articulate\\Schemas\\Schema' => __DIR__ . '/../..' . '/lib/Articulate/Schemas/Schema.php',
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit067de9a1d0b583fb240da014560d42a6::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit067de9a1d0b583fb240da014560d42a6::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit067de9a1d0b583fb240da014560d42a6::$classMap;

        }, null, ClassLoader::class);
    }
}
