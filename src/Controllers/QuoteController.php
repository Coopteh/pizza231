<?php
namespace App\Controllers;

use App\Models\Quote;
use App\Models\Policy;
use App\Views\BaseTemplate;

class QuoteController extends Controller
{
    public function create()
    {
        $policyModel = new Policy();
        $policies = $policyModel->getAll();
        
        $template = BaseTemplate::getTemplate();
        
        $success = $_SESSION['success'] ?? '';
        $errors = $_SESSION['errors'] ?? [];
        $oldInput = $_SESSION['old_input'] ?? [];
        unset($_SESSION['success'], $_SESSION['errors'], $_SESSION['old_input']);
        
        $alerts = '';
        if ($success) {
            $alerts .= "<div class='alert alert-success'>{$success}</div>";
        }
        if (!empty($errors)) {
            foreach ($errors as $error) {
                $alerts .= "<div class='alert alert-danger'>{$error}</div>";
            }
        }
        
        $policyOptions = '';
        foreach ($policies as $policy) {
            $selected = (isset($oldInput['policy_id']) && $oldInput['policy_id'] == $policy['id']) ? 'selected' : '';
            $policyOptions .= "<option value='{$policy['id']}' {$selected}>{$policy['name']} - от {$policy['price']} ₽</option>";
        }
        
        $content = "
            {$alerts}
            <h1 class='mb-4'>🧮 Рассчитать стоимость страхования</h1>
            <form method='POST' action='/Insurance221/quote' class='card p-4'>
                <div class='mb-3'>
                    <label class='form-label'>Тип страхования</label>
                    <select name='policy_id' class='form-control' required>
                        <option value=''>-- Выберите --</option>
                        {$policyOptions}
                    </select>
                </div>
                <div class='mb-3'>
                    <label class='form-label'>Ваше имя</label>
                    <input type='text' name='client_name' class='form-control' value='" . htmlspecialchars($oldInput['client_name'] ?? '') . "' required>
                </div>
                <div class='mb-3'>
                    <label class='form-label'>Телефон</label>
                    <input type='tel' name='client_phone' class='form-control' value='" . htmlspecialchars($oldInput['client_phone'] ?? '') . "' required>
                </div>
                <div class='mb-3'>
                    <label class='form-label'>Email</label>
                    <input type='email' name='client_email' class='form-control' value='" . htmlspecialchars($oldInput['client_email'] ?? '') . "' required>
                </div>
                <button type='submit' class='btn btn-ins'>Получить расчёт</button>
            </form>
        ";
        echo sprintf($template, "Расчёт стоимости - Страховая Компания ИС-221", $content);
    }
    
    public function store()
    {
        $data = [
            'policy_id' => $_POST['policy_id'] ?? null,
            'client_name' => $_POST['client_name'] ?? '',
            'client_phone' => $_POST['client_phone'] ?? '',
            'client_email' => $_POST['client_email'] ?? '',
            'created_at' => date('Y-m-d H:i:s')
        ];
        $errors = [];
        if (!$data['policy_id']) $errors[] = 'Выберите тип страхования';
        if (empty($data['client_name'])) $errors[] = 'Введите имя';
        if (empty($data['client_phone'])) $errors[] = 'Введите телефон';
        if (empty($data['client_email'])) $errors[] = 'Введите email';
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $_POST;
            header('Location: /Insurance221/quote');
            exit;
        }
        
        $quoteModel = new Quote();
        $quoteId = $quoteModel->create($data);
        
        if ($quoteId) {
            $_SESSION['success'] = 'Заявка #' . $quoteId . ' создана! Менеджер свяжется с вами.';
        } else {
            $_SESSION['errors'] = ['Не удалось создать заявку'];
        }
        
        header('Location: /Insurance221/quote');
        exit;
    }
}