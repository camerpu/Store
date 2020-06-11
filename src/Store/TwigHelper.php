<?php

namespace Store;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

class TwigHelper
{
    /**
     * Create Twig Instantion
     * @param string $custom
     * @return Environment
     */
    public static function getTwig() : Environment
    {
        $loader = new FilesystemLoader(__DIR__ . '/../../views/');
        $twig = new Environment($loader, [
            'cache' =>  __DIR__ . '/../../page_cache/',
            'debug' => true,
        ]);;
        $twig->addExtension(new DebugExtension());

        return $twig;
    }
}