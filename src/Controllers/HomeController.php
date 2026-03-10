<?php
namespace App\Controllers;

use App\Views\BaseTemplate;

class HomeController extends Controller
{
    public function index()
    {
        $template = BaseTemplate::getTemplate();
        
        $success = $_SESSION['success'] ?? '';
        $errors = $_SESSION['errors'] ?? [];
        unset($_SESSION['success'], $_SESSION['errors']);
        
        $alerts = '';
        if ($success) {
            $alerts .= "<div class='alert alert-success'>{$success}</div>";
        }
        if (!empty($errors)) {
            foreach ($errors as $error) {
                $alerts .= "<div class='alert alert-danger'>{$error}</div>";
            }
        }
        
        $content = "
            {$alerts}
            <div class='text-center py-5'>
                <h1 class='display-4 fw-bold text-primary'>🛡️ Надёжная защита для вас и вашего бизнеса</h1>
                <p class='lead text-muted mt-3'>Более 15 лет на рынке страховых услуг</p>
                <div class='row mt-5'>
                    <div class='col-md-4'>
                        <div class='card p-4'>
                            <h3>🚗 Автострахование</h3>
                            <p>Защита вашего автомобиля от любых рисков</p>
                        </div>
                    </div>
                    <div class='col-md-4'>
                        <div class='card p-4'>
                            <h3>🏠 Имущество</h3>
                            <p>Страхование недвижимости и домашнего имущества</p>
                        </div>
                    </div>
                    <div class='col-md-4'>
                        <div class='card p-4'>
                            <h3>👨‍👩‍👧‍👦 Жизнь и здоровье</h3>
                            <p>Забота о вас и ваших близких</p>
                        </div>
                    </div>
                </div>
                <a href='/Insurance221/services' class='btn btn-ins btn-lg mt-5'>Наши услуги</a>
            </div>
        ";
        
        echo sprintf($template, "Главная - Страховая Компания", $content);
    }
}