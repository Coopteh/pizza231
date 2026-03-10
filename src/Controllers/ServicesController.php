<?php
namespace App\Controllers;

use App\Models\Policy;
use App\Views\BaseTemplate;

class ServicesController extends Controller
{
    public function index()
    {
        $policyModel = new Policy();
        $policies = $policyModel->getAll();
        
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
        
        $content = "{$alerts}<h1 class='mb-4'>📋 Наши страховые продукты</h1><div class='row'>";
        
        foreach ($policies as $policy) {
            $content .= "
                <div class='col-md-4 mb-4'>
                    <div class='card h-100 p-4'>
                        <h3>{$policy['name']}</h3>
                        <p class='text-muted'>{$policy['description']}</p>
                        <p class='fw-bold text-primary'>от {$policy['price']} ₽</p>
                        <a href='/Insurance221/quote?type={$policy['id']}' class='btn btn-ins'>Рассчитать</a>
                    </div>
                </div>
            ";
        }
        
        $content .= "</div>";
        echo sprintf($template, "Услуги - Страховая Компания", $content);
    }
}