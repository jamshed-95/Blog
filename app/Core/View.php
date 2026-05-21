<?php

namespace App\Core;

use Smarty\Smarty;

final class View
{
    private array $config;
    private ?Smarty $smarty = null;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function render(string $template, array $vars = [])
    {
        $smarty = $this->smarty();

        foreach ($vars as $key => $value) {
            $smarty->assign($key, $value);
        }

        $smarty->display($template);
    }

    public function smarty()
    {
        if ($this->smarty instanceof Smarty) {
            return $this->smarty;
        }

        if (!class_exists(Smarty::class)) {
            http_response_code(500);
            header('Content-Type: text/plain; charset=utf-8');
            echo "Smarty не найден.\n\nВыполните:\ncomposer install\n";
            exit;
        }

        $root = dirname(__DIR__, 2);
        $storage = $root . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'smarty';

        $compileDir = $storage . DIRECTORY_SEPARATOR . 'templates_c';
        $cacheDir = $storage . DIRECTORY_SEPARATOR . 'cache';
        $configDir = $storage . DIRECTORY_SEPARATOR . 'configs';

        foreach ([$compileDir, $cacheDir, $configDir] as $dir) {
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
        }

        $smarty = new Smarty();
        $smarty->setTemplateDir($root . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Views');
        $smarty->setCompileDir($compileDir);
        $smarty->setCacheDir($cacheDir);
        $smarty->setConfigDir($configDir);
        $smarty->escape_html = true;

        $smarty->assign('baseUrl', rtrim($this->config['app']['base_url'] ?? '', '/'));

        $this->smarty = $smarty;
        return $this->smarty;
    }
}
