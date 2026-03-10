<?php
namespace App\Controllers;
use App\Core\BaseTemplate;
use App\Models\Course;
use App\Models\Student;

class EnrollmentController {
    public function create(): void {
        $courseModel = new Course();
        $courses = $courseModel->getAll();
        $message = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $studentModel = new Student();
            $data = [
                'name' => $_POST['name'] ?? '',
                'email' => $_POST['email'] ?? '',
                'phone' => $_POST['phone'] ?? '',
                'course_id' => $_POST['course_id'] ?? ''
            ];

            if ($studentModel->create($data)) {
                $message = '<div class="alert alert-success">✅ Заявка успешно отправлена! Менеджер свяжется с вами.</div>';
            } else {
                $message = '<div class="alert alert-danger">❌ Ошибка при отправке. Попробуйте позже.</div>';
            }
        }

        $options = '';
        foreach ($courses as $course) {
            $options .= "<option value=\"{$course['id']}\">{$course['title']} - {$course['price']} ₽</option>";
        }

        $content = <<<HTML
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card border-0 shadow">
                    <div class="card-body p-4">
                        <h3 class="card-title mb-4">Запись на курс</h3>
                        {$message}
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Ваше имя *</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email *</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Телефон *</label>
                                <input type="tel" name="phone" class="form-control" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Выберите курс *</label>
                                <select name="course_id" class="form-select" required>
                                    <option value="">-- Выберите курс --</option>
                                    {$options}
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Отправить заявку</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        HTML;

        $template = BaseTemplate::getTemplate();
        echo sprintf($template, "Запись - " . APP_NAME, BASE_URL, BASE_URL, BASE_URL, BASE_URL, BASE_URL, BASE_URL, BASE_URL, $content, BASE_URL);
    }
}