<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit367c59cb3ac97eda5374cb10ba13e60f
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Stripe\\' => 7,
        ),
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
        'B' => 
        array (
            'BaraaHawa\\BarabossCom\\' => 22,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Stripe\\' => 
        array (
            0 => __DIR__ . '/..' . '/stripe/stripe-php/lib',
        ),
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
        'BaraaHawa\\BarabossCom\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit367c59cb3ac97eda5374cb10ba13e60f::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit367c59cb3ac97eda5374cb10ba13e60f::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit367c59cb3ac97eda5374cb10ba13e60f::$classMap;

        }, null, ClassLoader::class);
    }
}
