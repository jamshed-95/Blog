<?php

namespace App\Models;

use App\Core\Database;
use PDO;

final class PostRepository
{
    private PDO $pdo;

    public function __construct(Database $db)
    {
        $this->pdo = $db->pdo();
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT id, image_url, title, description, body, created_at, views
            FROM posts
            WHERE id = :id
        ");
        $stmt->execute(['id' => $id]);
        $post = $stmt->fetch();

        if ($post === false) {
            return null;
        }

        $post['categories'] = $this->getCategoriesForPost($id);
        return $post;
    }

    public function incrementViews(int $id): void
    {
        $stmt = $this->pdo->prepare("UPDATE posts SET views = views + 1 WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

    public function getLatestByCategory(int $categoryId, int $limit): array
    {
        $stmt = $this->pdo->prepare("
            SELECT p.id, p.image_url, p.title, p.description, p.created_at, p.views
            FROM posts p
            INNER JOIN post_categories pc ON pc.post_id = p.id
            WHERE pc.category_id = :category_id
            ORDER BY p.created_at DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getSimilar(int $postId, int $limit): array
    {
        $categoryIds = array_map(
            static fn (array $c) => (int)$c['id'],
            $this->getCategoriesForPost($postId)
        );

        if ($categoryIds === []) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($categoryIds), '?'));

        $sql = "
            SELECT DISTINCT p.id, p.image_url, p.title, p.description, p.created_at, p.views
            FROM posts p
            INNER JOIN post_categories pc ON pc.post_id = p.id
            WHERE p.id <> ?
              AND pc.category_id IN ({$placeholders})
            ORDER BY p.created_at DESC
            LIMIT ?
        ";

        $params = array_merge([$postId], $categoryIds, [$limit]);
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    private function getCategoriesForPost(int $postId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT c.id, c.name
            FROM categories c
            INNER JOIN post_categories pc ON pc.category_id = c.id
            WHERE pc.post_id = :post_id
            ORDER BY c.name ASC
        ");
        $stmt->execute(['post_id' => $postId]);
        return $stmt->fetchAll();
    }
}
