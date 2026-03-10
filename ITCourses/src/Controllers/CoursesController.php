<?php
namespace App\Controllers;
use App\Core\BaseTemplate;
use App\Models\Course;

class CoursesController {
    public function index(): void {
        $courseModel = new Course();
        $courses = $courseModel->getAll();

        $coursesHtml = '';
        foreach ($courses as $course) {
            $coursesHtml .= <<<HTML
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">{$course['title']}</h5>
                        <p class="card-text text-muted">{$course['description']}</p>
                        <p class="fw-bold text-primary">{$course['price']} ₽</p>
                        <p class="text-secondary"><small>⏱ {$course['duration']} недель</small></p>
                        <a href="enrollment" class="btn btn-outline-primary w-100">Записаться</a>
                    </div>
                </div>
            </div>
            HTML;
        }

        $content = "<h2 class=\"mb-4\">Наши курсы</h2><div class=\"row\">{$coursesHtml}</div>";
        $template = BaseTemplate::getTemplate();
        echo sprintf($template, "Курсы - " . APP_NAME, BASE_URL, BASE_URL, BASE_URL, BASE_URL, BASE_URL, BASE_URL, BASE_URL, $content, BASE_URL);
    }
}