<?php

namespace Views;

use Lib\Language;

class HomeTemplate extends BaseTemplate
{
    public static function getTemplate(): string
    {
        $template = parent::getTemplate();
        $title = Language::get('home_hero_title') . ' - ' . Language::get('site_name');

        $heroTitle = Language::get('home_hero_title');
        $heroSubtitle = Language::get('home_hero_subtitle');
        $featuresTitle = Language::get('home_features_title');
        $ctaButton = Language::get('home_cta');
        $feature1Title = Language::get('home_feature_1_title');
        $feature1Desc = Language::get('home_feature_1_desc');
        $feature2Title = Language::get('home_feature_2_title');
        $feature2Desc = Language::get('home_feature_2_desc');
        $feature3Title = Language::get('home_feature_3_title');
        $feature3Desc = Language::get('home_feature_3_desc');
        $statsStudents = Language::get('home_stats_students');
        $statsCourses = Language::get('home_stats_courses');
        $statsEmployment = Language::get('home_stats_employment');

        $customStyles = '
        <style>
            /* 🌙 ТЁМНАЯ ТЕМА: Hero секция */
            .hero-section {
                background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
                color: white;
                padding: 4rem 1rem;
                margin-bottom: 2rem;
                border-radius: 0 0 20px 20px;
                transition: background 0.3s ease;
            }
            
            .hero-title {
                font-size: 2.5rem;
                font-weight: 700;
                margin-bottom: 1.25rem;
                line-height: 1.2;
            }
            
            .hero-subtitle {
                font-size: 1.1rem;
                opacity: 0.95;
                max-width: 600px;
                margin: 0 auto 1.5rem auto;
                line-height: 1.5;
            }
            
            .hero-cta {
                background: #fff;
                color: var(--primary);
                padding: 1rem 2rem;
                font-size: 1rem;
                font-weight: 600;
                border-radius: 8px;
                text-decoration: none;
                display: inline-block;
                transition: all 0.3s ease;
                min-height: 48px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }
            
            .hero-cta:hover {
                background: var(--badge-bg);
                color: var(--primary);
                text-decoration: none;
                transform: translateY(-2px);
            }
            
            .feature-box {
                background: var(--surface);
                border-radius: 12px;
                padding: 1.5rem;
                text-align: center;
                height: 100%;
                transition: transform 0.3s ease, box-shadow 0.3s ease, background 0.3s ease;
                border: 1px solid var(--border);
            }
            
            .feature-box:hover {
                transform: translateY(-5px);
                background: var(--surface-hover);
                box-shadow: var(--shadow-lg);
                border-color: var(--primary);
            }
            
            .feature-icon {
                font-size: 2rem;
                font-weight: 700;
                margin-bottom: 1rem;
                display: inline-block;
                background: var(--badge-bg);
                width: 70px;
                height: 70px;
                line-height: 70px;
                border-radius: 12px;
                color: var(--primary);
                transition: all 0.3s ease;
            }
            
            .feature-box:hover .feature-icon {
                background: var(--primary);
                color: white;
            }
            
            .stats-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
                gap: 1.5rem;
                margin: 2rem 0;
            }
            
            .stat-item {
                text-align: center;
                padding: 1.5rem 1rem;
                background: var(--surface);
                border-radius: 12px;
                border: 1px solid var(--border);
                transition: all 0.3s ease;
            }
            
            .stat-item:hover {
                transform: translateY(-3px);
                box-shadow: var(--shadow-lg);
            }
            
            .stat-number {
                font-size: 2.5rem;
                font-weight: 700;
                color: var(--primary);
                display: block;
                transition: color 0.3s ease;
            }
            
            .stat-label {
                color: var(--text-muted);
                font-size: 0.9rem;
                line-height: 1.4;
                transition: color 0.3s ease;
            }
            
            @media (max-width: 768px) {
                .hero-section {
                    padding: 3rem 1rem;
                    border-radius: 0 0 15px 15px;
                }
                .hero-title {
                    font-size: 2rem;
                }
                .hero-subtitle {
                    font-size: 1rem;
                }
                .feature-icon {
                    width: 60px;
                    height: 60px;
                    line-height: 60px;
                    font-size: 1.75rem;
                }
                .stats-grid {
                    grid-template-columns: repeat(2, 1fr);
                    gap: 1rem;
                }
                .stat-number {
                    font-size: 2rem;
                }
            }
            
            @media (max-width: 576px) {
                .hero-section {
                    padding: 2.5rem 1rem;
                }
                .hero-title {
                    font-size: 1.75rem;
                }
                .hero-subtitle {
                    font-size: 0.95rem;
                }
                .hero-cta {
                    width: 100%;
                    padding: 0.875rem 1.5rem;
                }
                .feature-box {
                    padding: 1.25rem;
                }
                .feature-icon {
                    width: 50px;
                    height: 50px;
                    line-height: 50px;
                    font-size: 1.5rem;
                }
                .stats-grid {
                    grid-template-columns: 1fr 1fr;
                    gap: 0.75rem;
                }
                .stat-item {
                    padding: 1rem 0.5rem;
                }
                .stat-number {
                    font-size: 1.75rem;
                }
                .stat-label {
                    font-size: 0.8rem;
                }
            }
        </style>';

        $content = $customStyles . '
        <section class="hero-section text-center">
            <div class="container">
                <h1 class="hero-title">' . $heroTitle . '</h1>
                <p class="hero-subtitle">' . $heroSubtitle . '</p>
                <a href="/courses" class="hero-cta">' . $ctaButton . '</a>
            </div>
        </section>
        <section class="container py-4 py-md-5">
            <div class="text-center mb-4 mb-md-5">
                <h2 class="fw-bold" style="color: var(--text);">' . $featuresTitle . '</h2>
                <div style="width: 60px; height: 4px; background: var(--primary); margin: 15px auto;"></div>
            </div>
            <div class="row g-3 g-md-4">
                <div class="col-md-4">
                    <div class="feature-box">
                        <div class="feature-icon">ПР</div>
                        <h5 class="fw-bold mb-2 mb-md-3" style="color: var(--text);">' . $feature1Title . '</h5>
                        <p class="mb-0" style="color: var(--text-muted);">' . $feature1Desc . '</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-box">
                        <div class="feature-icon">ОП</div>
                        <h5 class="fw-bold mb-2 mb-md-3" style="color: var(--text);">' . $feature2Title . '</h5>
                        <p class="mb-0" style="color: var(--text-muted);">' . $feature2Desc . '</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-box">
                        <div class="feature-icon">ТР</div>
                        <h5 class="fw-bold mb-2 mb-md-3" style="color: var(--text);">' . $feature3Title . '</h5>
                        <p class="mb-0" style="color: var(--text-muted);">' . $feature3Desc . '</p>
                    </div>
                </div>
            </div>
            <div class="stats-grid">
                <div class="stat-item">
                    <span class="stat-number">2000+</span>
                    <span class="stat-label">' . $statsStudents . '</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">12</span>
                    <span class="stat-label">' . $statsCourses . '</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">94%</span>
                    <span class="stat-label">' . $statsEmployment . '</span>
                </div>
            </div>
        </section>';

        return str_replace(['{{TITLE}}', '{{CONTENT}}'], [$title, $content], $template);
    }
}
