<?php
namespace Views;
use Lib\DataLoader;
// 🌐 LANG: Добавлен импорт Language
use Lib\Language;

class CourseTemplate extends BaseTemplate
{
    public static function renderCourse(int $courseId): string
    {
        $template = parent::getTemplate();
        $course = DataLoader::loadCourse($courseId);
        
        if (!$course) {
            http_response_code(404);
            // 🌐 LANG: Сообщение об ошибке с переводом
            return '<div class="container py-5"><h1>' . Language::get('course_not_found') . '</h1><a href="/courses">' . Language::get('course_back_list') . '</a></div>';
        }
        
        // 🌐 LANG: Заголовок с переводом
        $title = $course['title'] . ' — ' . Language::get('site_name');
        
        $customStyles = '
<style>
.course-hero {
background: linear-gradient(135deg, #2c5282 0%, #1a365d 100%);
border-radius: 12px;
padding: 3rem 2.5rem;
color: white;
margin-bottom: 2.5rem;
position: relative;
overflow: hidden;
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
}
.course-description {
font-size: 1.1rem;
opacity: 0.95;
margin-bottom: 1.5rem;
max-width: 700px;
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
font-weight: 500;
}
.features-list {
list-style: none;
padding: 0;
margin: 0;
}
.features-list li {
padding: 0.85rem 0;
border-bottom: 1px solid #e5e7eb;
display: flex;
align-items: flex-start;
gap: 0.75rem;
font-size: 1rem;
color: #374151;
}
.features-list li::before {
content: "✓";
color: #2c5282;
font-weight: 700;
flex-shrink: 0;
}
.cta-section {
background: #f9fafb;
border-radius: 12px;
padding: 2rem;
border: 1px solid #e5e7eb;
}
.btn-enroll {
background: #2c5282;
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
}
.btn-enroll:hover {
background: #1a365d;
color: white;
text-decoration: none;
}
.btn-enroll.secondary {
background: #ffffff;
color: #2c5282;
border: 2px solid #2c5282;
}
.btn-enroll.secondary:hover {
background: #eff6ff;
}
.back-link {
color: #6b7280;
text-decoration: none;
font-weight: 500;
margin-bottom: 1.5rem;
display: inline-flex;
align-items: center;
gap: 0.5rem;
}
.back-link:hover {
color: #2c5282;
text-decoration: none;
}
.info-badge {
display: inline-block;
background: #ebf4ff;
color: #2c5282;
padding: 0.25rem 0.75rem;
border-radius: 6px;
font-size: 0.85rem;
font-weight: 500;
margin-right: 0.5rem;
margin-bottom: 0.5rem;
}
</style>';
        
        $featuresHtml = '';
        foreach ($course['features'] as $feature) {
            $featuresHtml .= '<li>' . htmlspecialchars($feature) . '</li>';
        }
        
        $formatHtml = '';
        foreach ($course['format'] as $fmt) {
            $formatHtml .= '<span class="info-badge">' . htmlspecialchars($fmt) . '</span>';
        }
        
        // 🌐 LANG: Текст сертификата и трудоустройства с переводом
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
<span class="badge-level">' . htmlspecialchars($course['level']) . '</span>
<h1 class="course-title">' . htmlspecialchars($course['title']) . '</h1>
<p class="course-description">' . htmlspecialchars($course['description']) . '</p>
<div class="course-price">' . htmlspecialchars($course['price_from']) . '</div>
<div class="opacity-75 mb-3">' . htmlspecialchars($course['duration']) . '</div>
<div>' . $formatHtml . '</div>
</div>
<div class="row g-4">
<div class="col-lg-8">
<div class="card border-0 shadow-sm rounded-4 p-4">
<h3 class="fw-bold mb-4">' . Language::get('course_program') . '</h3>
<ul class="features-list">' . $featuresHtml . '</ul>
</div>
<div class="card border-0 shadow-sm rounded-4 p-4 mt-4">
<h4 class="fw-bold mb-3">' . Language::get('course_details') . '</h4>
<div class="row g-3">
<div class="col-md-6">
<strong>' . Language::get('course_level') . ':</strong>
<p class="text-muted">' . htmlspecialchars($course['level']) . '</p>
</div>
<div class="col-md-6">
<strong>' . Language::get('course_format') . ':</strong>
<p class="text-muted">' . implode(', ', $course['format']) . '</p>
</div>
<div class="col-md-6">
<strong>' . Language::get('course_certificate') . ':</strong>
<p class="text-muted">' . $certificateText . '</p>
</div>
<div class="col-md-6">
<strong>' . Language::get('course_job_assistance') . ':</strong>
<p class="text-muted">' . $jobAssistanceText . '</p>
</div>
</div>
</div>
</div>
<div class="col-lg-4">
<div class="cta-section">
<h4 class="fw-bold mb-3">' . Language::get('course_enroll') . '</h4>
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