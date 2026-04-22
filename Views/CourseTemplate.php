<?php

namespace Views;

use Lib\DataLoader;
use Lib\Language;

class CourseTemplate extends BaseTemplate
{
    public static function renderCourse(int $courseId): string
    {
        $template = parent::getTemplate();
        $course = DataLoader::loadCourse($courseId);
        $lang = Language::getCurrentLang();

        // Вспомогательная функция для получения текста на нужном языке
        $getText = function ($field, $default = '') use ($lang) {
            if (is_array($field)) {
                return $field[$lang] ?? $field['ru'] ?? $default;
            }
            return $field;
        };

        if (!$course) {
            http_response_code(404);
            return '<div class="container py-5"><h1 style="color: var(--text);">' . Language::get('course_not_found') . '</h1><a href="/courses" class="btn btn-primary">' . Language::get('course_back_list') . '</a></div>';
        }

        $title = $getText($course['title']) . ' — ' . Language::get('site_name');

        $customStyles = '
<style>
/* 🌙 ТЁМНАЯ ТЕМА: Улучшение рендеринга текста */
* {
-webkit-font-smoothing: antialiased;
-moz-osx-font-smoothing: grayscale;
text-rendering: optimizeLegibility;
}
/* 🌙 ТЁМНАЯ ТЕМА: Стили для страницы курса */
.course-hero {
background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
border-radius: 12px;
padding: 3rem 2.5rem;
color: white;
margin-bottom: 2.5rem;
position: relative;
overflow: hidden;
transition: background 0.3s ease;
text-shadow: 0 1px 2px rgba(0,0,0,0.1);
}
.course-hero::after {
content: "' . $course['icon'] . '";
position: absolute;
right: -30px;
bottom: -50px;
font-size: 15rem;
opacity: 0.1;
font-weight: 900;
color: #fff;
}
.course-title {
font-size: 2.25rem;
font-weight: 700;
margin-bottom: 1rem;
letter-spacing: -0.02em;
}
.course-description {
font-size: 1.1rem;
opacity: 0.95;
margin-bottom: 1.5rem;
max-width: 700px;
line-height: 1.6;
}
.course-price {
font-size: 2rem;
font-weight: 700;
margin: 1rem 0;
}
.badge-level {
background: rgba(255,255,255,0.2);
color: #fff;
padding: 0.35rem 1rem;
border-radius: 50px;
font-size: 0.85rem;
font-weight: 600;
backdrop-filter: blur(4px);
}
.features-list {
list-style: none;
padding: 0;
margin: 0;
}
.features-list li {
padding: 0.85rem 0;
border-bottom: 1px solid var(--border);
display: flex;
align-items: flex-start;
gap: 0.75rem;
font-size: 1rem;
color: var(--text);
font-weight: 500;
transition: border-color 0.3s ease, color 0.3s ease;
}
.features-list li::before {
content: "✓";
color: var(--primary);
font-weight: 700;
flex-shrink: 0;
}
.cta-section {
background: var(--surface-hover);
border-radius: 12px;
padding: 2rem;
border: 1px solid var(--border);
transition: all 0.3s ease;
}
.btn-enroll {
background: var(--primary);
border: none;
padding: 0.875rem 2rem;
font-size: 1rem;
font-weight: 600;
border-radius: 8px;
color: white;
text-decoration: none;
display: inline-flex;
align-items: center;
justify-content: center;
width: 100%;
margin-bottom: 0.75rem;
transition: background 0.3s ease;
text-shadow: 0 1px 2px rgba(0,0,0,0.1);
}
.btn-enroll:hover {
background: var(--primary-dark);
color: white;
text-decoration: none;
}
.btn-enroll.secondary {
background: var(--surface);
color: var(--primary);
border: 2px solid var(--primary);
}
.btn-enroll.secondary:hover {
background: var(--badge-bg);
}
.back-link {
color: var(--text-muted);
text-decoration: none;
font-weight: 500;
margin-bottom: 1.5rem;
display: inline-flex;
align-items: center;
gap: 0.5rem;
transition: color 0.2s;
}
.back-link:hover {
color: var(--primary);
text-decoration: none;
}
.info-badge {
display: inline-block;
background: var(--badge-bg);
color: var(--badge-text);
padding: 0.25rem 0.75rem;
border-radius: 6px;
font-size: 0.85rem;
font-weight: 600;
margin-right: 0.5rem;
margin-bottom: 0.5rem;
}
.card {
background: var(--surface);
border-color: var(--border);
transition: all 0.3s ease;
}
/* 🌙 ТЁМНАЯ ТЕМА: Улучшение читаемости текста */
.fw-bold, strong {
font-weight: 600 !important;
letter-spacing: -0.01em;
}
.text-muted {
color: var(--text-muted) !important;
font-weight: 400;
}
/* 🌙 ТЁМНАЯ ТЕМА: Улучшение контраста для темной темы */
[data-theme="dark"] .course-hero {
text-shadow: 0 2px 4px rgba(0,0,0,0.3);
}
[data-theme="dark"] .features-list li {
font-weight: 500;
}
[data-theme="dark"] .text-muted {
color: var(--text-muted) !important;
opacity: 0.9;
}
@media (max-width: 768px) {
.course-hero {
padding: 2rem 1.5rem;
}
.course-title {
font-size: 1.75rem;
}
}
@media (max-width: 576px) {
.course-hero {
padding: 1.5rem 1rem;
}
.course-title {
font-size: 1.5rem;
}
.course-price {
font-size: 1.5rem;
}
}
</style>';

        $featuresHtml = '';
        foreach ($course['features'] as $feature) {
            $featuresHtml .= '<li>' . htmlspecialchars($getText($feature)) . '</li>';
        }

        $formatHtml = '';
        foreach ($course['format'] as $fmt) {
            $formatHtml .= '<span class="info-badge">' . htmlspecialchars($getText($fmt)) . '</span>';
        }

        $certificateText = $course['certificate'] ? Language::get('issued') : Language::get('not_issued');
        $jobAssistanceText = $course['job_assistance'] ? Language::get('assistance_provided') : Language::get('assistance_not_provided');
        $certificateCheck = $course['certificate'] ? '✓ ' . Language::get('course_certificate') . '<br>' : '';
        $jobAssistanceCheck = $course['job_assistance'] ? '✓ ' . Language::get('course_job_assistance') : '';

        $content = $customStyles . '
<section class="container py-5">
    <a href="/courses" class="back-link">
        <span>←</span> ' . Language::get('course_back') . '
    </a>
    <div class="course-hero">
        <span class="badge-level">' . htmlspecialchars($getText($course['level'])) . '</span>
        <h1 class="course-title">' . htmlspecialchars($getText($course['title'])) . '</h1>
        <p class="course-description">' . htmlspecialchars($getText($course['description'])) . '</p>
        <div class="course-price">' . htmlspecialchars($course['price_from']) . '</div>
        <div class="opacity-75 mb-3">' . htmlspecialchars($getText($course['duration'])) . '</div>
        <div>' . $formatHtml . '</div>
    </div>
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <h3 class="fw-bold mb-4" style="color: var(--text);">' . Language::get('course_program') . '</h3>
                <ul class="features-list">' . $featuresHtml . '</ul>
            </div>
            <div class="card border-0 shadow-sm rounded-4 p-4 mt-4">
                <h4 class="fw-bold mb-3" style="color: var(--text);">' . Language::get('course_details') . '</h4>
                <div class="row g-3">
                    <div class="col-md-6">
                        <strong style="color: var(--text);">' . Language::get('course_level') . ':</strong>
                        <p class="text-muted">' . htmlspecialchars($getText($course['level'])) . '</p>
                    </div>
                    <div class="col-md-6">
                        <strong style="color: var(--text);">' . Language::get('course_format') . ':</strong>
                        <p class="text-muted">' . implode(', ', array_map(fn ($f) => $getText($f), $course['format'])) . '</p>
                    </div>
                    <div class="col-md-6">
                        <strong style="color: var(--text);">' . Language::get('course_certificate') . ':</strong>
                        <p class="text-muted">' . $certificateText . '</p>
                    </div>
                    <div class="col-md-6">
                        <strong style="color: var(--text);">' . Language::get('course_job_assistance') . ':</strong>
                        <p class="text-muted">' . $jobAssistanceText . '</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="cta-section">
                <h4 class="fw-bold mb-3" style="color: var(--text);">' . Language::get('course_enroll') . '</h4>
                <p class="text-muted mb-4">' . Language::get('course_ask_question') . '</p>
                <form method="POST" action="/cart/add" class="mb-3">
                    <input type="hidden" name="courseId" value="' . $course['id'] . '">
                    <button type="submit" class="btn-enroll">
                        ' . Language::get('course_enroll_btn') . '
                    </button>
                </form>
                <a href="mailto:info@kemt.ru" class="btn-enroll secondary">
                    ' . Language::get('course_ask_question') . '
                </a>
                <p class="small text-muted mt-4 mb-0">
                    ' . $certificateCheck . '
                    ' . $jobAssistanceCheck . '
                </p>
            </div>
        </div>
    </div>
</section>';

        return str_replace(['{{TITLE}}', '{{CONTENT}}'], [$title, $content], $template);
    }
}
