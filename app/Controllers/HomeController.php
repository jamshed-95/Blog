<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\CategoryRepository;
use App\Models\PostRepository;

final class HomeController extends Controller
{
    public function index()
    {
        if (!$this->db->isAvailable()) {
            http_response_code(500);
            $this->view->render('errors/500.tpl', [
                'title' => 'Ошибка подключения к базе',
                'message' => 'Проверьте настройки MySQL в config/config.php и запустите сидинг: ?r=seed',
            ]);
            return;
        }

        $categoryRepo = new CategoryRepository($this->db);
        $postRepo = new PostRepository($this->db);

        $categories = $categoryRepo->getAllWithPosts();
        $limit = (int)($this->config['blog']['home_posts_per_category'] ?? 3);

        foreach ($categories as &$category) {
            $category['posts'] = $postRepo->getLatestByCategory((int)$category['id'], $limit);
        }
        unset($category);

        $this->view->render('home.tpl', [
            'title' => 'Блог',
            'categories' => $categories,
        ]);
    }
}
