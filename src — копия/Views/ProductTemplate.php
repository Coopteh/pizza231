<?php
namespace App\Views;

class ProductTemplate extends BaseTemplate
{
    protected static function getImageFilename(string $image): string
    {
        $filename = basename($image);
        $filename = preg_replace('/[^a-zA-Z0-9._-]/u', '_', $filename);
        return $filename;
    }

    protected static function extractDurationFromDescription(string $description): string
    {
        $patterns = [
            '/(\d+\s*(?:день|дня|дней|час|часа|часов|неделя|недели|недель))/ui',
            '/(\d+\s*(?:сутки|суток|минута|минуты|минут))/ui'
        ];
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $description, $matches)) {
                return trim($matches[1]);
            }
        }
        return 'длительность по запросу';
    }

    public static function getAllTemplate(array $products): string
    {
        $template = parent::getTemplate();
        $cards = '';
        $items = array_values($products);

        foreach ($items as $product) {
            if (!is_array($product) || !isset($product['name'], $product['id'])) continue;

            $duration = self::extractDurationFromDescription($product['description'] ?? '');
            $imageFileName = self::getImageFilename($product['image'] ?? 'no-image.jpg');
            $price = $product['price'] ?? '0';
            $description = htmlspecialchars($product['description'] ?? '');
            $shortDesc = mb_strlen($description) > 120 ? mb_substr($description, 0, 120) . '...' : $description;
            $productName = htmlspecialchars($product['name']);

            $cards .= '<div class="col-md-6 col-lg-4 mb-4 animate-on-scroll">';
            $cards .= '<div class="card h-100 shadow-sm border-0 hover-lift">';
            $cards .= '<img src="/assets/img/' . $imageFileName . '" class="card-img-top" alt="' . $productName . '" style="height: 200px; object-fit: cover; border-radius: 0.375rem 0.375rem 0 0;" onerror="this.src=\'/assets/img/no-image.jpg\'">';
            $cards .= '<div class="card-body d-flex flex-column">';
            $cards .= '<h5 class="card-title fw-bold">' . $productName . '</h5>';
            $cards .= '<p class="card-text text-muted small mb-2"><i class="bi bi-clock me-1"></i> ' . $duration . '</p>';
            $cards .= '<p class="card-text flex-grow-1 small">' . $shortDesc . '</p>';
            $cards .= '<div class="d-flex justify-content-between align-items-center mt-3">';
            $cards .= '<h6 class="text-success mb-0 fw-bold"><i class="bi bi-currency-ruble"></i> ' . $price . '</h6>';
            $cards .= '<small class="text-body-secondary"><i class="bi bi-star-fill text-warning"></i> 4.9</small>';
            $cards .= '</div>';
            $cards .= '<div class="mt-3"><a href="/products/' . $product['id'] . '" class="btn btn-primary btn-sm w-100">Подробнее</a></div>';
            $cards .= '</div></div></div>';
        }

        if (empty($cards)) {
            $cards = '<div class="col-12"><p class="text-center text-muted">Товары не найдены</p></div>';
        }

        $content = '<h1 class="mb-4 text-center">Каталог туров</h1><div class="row g-4">' . $cards . '</div>';
        $title = 'Каталог туров';
        return sprintf($template, $title, $content);
    }

    public static function getCardTemplate(array $data): string
    {
        $template = parent::getTemplate();
        $duration = self::extractDurationFromDescription($data['description']);
        $imageFileName = self::getImageFilename($data['image']);
        $productName = htmlspecialchars($data['name']);
        $productDesc = htmlspecialchars($data['description']);
        $productPrice = $data['price'];
        $productId = (int)$data['id'];

        // Получаем отзывы
        $reviews = [];
        try {
            $pdo = new \PDO(\App\Config\Config::MYSQL_DSN, \App\Config\Config::MYSQL_USER, \App\Config\Config::MYSQL_PASSWORD);
            $stmt = $pdo->prepare("
                SELECT r.*, u.username 
                FROM reviews r
                JOIN users u ON r.user_id = u.id
                WHERE r.product_id = :product_id AND r.is_approved = TRUE
                ORDER BY r.created_at DESC
            ");
            $stmt->execute([':product_id' => $productId]);
            $reviews = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Get reviews error: " . $e->getMessage());
        }

        // Генерация HTML отзывов
        $reviewsHtml = '';
        if (!empty($reviews)) {
            foreach ($reviews as $review) {
                $stars = str_repeat('⭐', (int)$review['rating']);
                $reviewUsername = htmlspecialchars($review['username']);
                $reviewDate = date('d.m.Y', strtotime($review['created_at']));
                $reviewTitle = !empty($review['title']) ? '<h6 class="fw-bold">' . htmlspecialchars($review['title']) . '</h6>' : '';
                $reviewComment = nl2br(htmlspecialchars($review['comment']));
                
                $reviewsHtml .= '<div class="card mb-3 animate-fade-in">';
                $reviewsHtml .= '<div class="card-body">';
                $reviewsHtml .= '<div class="d-flex justify-content-between mb-2">';
                $reviewsHtml .= '<strong><i class="bi bi-person me-1"></i>' . $reviewUsername . '</strong>';
                $reviewsHtml .= '<small class="text-muted">' . $reviewDate . '</small>';
                $reviewsHtml .= '</div>';
                $reviewsHtml .= '<div class="mb-2">' . $stars . ' <small class="text-muted">(' . $review['rating'] . '/5)</small></div>';
                $reviewsHtml .= $reviewTitle;
                $reviewsHtml .= '<p class="card-text mb-0">' . $reviewComment . '</p>';
                $reviewsHtml .= '</div></div>';
            }
        }

        // Форма отзыва
        if (session_status() === PHP_SESSION_NONE) session_start();
        $reviewForm = '';
        if (!empty($_SESSION['user_id'])) {
            $reviewForm = '<div class="card mt-4 shadow-sm animate-fade-in" style="animation-delay: 0.2s">';
            $reviewForm .= '<div class="card-header bg-light"><h6 class="mb-0"><i class="bi bi-chat-left-text me-2"></i>Оставить отзыв</h6></div>';
            $reviewForm .= '<div class="card-body">';
            $reviewForm .= '<form method="POST" action="/reviews/add">';
            $reviewForm .= '<input type="hidden" name="product_id" value="' . $productId . '">';
            $reviewForm .= '<div class="mb-3"><label class="form-label">Ваша оценка</label>';
            $reviewForm .= '<div class="rating-input">';
            $reviewForm .= '<input type="radio" name="rating" id="star5" value="5" checked><label for="star5">⭐</label>';
            $reviewForm .= '<input type="radio" name="rating" id="star4" value="4"><label for="star4">⭐</label>';
            $reviewForm .= '<input type="radio" name="rating" id="star3" value="3"><label for="star3">⭐</label>';
            $reviewForm .= '<input type="radio" name="rating" id="star2" value="2"><label for="star2">⭐</label>';
            $reviewForm .= '<input type="radio" name="rating" id="star1" value="1"><label for="star1">⭐</label>';
            $reviewForm .= '</div></div>';
            $reviewForm .= '<div class="mb-3"><label class="form-label">Заголовок (необязательно)</label>';
            $reviewForm .= '<input type="text" class="form-control" name="title" maxlength="255" placeholder="Кратко о впечатлениях"></div>';
            $reviewForm .= '<div class="mb-3"><label class="form-label">Ваш отзыв *</label>';
            $reviewForm .= '<textarea class="form-control" name="comment" rows="3" required placeholder="Расскажите о вашем опыте..." minlength="20" maxlength="1000"></textarea>';
            $reviewForm .= '<small class="text-muted">Минимум 20 символов</small></div>';
            $reviewForm .= '<button type="submit" class="btn btn-primary"><i class="bi bi-send me-1"></i>Отправить на модерацию</button>';
            $reviewForm .= '</form></div></div>';
        } else {
            $reviewForm = '<div class="alert alert-info mt-4 animate-fade-in">';
            $reviewForm .= '<i class="bi bi-info-circle me-2"></i><a href="/login">Войдите</a> или <a href="/register">зарегистрируйтесь</a>, чтобы оставить отзыв';
            $reviewForm .= '</div>';
        }

        $reviewCount = count($reviews);

        // Основная карточка товара
        $card = '<div class="container my-5">';
        $card .= '<img src="/assets/img/' . $imageFileName . '" class="float-start me-4" alt="' . $productName . '" style="width: 250px; height: auto; border-radius: 0.375rem;" onerror="this.src=\'/assets/img/no-image.jpg\'">';
        $card .= '<div class="card-body ps-4">';
        $card .= '<h2 class="card-title">' . $productName . '</h2>';
        $card .= '<p class="card-text text-muted mb-4"><i class="bi bi-calendar-week me-2"></i> Даты по запросу | <i class="bi bi-clock me-2"></i> ' . $duration . '</p>';
        $card .= '<p class="card-text flex-grow-1">' . $productDesc . '</p>';
        $card .= '<div class="d-flex justify-content-between align-items-center mt-4">';
        $card .= '<h3 class="text-success"><i class="bi bi-currency-ruble"></i> ' . $productPrice . '</h3>';
        $card .= '<small class="text-body-secondary"><i class="bi bi-star-fill text-warning"></i> 4.9 (15 отзывов)</small>';
        $card .= '</div>';
        $card .= '<div class="mt-auto mt-3">';
        $card .= '<form class="d-inline" action="/basket" method="POST">';
        $card .= '<input type="hidden" name="id" value="' . $productId . '" />';
        $card .= '<button type="submit" class="btn btn-success btn-lg me-3">Забронировать тур</button>';
        $card .= '</form>';
        $card .= '<a href="javascript:history.back()" role="button" class="btn btn-outline-primary btn-lg">Вернуться к выбору</a>';
        $card .= '</div></div><div style="clear: both;"></div></div>';
        
        // Блок отзывов
        $card .= '<div class="container my-5 pt-4 border-top">';
        $card .= '<h4 class="mb-4"><i class="bi bi-chat-square-text me-2"></i>Отзывы (' . $reviewCount . ')</h4>';
        $card .= $reviewsHtml;
        $card .= $reviewForm;
        $card .= '</div>';

        $title = 'Тур: ' . $productName;
        return sprintf($template, $title, $card);
    }
}