<?php
namespace Views;
use Lib\DataLoader;
// 🌐 LANG: Добавлен импорт Language
use Lib\Language;

class CoursesTemplate extends BaseTemplate
{
    public static function getTemplate(): string
    {
        $template = parent::getTemplate();
        // 🌐 LANG: Заголовок с переводом
        $title = Language::get('courses_title') . ' — ' . Language::get('site_name');
        $courses = DataLoader::loadCourses();
        
        $customStyles = '
<style>
.page-header {
text-align: center;
margin-bottom: 3rem;
padding: 2rem 0;
}
.page-title {
font-size: 2.5rem;
font-weight: 700;
color: #2d3748;
margin-bottom: 1rem;
}
.page-subtitle {
font-size: 1.1rem;
color: #718096;
max-width: 600px;
margin: 0 auto;
}
.courses-grid {
display: grid;
grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
gap: 1.5rem;
margin-bottom: 3rem;
}
.course-card {
background: #ffffff;
border-radius: 12px;
border: 1px solid #e2e8f0;
padding: 1.75rem;
display: flex;
flex-direction: column;
transition: box-shadow 0.2s, border-color 0.2s;
text-decoration: none;
color: inherit;
}
.course-card:hover {
box-shadow: 0 10px 25px rgba(0,0,0,0.08);
border-color: #2c5282;
text-decoration: none;
color: inherit;
}
.icon-box {
width: 80px;
height: 80px;
display: flex;
align-items: center;
justify-content: center;
border-radius: 8px;
font-size: 1.5rem;
font-weight: 700;
margin: 0 auto 1.5rem auto;
background: #ebf4ff;
color: #2c5282;
transition: all 0.3s ease;
}
.course-card:hover .icon-box {
background: #2c5282;
color: #fff;
}
.course-title {
font-size: 1.25rem;
font-weight: 700;
color: #2d3748;
margin-bottom: 0.5rem;
}
.course-description {
color: #718096;
font-size: 0.95rem;
line-height: 1.5;
margin-bottom: 1.25rem;
flex-grow: 1;
}
.course-price {
font-size: 1.35rem;
font-weight: 700;
color: #2d3748;
margin-bottom: 1rem;
}
.btn-card {
padding: 0.625rem 1rem;
font-size: 0.9rem;
font-weight: 500;
border-radius: 8px;
text-align: center;
text-decoration: none;
transition: background 0.2s;
border: none;
cursor: pointer;
display: inline-block;
}
.btn-primary {
background: #2c5282;
color: white;
}
.btn-primary:hover {
background: #1a365d;
color: white;
text-decoration: none;
}
.btn-outline {
background: transparent;
color: #2c5282;
border: 2px solid #2c5282;
}
.btn-outline:hover {
background: #ebf4ff;
text-decoration: none;
}
.level-badge {
display: inline-block;
background: #f7fafc;
color: #4a5568;
padding: 0.25rem 0.75rem;
border-radius: 6px;
font-size: 0.8rem;
font-weight: 500;
margin-bottom: 1rem;
}
</style>';
        
        $coursesHtml = '';
        foreach ($courses as $course) {
            $coursesHtml .= '
<a href="/course/' . $course['id'] . '" class="course-card">
<div class="text-center mb-3">
<div class="icon-box">' . htmlspecialchars($course['icon']) . '</div>
</div>
<span class="level-badge text-center">' . htmlspecialchars($course['level']) . '</span>
<h3 class="course-title text-center">' . htmlspecialchars($course['title']) . '</h3>
<p class="course-description text-center">' . htmlspecialchars($course['description']) . '</p>
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
<section class="container py-5">
<div class="page-header">
<!-- 🌐 LANG: Заголовки с переводом -->
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