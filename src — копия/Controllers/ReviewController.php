<?php
namespace App\Controllers;

use App\Config\Config;
use PDO;

class ReviewController
{
    public function add(): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        if (empty($_SESSION['user_id'])) {
            $_SESSION['flash'] = 'Только авторизованные пользователи могут оставлять отзывы';
            $this->redirectBack();
            return;
        }
        
        $productId = (int)($_POST['product_id'] ?? 0);
        $rating = (int)($_POST['rating'] ?? 5);
        $title = trim($_POST['title'] ?? '');
        $comment = trim($_POST['comment'] ?? '');
        
        $errors = [];
        if ($productId <= 0) $errors[] = 'Неверный товар';
        if ($rating < 1 || $rating > 5) $errors[] = 'Неверный рейтинг';
        if (mb_strlen($comment) < 20) $errors[] = 'Отзыв должен содержать минимум 20 символов';
        if (mb_strlen($comment) > 1000) $errors[] = 'Отзыв не должен превышать 1000 символов';
        
        if (!empty($errors)) {
            $_SESSION['flash'] = '❌ ' . implode('; ', $errors);
            $this->redirectBack();
            return;
        }
        
        try {
            $pdo = new PDO(Config::MYSQL_DSN, Config::MYSQL_USER, Config::MYSQL_PASSWORD,
                         [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
            
            $stmt = $pdo->prepare("
                INSERT INTO reviews (product_id, user_id, rating, title, comment)
                VALUES (:product_id, :user_id, :rating, :title, :comment)
            ");
            $stmt->execute([
                ':product_id' => $productId,
                ':user_id' => $_SESSION['user_id'],
                ':rating' => $rating,
                ':title' => $title ?: null,
                ':comment' => $comment
            ]);
            
            $_SESSION['flash'] = '✅ Спасибо! Ваш отзыв отправлен на модерацию.';
        } catch (\PDOException $e) {
            error_log("Review error: " . $e->getMessage());
            $_SESSION['flash'] = '❌ Ошибка при сохранении отзыва';
        }
        
        $this->redirectBack();
    }
    
    public function getProductReviews(int $productId): array
    {
        try {
            $pdo = new PDO(Config::MYSQL_DSN, Config::MYSQL_USER, Config::MYSQL_PASSWORD);
            $stmt = $pdo->prepare("
                SELECT r.*, u.username 
                FROM reviews r
                JOIN users u ON r.user_id = u.id
                WHERE r.product_id = :product_id AND r.is_approved = TRUE
                ORDER BY r.created_at DESC
            ");
            $stmt->execute([':product_id' => $productId]);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Get reviews error: " . $e->getMessage());
            return [];
        }
    }
    
    private function redirectBack(): void
    {
        $prevUrl = $_SERVER['HTTP_REFERER'] ?? '/products';
        header("Location: {$prevUrl}");
        exit;
    }
}