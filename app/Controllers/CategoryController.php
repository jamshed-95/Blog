<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Paginator;
use App\Models\CategoryRepository;

final class CategoryController extends Controller
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

        $categoryId = $this->intQuery('id', 0);
        $sort = $this->stringQuery('sort', 'date');
        $page = max(1, $this->intQuery('page', 1));

        $repo = new CategoryRepository($this->db);
        $category = $repo->getById($categoryId);

        if ($category === null) {
            http_response_code(404);
            $this->view->render('errors/404.tpl', [
                'title' => 'Категория не найдена',
            ]);
            return;
        }

        $pageSize = (int)($this->config['blog']['category_page_size'] ?? 6);
        $total = $repo->countPostsByCategory($categoryId);
        $paginator = new Paginator($page, $pageSize, $total);

        $posts = $repo->getPostsByCategory($categoryId, $sort, $paginator->pageSize, $paginator->offset());

        $this->view->render('category.tpl', [
            'title' => $category['name'],
            'category' => $category,
            'posts' => $posts,
            'sort' => $sort,
            'pagination' => [
                'page' => $paginator->page,
                'pageSize' => $paginator->pageSize,
                'total' => $paginator->total,
                'pages' => $paginator->pages(),
                'hasPrev' => $paginator->hasPrev(),
                'hasNext' => $paginator->hasNext(),
                'prevPage' => $paginator->prevPage(),
                'nextPage' => $paginator->nextPage(),
            ],
        ]);
    }
}
