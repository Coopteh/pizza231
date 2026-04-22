<?php

namespace Views;

use Lib\DataLoader;
use Lib\Language;

class CoursesTemplate extends BaseTemplate
{
    public static function getTemplate(): string
    {
        $template = parent::getTemplate();
        $title = Language::get('courses_title') . ' — ' . Language::get('site_name');
        $courses = DataLoader::loadCourses();
        $lang = Language::getCurrentLang();

        // Вспомогательная функция для получения текста на нужном языке
        $getText = function ($field, $default = '') use ($lang) {
            if (is_array($field)) {
                return $field[$lang] ?? $field['ru'] ?? $default;
            }
            return $field;
        };

        $customStyles = '
<style>
/* 🌙 ТЁМНАЯ ТЕМА: Стили для страницы курсов */
.page-header {
text-align: center;
margin-bottom: 2.5rem;
padding: 1.5rem 0;
}
.page-title {
font-size: 2rem;
font-weight: 700;
color: var(--text);
margin-bottom: 0.75rem;
transition: color 0.3s ease;
}
.page-subtitle {
font-size: 1rem;
color: var(--text-muted);
max-width: 600px;
margin: 0 auto;
line-height: 1.5;
transition: color 0.3s ease;
}
.courses-grid {
display: grid;
grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
gap: 1.25rem;
margin-bottom: 2.5rem;
}
.course-card {
background: var(--surface);
border-radius: 12px;
border: 1px solid var(--border);
padding: 1.5rem;
display: flex;
flex-direction: column;
transition: box-shadow 0.2s, border-color 0.2s, background 0.3s ease;
text-decoration: none;
color: inherit;
}
.course-card:hover {
box-shadow: var(--shadow-lg);
border-color: var(--primary);
text-decoration: none;
color: inherit;
transform: translateY(-3px);
}
.icon-box {
width: 70px;
height: 70px;
display: flex;
align-items: center;
justify-content: center;
border-radius: 8px;
font-size: 1.25rem;
font-weight: 700;
margin: 0 auto 1.25rem auto;
background: var(--badge-bg);
color: var(--primary);
transition: all 0.3s ease;
}
.course-card:hover .icon-box {
background: var(--primary);
color: #fff;
}
.course-title {
font-size: 1.1rem;
font-weight: 700;
color: var(--text);
margin-bottom: 0.5rem;
line-height: 1.3;
transition: color 0.3s ease;
}
.course-description {
color: var(--text-muted);
font-size: 0.9rem;
line-height: 1.5;
margin-bottom: 1rem;
flex-grow: 1;
transition: color 0.3s ease;
}
.course-price {
font-size: 1.25rem;
font-weight: 700;
color: var(--text);
margin-bottom: 1rem;
transition: color 0.3s ease;
}
.btn-card {
padding: 0.625rem 1rem;
font-size: 0.85rem;
font-weight: 500;
border-radius: 8px;
text-align: center;
text-decoration: none;
transition: background 0.2s;
border: none;
cursor: pointer;
display: inline-block;
min-height: 44px;
display: inline-flex;
align-items: center;
justify-content: center;
}
.btn-primary {
background: var(--primary);
color: white;
}
.btn-primary:hover {
background: var(--primary-dark);
color: white;
text-decoration: none;
}
.btn-outline {
background: transparent;
color: var(--primary);
border: 2px solid var(--primary);
}
.btn-outline:hover {
background: var(--badge-bg);
text-decoration: none;
}
.level-badge {
display: inline-block;
background: var(--surface-hover);
color: var(--text-muted);
padding: 0.25rem 0.75rem;
border-radius: 6px;
font-size: 0.75rem;
font-weight: 500;
margin-bottom: 0.75rem;
}
@media (max-width: 768px) {
.courses-grid {
grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
gap: 1rem;
}
.page-title {
font-size: 1.75rem;
}
}
@media (max-width: 576px) {
.courses-grid {
grid-template-columns: 1fr;
gap: 1rem;
}
.page-header {
padding: 1rem 0;
margin-bottom: 1.5rem;
}
.page-title {
font-size: 1.5rem;
}
.page-subtitle {
font-size: 0.9rem;
}
.course-card {
padding: 1.25rem;
}
.icon-box {
width: 60px;
height: 60px;
font-size: 1.1rem;
}
.course-title {
font-size: 1rem;
}
.course-price {
font-size: 1.1rem;
}
.btn-card {
padding: 0.5rem 0.75rem;
font-size: 0.8rem;
}
.d-flex.gap-2 {
flex-direction: column;
gap: 0.5rem !important;
}
.d-flex.gap-2 .btn-card {
width: 100%;
}
}
</style>';

        $coursesHtml = '';
        foreach ($courses as $course) {
            $coursesHtml .= '
<a href="/course/' . $course['id'] . '" class="course-card">
    <div class="text-center mb-3">
        <div class="icon-box">' . htmlspecialchars($course['icon']) . '</div>
    </div>
    <span class="level-badge text-center">' . htmlspecialchars($getText($course['level'])) . '</span>
    <h3 class="course-title text-center">' . htmlspecialchars($getText($course['title'])) . '</h3>
    <p class="course-description text-center">' . htmlspecialchars($getText($course['description'])) . '</p>
    <div class="course-price text-center">' . htmlspecialchars($course['price_from']) . '</div>
    <div class="d-flex gap-2 mt-auto">
        <span class="btn-card btn-outline" style="flex:1">' . Language::get('course_details') . '</span>
        <form method="POST" action="/cart/add" style="flex:1" onclick="event.stopPropagation()">
            <input type="hidden" name="courseId" value="' . $course['id'] . '">
            <button type="submit" class="btn-card btn-primary" style="width:100%">' . Language::get('course_add_cart') . '</button>
        </form>
    </div>
</a>';
        }

        $content = $customStyles . '
<section class="container py-4 py-md-5">
    <div class="page-header">
        <h1 class="page-title">' . Language::get('courses_title') . '</h1>
        <p class="page-subtitle">' . Language::get('courses_subtitle') . '</p>
    </div>
    <div class="courses-grid">
        ' . $coursesHtml . '
    </div>
</section>';

        return str_replace(['{{TITLE}}', '{{CONTENT}}'], [$title, $content], $template);
    }
}
