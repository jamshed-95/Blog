<?php

namespace App\Core;

use App\Controllers\CategoryController;
use App\Controllers\HomeController;
use App\Controllers\PostController;
use App\Controllers\SeedController;

final class Router
{
    /**
     * @return array{class-string, string}|null
     */
    public function resolve(string $route)
    {
        return match ($route) {
            'home' => [HomeController::class, 'index'],
            'category' => [CategoryController::class, 'show'],
            'post' => [PostController::class, 'show'],
            'seed' => [SeedController::class, 'run'],
            default => null,
        };
    }
}
