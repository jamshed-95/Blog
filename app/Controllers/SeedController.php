<?php

namespace App\Controllers;

use App\Core\Controller;
use PDO;
use PDOException;

final class SeedController extends Controller
{
    public function run()
    {
        $reset = $this->intQuery('reset', 0) === 1;

        try {
            $this->db->createDatabaseIfNotExists();
            $this->db->resetConnection();
            $pdo = $this->db->pdo();
        } catch (PDOException $e) {
            http_response_code(500);
            $this->view->render('errors/500.tpl', [
                'title' => 'Ошибка подключения к базе',
                'message' => 'Проверьте настройки MySQL (DB_HOST/DB_USER/DB_PASS/DB_NAME) в config/config.php или через переменные окружения.',
            ]);
            return;
        }

        $this->createTables($pdo);

        if ($reset) {
            $pdo->exec("SET FOREIGN_KEY_CHECKS=0");
            $pdo->exec("TRUNCATE TABLE post_categories");
            $pdo->exec("TRUNCATE TABLE posts");
            $pdo->exec("TRUNCATE TABLE categories");
            $pdo->exec("SET FOREIGN_KEY_CHECKS=1");
        }

        $counts = [
            'categories' => (int)$pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn(),
            'posts' => (int)$pdo->query("SELECT COUNT(*) FROM posts")->fetchColumn(),
        ];

        if ($counts['categories'] === 0 && $counts['posts'] === 0) {
            $this->seed($pdo);
            $counts = [
                'categories' => (int)$pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn(),
                'posts' => (int)$pdo->query("SELECT COUNT(*) FROM posts")->fetchColumn(),
            ];
        }

        $this->view->render('seed.tpl', [
            'title' => 'Сидинг',
            'reset' => $reset,
            'counts' => $counts,
        ]);
    }

    private function createTables(PDO $pdo)
    {
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS categories (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                description TEXT NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        $pdo->exec("
            CREATE TABLE IF NOT EXISTS posts (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                image_url VARCHAR(1024) NULL,
                title VARCHAR(255) NOT NULL,
                description TEXT NULL,
                body MEDIUMTEXT NOT NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                views INT UNSIGNED NOT NULL DEFAULT 0,
                INDEX idx_posts_created_at (created_at),
                INDEX idx_posts_views (views)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        $pdo->exec("
            CREATE TABLE IF NOT EXISTS post_categories (
                post_id INT UNSIGNED NOT NULL,
                category_id INT UNSIGNED NOT NULL,
                PRIMARY KEY (post_id, category_id),
                CONSTRAINT fk_pc_post FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
                CONSTRAINT fk_pc_category FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
                INDEX idx_pc_category (category_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
    }

    private function seed(PDO $pdo)
    {
        $categories = [
            ['Технологии', 'Новости и заметки о разработке.'],
            ['Дизайн', 'Интерфейсы, типографика и практические советы.'],
            ['Путешествия', 'Короткие истории и маршруты.'],
            ['Кофе', 'Зерно, рецепты и дегустации.'],
        ];

        $stmtCat = $pdo->prepare("INSERT INTO categories (name, description) VALUES (:name, :description)");
        foreach ($categories as [$name, $description]) {
            $stmtCat->execute(['name' => $name, 'description' => $description]);
        }

        $categoryIds = $pdo->query("SELECT id FROM categories ORDER BY id ASC")->fetchAll(PDO::FETCH_COLUMN);

        $lorem = [
            'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec at neque sed dolor vulputate vestibulum.',
            'Integer vel dignissim neque. Suspendisse potenti. Etiam sed commodo nibh, in viverra urna.',
            'Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae.',
            'Proin vitae metus a augue facilisis dictum. Aenean nec arcu quis enim convallis cursus.',
        ];

        $stmtPost = $pdo->prepare("
            INSERT INTO posts (image_url, title, description, body, created_at, views)
            VALUES (:image_url, :title, :description, :body, :created_at, :views)
        ");

        $stmtLink = $pdo->prepare("INSERT IGNORE INTO post_categories (post_id, category_id) VALUES (:post_id, :category_id)");

        for ($i = 1; $i <= 24; $i++) {
            $title = "Демо-статья #{$i}";
            $description = $lorem[$i % count($lorem)];
            $body = implode("\n\n", $lorem) . "\n\n" . implode("\n\n", array_reverse($lorem));
            $imageUrl = "https://picsum.photos/seed/post{$i}/800/500";
            $createdAt = (new \DateTimeImmutable('now'))->modify('-' . (25 - $i) . ' days')->format('Y-m-d H:i:s');
            $views = random_int(0, 350);

            $stmtPost->execute([
                'image_url' => $imageUrl,
                'title' => $title,
                'description' => $description,
                'body' => $body,
                'created_at' => $createdAt,
                'views' => $views,
            ]);

            $postId = (int)$pdo->lastInsertId();

            $pickCount = random_int(1, min(3, count($categoryIds)));
            shuffle($categoryIds);
            $picked = array_slice($categoryIds, 0, $pickCount);

            foreach ($picked as $categoryId) {
                $stmtLink->execute([
                    'post_id' => $postId,
                    'category_id' => (int)$categoryId,
                ]);
            }
        }
    }
}
