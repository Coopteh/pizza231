<?php
namespace App\Controllers;
use App\Core\BaseTemplate;

class HomeController {
    public function index(): void {
        $content = <<<HTML
        <section class="hero bg-primary text-white rounded p-5 text-center">
            <h1 class="display-4">🚀 Освойте IT-профессию</h1>
            <p class="lead">Практические курсы от экспертов индустрии</p>
            <a href="courses" class="btn btn-light btn-lg mt-3">Выбрать курс</a>
        </section>
        
        <section class="row mt-5">
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <h3 class="card-title">📚 Практика</h3>
                        <p class="card-text">Реальные проекты для вашего портфолио</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <h3 class="card-title">👨‍🏫 Эксперты</h3>
                        <p class="card-text">Преподаватели из ведущих IT-компаний</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <h3 class="card-title">📜 Сертификат</h3>
                        <p class="card-text">Официальный документ об окончании</p>
                    </div>
                </div>
            </div>
        </section>
        HTML;

        $template = BaseTemplate::getTemplate();
        echo sprintf($template, APP_NAME, BASE_URL, BASE_URL, BASE_URL, BASE_URL, BASE_URL, BASE_URL, BASE_URL, $content, BASE_URL);
    }

    public function notFound(): void {
        $content = '<div class="text-center py-5"><h1>404</h1><p class="lead">Страница не найдена</p><a href="' . BASE_URL . '" class="btn btn-primary">На главную</a></div>';
        $template = BaseTemplate::getTemplate();
        echo sprintf($template, "404 - ITCourses", BASE_URL, BASE_URL, BASE_URL, BASE_URL, BASE_URL, BASE_URL, BASE_URL, $content, BASE_URL);
    }
}