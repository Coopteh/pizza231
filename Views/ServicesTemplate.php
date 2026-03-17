<?php
namespace Views;

class ServicesTemplate extends BaseTemplate
{
    public static function getTemplate(): string
    {
        $template = parent::getTemplate();
        $title = 'Наши услуги - Страховая компания "Чёрный Вантуз"';
        
        $customStyles = '
        <style>
            .service-card {
                border: none;
                border-radius: 20px;
                transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                background: #ffffff;
                overflow: hidden;
                position: relative;
                z-index: 1;
                cursor: pointer;
            }
            
            .service-card:hover {
                transform: translateY(-8px);
                box-shadow: 0 20px 40px rgba(0,0,0,0.12) !important;
                text-decoration: none;
            }

            .service-card::before {
                content: "";
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 6px;
                background: linear-gradient(90deg, #0d6efd, #0dcaf0);
                z-index: 2;
                opacity: 0.8;
            }

            .icon-box {
                width: 90px;
                height: 90px;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 50%;
                font-size: 3rem;
                margin: 0 auto 1.5rem auto;
                background: linear-gradient(135deg, #f0f4ff 0%, #eef2ff 100%);
                color: #0d6efd;
                box-shadow: 0 10px 20px rgba(13, 110, 253, 0.15);
                transition: transform 0.3s ease;
            }

            .service-card:hover .icon-box {
                transform: scale(1.1) rotate(5deg);
                background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%);
                color: #fff;
            }

            .card-title {
                font-weight: 700;
                color: #1e293b;
                margin-bottom: 0.8rem;
            }

            .card-text {
                color: #64748b;
                line-height: 1.6;
                margin-bottom: 1.5rem;
            }

            .btn-custom {
                border-radius: 50px;
                padding: 10px 25px;
                font-weight: 600;
                transition: all 0.3s ease;
                border: 2px solid #0d6efd;
                color: #0d6efd;
                background: transparent;
                text-decoration: none;
                display: inline-block;
            }

            .btn-custom:hover {
                background: #0d6efd;
                color: #fff;
                box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
                text-decoration: none;
            }

            /* CTA карточка */
            .cta-card {
                background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
                color: white;
                border: none;
            }
            .cta-card::before {
                background: linear-gradient(90deg, #fbbf24, #f59e0b);
            }
            .cta-card .card-title { color: #fff; }
            .cta-card .card-text { color: #cbd5e1; }
            .cta-card .icon-box {
                background: rgba(255,255,255,0.1);
                color: #fbbf24;
                box-shadow: 0 10px 20px rgba(0,0,0,0.2);
            }
            .cta-card:hover .icon-box {
                background: #fbbf24;
                color: #1e293b;
            }
            .cta-card .btn-custom {
                border-color: #fbbf24;
                color: #fbbf24;
            }
            .cta-card .btn-custom:hover {
                background: #fbbf24;
                color: #1e293b;
                box-shadow: 0 5px 15px rgba(251, 191, 36, 0.4);
            }

            /* === Стили калькулятора === */
            .calculator-card {
                background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
                border-radius: 25px;
                border: none;
                box-shadow: 0 20px 60px rgba(13, 110, 253, 0.15);
                overflow: hidden;
            }
            .calculator-header {
                background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%);
                color: white;
                padding: 1.5rem 2rem;
                text-align: center;
            }
            .calculator-header h3 {
                margin: 0;
                font-weight: 700;
                font-size: 1.5rem;
            }
            .calculator-body {
                padding: 2rem;
            }
            .form-label {
                font-weight: 600;
                color: #334155;
                margin-bottom: 0.5rem;
            }
            .form-control-lg, .form-select-lg {
                border-radius: 15px;
                border: 2px solid #e2e8f0;
                padding: 0.75rem 1.25rem;
                font-size: 1rem;
                transition: all 0.2s;
            }
            .form-control-lg:focus, .form-select-lg:focus {
                border-color: #0d6efd;
                box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.15);
            }
            .form-check-input:checked {
                background-color: #0d6efd;
                border-color: #0d6efd;
            }
            .btn-calculate {
                background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%);
                border: none;
                padding: 1rem 2.5rem;
                font-size: 1.1rem;
                font-weight: 600;
                border-radius: 50px;
                color: white;
                transition: all 0.3s ease;
                box-shadow: 0 10px 25px rgba(13, 110, 253, 0.3);
                width: 100%;
            }
            .btn-calculate:hover {
                transform: translateY(-2px);
                box-shadow: 0 15px 35px rgba(13, 110, 253, 0.4);
                color: white;
            }
            .result-box {
                background: linear-gradient(135deg, #e0f2fe 0%, #f0f9ff 100%);
                border: 2px solid #0dcaf0;
                border-radius: 20px;
                padding: 1.5rem;
                margin-top: 2rem;
                display: none;
                animation: slideUp 0.4s ease;
            }
            @keyframes slideUp {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .result-value {
                font-size: 2rem;
                font-weight: 800;
                color: #0d6efd;
            }
            .product-badge {
                display: inline-block;
                background: linear-gradient(135deg, #0d6efd, #0dcaf0);
                color: white;
                padding: 0.35rem 1rem;
                border-radius: 50px;
                font-size: 0.85rem;
                font-weight: 600;
                margin-bottom: 1rem;
            }
            .service-card a {
                text-decoration: none;
            }
        </style>
        ';

        // HTML-контент услуг с ссылками на /product/{id}
        $content = $customStyles . '
        <section class="container py-5">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold text-dark mb-3">🛡️ Наши страховые услуги</h2>
                <p class="lead text-muted mx-auto" style="max-width: 700px;">
                    Надежная защита для вас, вашего дома и бизнеса. Выберите подходящий вариант ниже.
                </p>
                <div style="width: 80px; height: 5px; background: linear-gradient(90deg, #0d6efd, #0dcaf0); margin: 25px auto; border-radius: 10px;"></div>
            </div>
    
            <div class="row g-4 justify-content-center">
                
                <!-- Услуга 1: Авто -->
                <a href="/product/1" class="col-md-6 col-lg-6 text-decoration-none">
                    <div class="card service-card h-100 shadow-sm p-4">
                        <div class="card-body text-center">
                            <div class="icon-box">🚗</div>
                            <h4 class="card-title">Автострахование</h4>
                            <p class="card-text">Полное покрытие ОСАГО и КАСКО. Оформление онлайн за 15 минут, выплаты в день обращения.</p>
                            <span class="btn btn-custom">Подробнее →</span>
                        </div>
                    </div>
                </a>
                
                <!-- Услуга 2: Имущество -->
                <a href="/product/2" class="col-md-6 col-lg-6 text-decoration-none">
                    <div class="card service-card h-100 shadow-sm p-4">
                        <div class="card-body text-center">
                            <div class="icon-box">🏠</div>
                            <h4 class="card-title">Имущество</h4>
                            <p class="card-text">Защита квартиры, дома и дачи от пожара, затопления, кражи и стихийных бедствий.</p>
                            <span class="btn btn-custom">Подробнее →</span>
                        </div>
                    </div>
                </a>
                
                <!-- Услуга 3: Здоровье -->
                <a href="/product/3" class="col-md-6 col-lg-6 text-decoration-none">
                    <div class="card service-card h-100 shadow-sm p-4">
                        <div class="card-body text-center">
                            <div class="icon-box">🏥</div>
                            <h4 class="card-title">Здоровье (ДМС)</h4>
                            <p class="card-text">Полисы для взрослых и детей. Прием в лучших клиниках города без очередей и справок.</p>
                            <span class="btn btn-custom">Подробнее →</span>
                        </div>
                    </div>
                </a>

                <!-- Услуга 4: Бизнес -->
                <a href="/product/4" class="col-md-6 col-lg-6 text-decoration-none">
                    <div class="card service-card h-100 shadow-sm p-4">
                        <div class="card-body text-center">
                            <div class="icon-box">💼</div>
                            <h4 class="card-title">Для Бизнеса</h4>
                            <p class="card-text">Страхование ответственности, коммерческих грузов и здоровья сотрудников компании.</p>
                            <span class="btn btn-custom">Подробнее →</span>
                        </div>
                    </div>
                </a>

                <!-- Услуга 5: Путешествия -->
                <a href="/product/5" class="col-md-6 col-lg-6 text-decoration-none">
                    <div class="card service-card h-100 shadow-sm p-4">
                        <div class="card-body text-center">
                            <div class="icon-box">✈️</div>
                            <h4 class="card-title">Путешествия</h4>
                            <p class="card-text">Туристический полис для визы и поездок за границу. Покрытие до $50,000 и помощь 24/7.</p>
                            <span class="btn btn-custom">Подробнее →</span>
                        </div>
                    </div>
                </a>

                <!-- Услуга 6: Помощь (без ссылки на продукт) -->
                <div class="col-md-6 col-lg-6">
                    <div class="card service-card cta-card h-100 shadow-lg p-4">
                        <div class="card-body text-center d-flex flex-column justify-content-center">
                            <div class="icon-box">🤝</div>
                            <h4 class="card-title">Нужна помощь?</h4>
                            <p class="card-text">Не знаете, что выбрать? Наши эксперты подберут идеальный тариф бесплатно.</p>
                            <a href="tel:+79999999999" class="btn btn-custom mt-2">📞 8 999 999 99 99</a>
                        </div>
                    </div>
                </div>

            </div>
        </section>';

        // === Красивый калькулятор ===
        $calculatorContent = '
        <section class="container my-5" id="calculator">
            <div class="text-center mb-4">
                <h2 class="display-5 fw-bold text-dark mb-3">🧮 Калькулятор страховых взносов</h2>
                <p class="lead text-muted mx-auto" style="max-width: 700px;">
                    Рассчитайте стоимость полиса за 30 секунд — быстро, честно, без скрытых платежей
                </p>
            </div>
            
            <div class="calculator-card">
                <div class="calculator-header">
                    <h3>✨ Моментальный расчёт</h3>
                </div>
                <div class="calculator-body">
                    <form id="insuranceForm" class="row g-4">
                        
                        <!-- Тип страхования -->
                        <div class="col-md-6">
                            <label class="form-label">📋 Тип страхования</label>
                            <select id="productType" class="form-select form-select-lg" required>
                                <option value="" disabled selected>Выберите услугу...</option>
                                <option value="1" data-rate="0.20">🚗 Автострахование (20%)</option>
                                <option value="2" data-rate="0.25">🏠 Имущество (25%)</option>
                                <option value="3" data-rate="0.10">🏥 Здоровье / ДМС (10%)</option>
                                <option value="4" data-rate="0.15">💼 Для бизнеса (15%)</option>
                                <option value="5" data-rate="0.08">✈️ Путешествия (8%)</option>
                                <option value="6" data-rate="0.12">❤️ Страхование жизни (12%)</option>
                            </select>
                        </div>
                        
                        <!-- Стоимость объекта -->
                        <div class="col-md-6">
                            <label class="form-label">💰 Стоимость объекта (₽)</label>
                            <input type="number" id="objectValue" class="form-control form-control-lg" 
                                   placeholder="Например: 1 500 000" min="1000" step="1000" required>
                        </div>
                        
                        <!-- Уровень покрытия -->
                        <div class="col-md-6">
                            <label class="form-label">🛡️ Уровень покрытия</label>
                            <select id="coverageLevel" class="form-select form-select-lg">
                                <option value="0.5">Базовый (50% от стоимости)</option>
                                <option value="0.75" selected>Оптимальный (75%)</option>
                                <option value="1.0">Полный (100%)</option>
                                <option value="1.2">Расширенный (120%)</option>
                            </select>
                        </div>
                        
                        <!-- Срок страхования -->
                        <div class="col-md-6">
                            <label class="form-label">⏱️ Срок страхования</label>
                            <select id="term" class="form-select form-select-lg">
                                <option value="1">1 год</option>
                                <option value="2">2 года (−5% скидка)</option>
                                <option value="3">3 года (−10% скидка)</option>
                            </select>
                        </div>
                        
                        <!-- Дополнительные опции -->
                        <div class="col-12">
                            <label class="form-label">🎁 Дополнительные опции</label>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="form-check form-switch p-3 border rounded-3 h-100">
                                        <input class="form-check-input" type="checkbox" id="optRoad" value="500">
                                        <label class="form-check-label fw-medium" for="optRoad">
                                            🛠️ Помощь на дорогах
                                            <div class="text-muted small">+500 ₽/год</div>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check form-switch p-3 border rounded-3 h-100">
                                        <input class="form-check-input" type="checkbox" id="optLegal" value="1000">
                                        <label class="form-check-label fw-medium" for="optLegal">
                                            ⚖️ Юр. поддержка
                                            <div class="text-muted small">+1 000 ₽/год</div>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check form-switch p-3 border rounded-3 h-100">
                                        <input class="form-check-input" type="checkbox" id="optDiag" value="1500">
                                        <label class="form-check-label fw-medium" for="optDiag">
                                            🔍 Расширенная диагностика
                                            <div class="text-muted small">+1 500 ₽/год</div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Кнопка расчёта -->
                        <div class="col-12">
                            <button type="submit" class="btn btn-calculate">
                                ✨ Рассчитать страховку
                            </button>
                        </div>
                    </form>
                    
                    <!-- Блок результата -->
                    <div id="calcResult" class="result-box text-center">
                        <span class="product-badge" id="resProduct">Автострахование</span>
                        <h5 class="text-muted mb-3">Ваш ежегодный страховой взнос</h5>
                        <div class="result-value mb-3" id="finalPrice">0 ₽</div>
                        <p class="text-muted small mb-4">
                            * Итоговая сумма может быть скорректирована после оценки экспертом
                        </p>
                        <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                            <a href="tel:+79999999999" class="btn btn-success btn-lg rounded-pill px-4 fw-bold">
                                📞 Оформить по телефону
                            </a>
                            <button type="button" class="btn btn-outline-primary btn-lg rounded-pill px-4" onclick="document.getElementById(\'calcResult\').style.display=\'none\'">
                                🔁 Новый расчёт
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <script>
        document.getElementById("insuranceForm").addEventListener("submit", function(e) {
            e.preventDefault();
            
            // Получаем данные
            const productSelect = document.getElementById("productType");
            const productName = productSelect.options[productSelect.selectedIndex].text.replace(/\\(.*\\)/, "").trim();
            const baseRate = parseFloat(productSelect.options[productSelect.selectedIndex].dataset.rate);
            const objectValue = parseFloat(document.getElementById("objectValue").value) || 0;
            const coverageLevel = parseFloat(document.getElementById("coverageLevel").value);
            const term = parseInt(document.getElementById("term").value);
            
            // Дополнительные опции
            let optionsTotal = 0;
            if(document.getElementById("optRoad").checked) optionsTotal += 500;
            if(document.getElementById("optLegal").checked) optionsTotal += 1000;
            if(document.getElementById("optDiag").checked) optionsTotal += 1500;
            
            // Расчёт
            const coverageAmount = objectValue * coverageLevel;
            let premium = coverageAmount * baseRate;
            
            // Скидка за срок
            if(term === 2) premium *= 0.95;
            if(term === 3) premium *= 0.90;
            
            premium += optionsTotal;
            
            // Форматирование
            const formatRub = (num) => new Intl.NumberFormat("ru-RU", {
                style: "currency", 
                currency: "RUB", 
                maximumFractionDigits: 0
            }).format(num);
            
            // Отображаем результат
            document.getElementById("resProduct").textContent = productName;
            document.getElementById("finalPrice").textContent = formatRub(premium);
            
            // Показываем блок с анимацией
            const resultBox = document.getElementById("calcResult");
            resultBox.style.display = "block";
            resultBox.scrollIntoView({behavior: "smooth", block: "center"});
        });
        </script>
        ';

        return sprintf($template, $title, $content . $calculatorContent);
    }
}