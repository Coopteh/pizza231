<?php
namespace App\Controllers;

use App\Models\Claim;
use App\Views\BaseTemplate;

class ClaimsController extends Controller
{
    public function index()
    {
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
        
        $content = "
            {$alerts}
            <h1 class='mb-4'>📝 Подать заявление на страховую выплату</h1>
            <form method='POST' action='/Insurance221/claims' class='card p-4'>
                <div class='mb-3'>
                    <label class='form-label'>Номер полиса</label>
                    <input type='text' name='policy_number' class='form-control' value='" . htmlspecialchars($oldInput['policy_number'] ?? '') . "' required>
                </div>
                <div class='mb-3'>
                    <label class='form-label'>Описание случая</label>
                    <textarea name='description' class='form-control' rows='4' required>" . htmlspecialchars($oldInput['description'] ?? '') . "</textarea>
                </div>
                <div class='mb-3'>
                    <label class='form-label'>Дата события</label>
                    <input type='date' name='event_date' class='form-control' value='" . htmlspecialchars($oldInput['event_date'] ?? '') . "' required>
                </div>
                <div class='mb-3'>
                    <label class='form-label'>Сумма ущерба (₽)</label>
                    <input type='number' name='damage_amount' class='form-control' value='" . htmlspecialchars($oldInput['damage_amount'] ?? '') . "' required>
                </div>
                <button type='submit' class='btn btn-ins'>Подать заявление</button>
            </form>
        ";
        echo sprintf($template, "Заявления - Страховая Компания", $content);
    }
    
    public function store()
    {
        $data = [
            'policy_number' => $_POST['policy_number'] ?? '',
            'description' => $_POST['description'] ?? '',
            'event_date' => $_POST['event_date'] ?? '',
            'damage_amount' => $_POST['damage_amount'] ?? 0,
            'status' => 'new',
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        // Валидация
        $errors = [];
        if (empty($data['policy_number'])) $errors[] = 'Введите номер полиса';
        if (empty($data['description'])) $errors[] = 'Опишите страховой случай';
        if (empty($data['event_date'])) $errors[] = 'Укажите дату события';
        if ($data['damage_amount'] <= 0) $errors[] = 'Сумма ущерба должна быть больше 0';
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $_POST;
            header('Location: /Insurance221/claims');
            exit;
        }
        
        $claimModel = new Claim();
        $claimId = $claimModel->create($data);
        
        if ($claimId) {
            $_SESSION['success'] = 'Заявление #' . $claimId . ' принято в обработку';
        } else {
            $_SESSION['errors'] = ['Не удалось создать заявление'];
        }
        
        header('Location: /Insurance221/claims');
        exit;
    }
}