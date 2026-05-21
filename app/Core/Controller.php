<?php

namespace App\Core;

abstract class Controller
{
    protected Database $db;
    protected View $view;
    protected array $config;

    public function __construct(Database $db, View $view, array $config)
    {
        $this->db = $db;
        $this->view = $view;
        $this->config = $config;
    }

    protected function intQuery(string $key, int $default = 0)
    {
        $value = $_GET[$key] ?? null;
        if ($value === null) {
            return $default;
        }
        return max(0, (int)$value);
    }

    protected function stringQuery(string $key, string $default = '')
    {
        $value = $_GET[$key] ?? null;
        if ($value === null) {
            return $default;
        }
        return trim((string)$value);
    }

    protected function redirect(string $route, array $params = [])
    {
        $query = array_merge(['r' => $route], $params);
        $url = rtrim($this->config['app']['base_url'] ?? '', '/') . '/index.php?' . http_build_query($query);
        header('Location: ' . $url);
        exit;
    }
}
