<?php

namespace models;

class PathResolver
{
    private const HOME_PAGE = 'home';
    private const NOT_FOUND_PAGE = '404';
    public $defaultPage = self::HOME_PAGE;

    private $serverRoot;
    private $serverPagesDir;
    private $links;

    public function __construct(string $serverRoot, string $serverPagesDir)
    {
        $this->serverRoot = $serverRoot;
        $this->serverPagesDir = $serverPagesDir;
        $this->links = [
            [
                'text' => 'Home',
                'location' => '/home',
            ],
            [
                'text' => 'Labwork',
                'location' => '/labwork',
            ],
        ];
    }

    public function getLinks(): array
    {
        return $this->links;
    }

    public function getRootPath(): string
    {
        return $this->serverRoot;
    }

    public function resolvePathToPage(string $target): string
    {
        $absolutePagesPath = $this->serverRoot.'/'.$this->serverPagesDir;
        $absolutePath = $absolutePagesPath.'/'.$target.'.php';
        
        $finalTarget;

        if ($target == '') {
            $finalTarget = self::HOME_PAGE;
        } elseif (file_exists($absolutePath)) {
            $finalTarget = $target;
        } else {
            $finalTarget = self::NOT_FOUND_PAGE;
        }

        return $absolutePagesPath.'/'.$finalTarget.'.php';
    }

    public function resolvePathToFile(string $path): string
    {
        $absolutePagesPath = $this->serverRoot.'/'.$this->path;
    }
}
