<?php

namespace App\Models;

use App\Core\Database;
use PDO;

final class CategoryRepository
{
    private PDO $pdo;

    public function __construct(Database $db)
    {
        $this->pdo = $db->pdo();
    }

    public function getAllWithPosts(): array
    {
        $sql = "
            SELECT c.id, c.name, c.description, COUNT(pc.post_id) AS posts_count
            FROM categories c
            INNER JOIN post_categories pc ON pc.category_id = c.id
            GROUP BY c.id, c.name, c.description
            HAVING posts_count > 0
            ORDER BY c.name ASC
        ";
        return $this->pdo->query($sql)->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT id, name, description FROM categories WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row === false ? null : $row;
    }

    public function countPostsByCategory(int $categoryId): int
    {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) AS cnt
            FROM posts p
            INNER JOIN post_categories pc ON pc.post_id = p.id
            WHERE pc.category_id = :category_id
        ");
        $stmt->execute(['category_id' => $categoryId]);
        return (int)($stmt->fetchColumn() ?: 0);
    }

    public function getPostsByCategory(int $categoryId, string $sort, int $limit, int $offset): array
    {
        $orderBy = match ($sort) {
            'views' => 'p.views DESC, p.created_at DESC',
            default => 'p.created_at DESC',
        };

        $stmt = $this->pdo->prepare("
            SELECT p.id, p.image_url, p.title, p.description, p.created_at, p.views
            FROM posts p
            INNER JOIN post_categories pc ON pc.post_id = p.id
            WHERE pc.category_id = :category_id
            GROUP BY p.id, p.image_url, p.title, p.description, p.created_at, p.views
            ORDER BY {$orderBy}
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
