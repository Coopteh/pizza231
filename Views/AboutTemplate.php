<?php
namespace Views;
use Lib\Language;
class AboutTemplate extends BaseTemplate
{
    public static function getTemplate(): string
    {
        $template = parent::getTemplate();
        $title = Language::get('about_title') . ' - ' . Language::get('site_name');
        
        // Стили можно оставить похожими, но поменять акцентный цвет на красный/темный
        $customStyles = '
        <style>
        .feature-box {
            background: #f7fafc; border-radius: 8px; padding: 1.5rem;
            text-align: center; height: 100%; transition: transform 0.3s ease;
            border: 1px solid #e2e8f0;
        }
        .feature-box:hover { transform: translateY(-5px); border-color: #e53e3e; }
        .feature-icon {
            font-size: 2rem; font-weight: 700; margin-bottom: 1rem;
            display: inline-block; background: #fff5f5; width: 70px; height: 70px;
            line-height: 70px; border-radius: 8px; color: #e53e3e;
        }
        .hero-section {
            background: #1a1a1a; color: white; border-radius: 8px;
            padding: 3rem 2rem; margin-bottom: 3rem; border: 1px solid #e2e8f0;
        }
        .contact-block {
            background: #e53e3e; color: white; border-radius: 8px;
            padding: 2.5rem; text-align: center; margin: 3rem 0;
        }
        .contact-block h4, .contact-block p { color: white; }
        .stats-grid {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem; margin: 2rem 0;
        }
        .stat-item { text-align: center; padding: 1.5rem; background: #f7fafc; border-radius: 8px; }
        .stat-number { font-size: 2.5rem; font-weight: 700; color: #e53e3e; display: block; }
        </style>';

        $content = $customStyles . '
        <section class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="hero-section text-center">
                        <span class="badge bg-danger bg-opacity-10 text-danger mb-3 px-3 py-2 rounded-pill">' . Language::get('about_founded') . '</span>
                        <h2 class="display-5 fw-bold text-white mb-4">' . Language::get('site_name') . '</h2>
                        <p class="lead text-white-50 mx-auto" style="max-width: 800px;">' . Language::get('about_subtitle') . '</p>
                    </div>
                </div>
            </div>
            <div class="stats-grid">
                <div class="stat-item"><span class="stat-number">8+</span><span class="stat-label">' . Language::get('about_stats_years') . '</span></div>
                <div class="stat-item"><span class="stat-number">1500+</span><span class="stat-label">' . Language::get('about_stats_graduates') . '</span></div>
                <div class="stat-item"><span class="stat-number">100+</span><span class="stat-label">' . Language::get('about_stats_specialties') . '</span></div>
                <div class="stat-item"><span class="stat-number">98%</span><span class="stat-label">' . Language::get('about_stats_employment') . '</span></div>
            </div>
            <div class="row g-4 mb-5">
                <div class="col-12 text-center mb-3"><h3 class="fw-bold">' . Language::get('about_why_title') . '</h3></div>
                <div class="col-md-6 col-lg-3"><div class="feature-box"><div class="feature-icon">🏋️</div><h5 class="fw-bold">' . Language::get('about_feature_education') . '</h5><p class="text-muted small mb-0">' . Language::get('about_feature_education_desc') . '</p></div></div>
                <div class="col-md-6 col-lg-3"><div class="feature-box"><div class="feature-icon">🏃</div><h5 class="fw-bold">' . Language::get('about_feature_practice') . '</h5><p class="text-muted small mb-0">' . Language::get('about_feature_practice_desc') . '</p></div></div>
                <div class="col-md-6 col-lg-3"><div class="feature-box"><div class="feature-icon">👶</div><h5 class="fw-bold">' . Language::get('about_feature_employment') . '</h5><p class="text-muted small mb-0">' . Language::get('about_feature_employment_desc') . '</p></div></div>
                <div class="col-md-6 col-lg-3"><div class="feature-box"><div class="feature-icon">🛡️</div><h5 class="fw-bold">' . Language::get('about_feature_diploma') . '</h5><p class="text-muted small mb-0">' . Language::get('about_feature_diploma_desc') . '</p></div></div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="contact-block">
                        <h3 class="fw-bold mb-3">' . Language::get('about_admissions') . '</h3>
                        <p class="mb-4 opacity-75">' . Language::get('about_admissions_desc') . '</p>
                        <a href="tel:+79990000000" class="phone-display" style="color:white; font-size:2rem; font-weight:700;">+7 (999) 000-00-00</a>
                        <a href="mailto:info@ironlegend.gym" class="btn btn-light btn-lg px-5 fw-bold mt-2">' . Language::get('about_write_us') . '</a>
                    </div>
                </div>
            </div>
        </section>';

        return str_replace(['{{TITLE}}', '{{CONTENT}}'], [$title, $content], $template);
    }
}