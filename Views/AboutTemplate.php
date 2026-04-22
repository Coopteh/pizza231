<?php

namespace Views;

use Lib\Language;

class AboutTemplate extends BaseTemplate
{
    public static function getTemplate(): string
    {
        $template = parent::getTemplate();
        $title = Language::get('about_title') . ' - ' . Language::get('site_name');

        $customStyles = '
        <style>
            /* 🌙 ТЁМНАЯ ТЕМА: Стили для страницы "О нас" */
            .hero-section {
                background: var(--badge-bg);
                border-radius: 8px;
                padding: 3rem 2rem;
                margin-bottom: 3rem;
                border: 1px solid var(--border);
                transition: background 0.3s ease, border-color 0.3s ease;
            }
            
            .contact-block {
                background: var(--primary);
                color: white;
                border-radius: 8px;
                padding: 2.5rem;
                text-align: center;
                margin: 3rem 0;
                transition: background 0.3s ease;
            }
            
            .contact-block h4, .contact-block p {
                color: white;
            }
            
            .phone-display {
                font-size: 2rem;
                font-weight: 700;
                color: #fff;
                text-decoration: none;
                display: block;
                margin: 1rem 0;
            }
            
            .phone-display:hover {
                color: #ebf4ff;
            }
            
            .map-wrapper {
                border-radius: 8px;
                overflow: hidden;
                box-shadow: var(--shadow);
                border: 3px solid var(--surface);
            }
            
            .stats-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 1.5rem;
                margin: 2rem 0;
            }
            
            .stat-item {
                text-align: center;
                padding: 1.5rem;
                background: var(--surface);
                border-radius: 8px;
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
                font-size: 0.95rem;
                transition: color 0.3s ease;
            }
            
            .feature-box {
                background: var(--surface);
                border-radius: 8px;
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
                border-radius: 8px;
                color: var(--primary);
                transition: all 0.3s ease;
            }
            
            .feature-box:hover .feature-icon {
                background: var(--primary);
                color: white;
            }
            
            @media (max-width: 768px) {
                .hero-section {
                    padding: 2rem 1rem;
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
                    padding: 1.5rem 1rem;
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
                .contact-block {
                    padding: 1.5rem;
                }
                .phone-display {
                    font-size: 1.5rem;
                }
            }
        </style>';

        $content = $customStyles . '
        <section class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="hero-section text-center">
                        <span class="badge bg-primary bg-opacity-10 text-primary mb-3 px-3 py-2 rounded-pill">' . Language::get('about_founded') . '</span>
                        <h2 class="display-5 fw-bold mb-4" style="color: var(--text);">' . Language::get('site_name') . '</h2>
                        <p class="lead mx-auto" style="max-width: 800px; color: var(--text-muted);">
                            ' . Language::get('about_subtitle') . '
                        </p>
                    </div>
                </div>
            </div>
            <div class="stats-grid">
                <div class="stat-item">
                    <span class="stat-number">65+</span>
                    <span class="stat-label">' . Language::get('about_stats_years') . '</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">15 000+</span>
                    <span class="stat-label">' . Language::get('about_stats_graduates') . '</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">12</span>
                    <span class="stat-label">' . Language::get('about_stats_specialties') . '</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">94%</span>
                    <span class="stat-label">' . Language::get('about_stats_employment') . '</span>
                </div>
            </div>
            <div class="row g-4 mb-5">
                <div class="col-12 text-center mb-3">
                    <h3 class="fw-bold" style="color: var(--text);">' . Language::get('about_why_title') . '</h3>
                    <div style="width: 60px; height: 4px; background: var(--primary); margin: 15px auto;"></div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="feature-box">
                        <div class="feature-icon">ОБ</div>
                        <h5 class="fw-bold" style="color: var(--text);">' . Language::get('about_feature_education') . '</h5>
                        <p class="small mb-0" style="color: var(--text-muted);">' . Language::get('about_feature_education_desc') . '</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="feature-box">
                        <div class="feature-icon">ПР</div>
                        <h5 class="fw-bold" style="color: var(--text);">' . Language::get('about_feature_practice') . '</h5>
                        <p class="small mb-0" style="color: var(--text-muted);">' . Language::get('about_feature_practice_desc') . '</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="feature-box">
                        <div class="feature-icon">ТР</div>
                        <h5 class="fw-bold" style="color: var(--text);">' . Language::get('about_feature_employment') . '</h5>
                        <p class="small mb-0" style="color: var(--text-muted);">' . Language::get('about_feature_employment_desc') . '</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="feature-box">
                        <div class="feature-icon">ДИ</div>
                        <h5 class="fw-bold" style="color: var(--text);">' . Language::get('about_feature_diploma') . '</h5>
                        <p class="small mb-0" style="color: var(--text-muted);">' . Language::get('about_feature_diploma_desc') . '</p>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="contact-block">
                        <h3 class="fw-bold mb-3">' . Language::get('about_admissions') . '</h3>
                        <p class="mb-4 opacity-75">' . Language::get('about_admissions_desc') . '</p>
                        <a href="tel:+73842396000" class="phone-display">+7 (3842) 39-60-00</a>
                        <a href="mailto:info@kemt.ru" class="d-block mb-3 opacity-75">info@kemt.ru</a>
                        <a href="mailto:info@kemt.ru" class="btn btn-light btn-lg px-5 fw-bold mt-2">
                            ' . Language::get('about_write_us') . '
                        </a>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center mt-5">
                <div class="col-lg-10">
                    <h4 class="mb-3 text-center fw-bold" style="color: var(--text);">' . Language::get('about_address') . '</h4>
                    <div class="map-wrapper mb-5">
                        <iframe src="https://yandex.ru/map-widget/v1/?ll=86.066427%2C55.355900&z=15" width="100%" height="450" frameborder="0" allowfullscreen="true"></iframe>
                    </div>
                    <div class="row text-center g-3">
                        <div class="col-md-4">
                            <strong style="color: var(--text);">' . Language::get('about_address_label') . ':</strong><br>
                            <span style="color: var(--text-muted);">г. Кемерово, ул. Тухачевского, 32а</span>
                        </div>
                        <div class="col-md-4">
                            <strong style="color: var(--text);">' . Language::get('about_schedule_label') . ':</strong><br>
                            <span style="color: var(--text-muted);">' . Language::get('about_schedule') . '</span>
                        </div>
                        <div class="col-md-4">
                            <strong style="color: var(--text);">' . Language::get('about_transport_label') . ':</strong><br>
                            <span style="color: var(--text-muted);">' . Language::get('about_transport') . '</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>';

        return str_replace(['{{TITLE}}', '{{CONTENT}}'], [$title, $content], $template);
    }
}
