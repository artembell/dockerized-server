<?php

namespace models;

class PathResolver
{
    public static $defaultPage = 'home';

    public static $paths = [
        [
            "text" => "Home",
            "location" => "/home",
        ],
        [
            "text" => "About",
            "location" => "/about",
        ],
        [
            "text" => "Support",
            "location" => "/support",
        ],
        [
            "text" => "Pricing",
            "location" => "/pricing",
        ],
        [
            "text" => "Contacts",
            "location" => "/contacts",
        ],
        [
            "text" => "Laba 2",
            "location" => "/laba-2",
        ],
    ];

    private $serverRoot;
    private $serverPagesDir;

    public function __construct(string $serverRoot, string $serverPagesDir) {
        $this->serverRoot = $serverRoot;
        $this->serverPagesDir = $serverPagesDir;
    }  

    public function resolvePath(string $target): string {
        $absolutePagesPath = $this->serverRoot."/".$this->serverPagesDir;
        $absolutePath = $absolutePagesPath."/".$target.".php";
        
        $finalTarget;

        if ($target == "") {
            $finalTarget = "home";
        } else if (file_exists($absolutePath)) {
            $finalTarget = $target;
        } else {
            $finalTarget = "404";
        }

        return $absolutePagesPath."/".$finalTarget.".php";;
    }
}
