<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\PostRepository;

final class PostController extends Controller
{
    public function show()
    {
        if (!$this->db->isAvailable()) {
            http_response_code(500);
            $this->view->render('errors/500.tpl', [
                'title' => 'Ошибка подключения к базе',
                'message' => 'Проверьте настройки MySQL в config/config.php и запустите сидинг: ?r=seed',
            ]);
            return;
        }

        $postId = $this->intQuery('id', 0);
        $repo = new PostRepository($this->db);

        $post = $repo->getById($postId);
        if ($post === null) {
            http_response_code(404);
            $this->view->render('errors/404.tpl', [
                'title' => 'Статья не найдена',
            ]);
            return;
        }

        $repo->incrementViews($postId);
        $post = $repo->getById($postId);
        $post['paragraphs'] = preg_split("/\\R\\R+/u", trim((string)($post['body'] ?? ''))) ?: [];

        $limit = (int)($this->config['blog']['similar_posts_limit'] ?? 3);
        $similar = $repo->getSimilar($postId, $limit);

        $this->view->render('post.tpl', [
            'title' => $post['title'],
            'post' => $post,
            'similar' => $similar,
        ]);
    }
}
