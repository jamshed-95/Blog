<?php

namespace App\Core;

final class App
{
    private array $config;
    private Database $db;
    private View $view;
    private Router $router;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->db = new Database($config['db']);
        $this->view = new View($config);
        $this->router = new Router();
    }

    public function run()
    {
        $route = (string)($_GET['r'] ?? 'home');
        $handler = $this->router->resolve($route);

        if ($handler === null) {
            http_response_code(404);
            $this->view->render('errors/404.tpl', [
                'title' => 'Страница не найдена',
            ]);
            return;
        }

        [$controllerClass, $method] = $handler;
        $controller = new $controllerClass($this->db, $this->view, $this->config);

        if (!method_exists($controller, $method)) {
            http_response_code(500);
            $this->view->render('errors/500.tpl', [
                'title' => 'Ошибка приложения',
                'message' => 'Метод контроллера не найден.',
            ]);
            return;
        }

        $controller->$method();
    }
}
