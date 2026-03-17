<?php
namespace Views;

class ProductTemplate extends BaseTemplate
{
    private static array $products = [
        1 => [
            'id' => 1,
            'title' => 'Автострахование',
            'icon' => '🚗',
            'description' => 'Полное покрытие ОСАГО и КАСКО. Оформление онлайн за 15 минут, выплаты в день обращения.',
            'features' => [
                '✅ Оформление полиса за 15 минут',
                '✅ Выплаты в день обращения',
                '✅ Помощь на дорогах 24/7',
                '✅ Скидка 10% за безаварийную езду'
            ],
            'price_from' => 'от 3 500 ₽/год',
            'coverage' => 'до 400 000 ₽ по ОСАГО, до 10 млн ₽ по КАСКО'
        ],
        2 => [
            'id' => 2,
            'title' => 'Имущество',
            'icon' => '🏠',
            'description' => 'Защита квартиры, дома и дачи от пожара, затопления, кражи и стихийных бедствий.',
            'features' => [
                '✅ Покрытие от пожара и затопления',
                '✅ Защита от кражи и вандализма',
                '✅ Страхование отделки и имущества',
                '✅ Онлайн-оценка ущерба'
            ],
            'price_from' => 'от 1 200 ₽/год',
            'coverage' => 'до 10 млн ₽ на недвижимость'
        ],
        3 => [
            'id' => 3,
            'title' => 'Здоровье (ДМС)',
            'icon' => '🏥',
            'description' => 'Полисы для взрослых и детей. Прием в лучших клиниках города без очередей и справок.',
            'features' => [
                '✅ Приём у специалистов без очереди',
                '✅ Анализы и диагностика включены',
                '✅ Стоматология и скорая помощь',
                '✅ Телемедицина 24/7'
            ],
            'price_from' => 'от 8 900 ₽/год',
            'coverage' => 'до 500 000 ₽ на лечение'
        ],
        4 => [
            'id' => 4,
            'title' => 'Для Бизнеса',
            'icon' => '💼',
            'description' => 'Страхование ответственности, коммерческих грузов и здоровья сотрудников компании.',
            'features' => [
                '✅ Страхование гражданской ответственности',
                '✅ Защита коммерческих грузов',
                '✅ ДМС для сотрудников',
                '✅ Индивидуальные тарифы'
            ],
            'price_from' => 'индивидуально',
            'coverage' => 'до 50 млн ₽'
        ],
        5 => [
            'id' => 5,
            'title' => 'Путешествия',
            'icon' => '✈️',
            'description' => 'Туристический полис для визы и поездок за границу. Покрытие до $50,000 и помощь 24/7.',
            'features' => [
                '✅ Покрытие медицинских расходов за рубежом',
                '✅ Эвакуация и репатриация',
                '✅ Потеря багажа и документов',
                '✅ Поддержка на 20+ языках'
            ],
            'price_from' => 'от 450 ₽/неделя',
            'coverage' => 'до $50 000'
        ],
        6 => [
            'id' => 6,
            'title' => 'Страхование жизни',
            'icon' => '❤️',
            'description' => 'Накопительное страхование жизни. Защита семьи и инвестиции в будущее.',
            'features' => [
                '✅ Накопительная часть с доходностью',
                '✅ Защита при несчастном случае',
                '✅ Налоговые вычеты до 120 000 ₽',
                '✅ Гибкие условия выплат'
            ],
            'price_from' => 'от 2 000 ₽/мес',
            'coverage' => 'до 20 млн ₽'
        ]
    ];

    // 🆕 Новый метод для продукта (не переопределяет родительский!)
    public static function renderProduct(int $productId): string
    {
        $template = parent::getTemplate();
        $product = self::$products[$productId] ?? null;
        
        if (!$product) {
            http_response_code(404);
            return (new \Controllers\ErrorController())->get();
        }
        
        $title = $product['title'] . ' - Страховая компания "Чёрный Вантуз"';
        
        $customStyles = '
        <style>
            .product-hero {
                background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%);
                border-radius: 25px;
                padding: 3rem 2rem;
                color: white;
                margin-bottom: 2.5rem;
                position: relative;
                overflow: hidden;
                box-shadow: 0 20px 40px rgba(13, 110, 253, 0.3);
            }
            .product-hero::before {
                content: "' . $product['icon'] . '";
                position: absolute;
                right: -20px;
                bottom: -40px;
                font-size: 12rem;
                opacity: 0.15;
                transform: rotate(-15deg);
                pointer-events: none;
            }
            .product-icon-lg {
                font-size: 4rem;
                margin-bottom: 1rem;
                display: inline-block;
                background: rgba(255,255,255,0.2);
                width: 100px;
                height: 100px;
                line-height: 100px;
                border-radius: 50%;
                backdrop-filter: blur(10px);
            }
            .product-price {
                font-size: 2rem;
                font-weight: 800;
                color: #fff;
                margin: 1rem 0;
            }
            .coverage-badge {
                background: rgba(255,255,255,0.2);
                padding: 0.5rem 1.5rem;
                border-radius: 50px;
                display: inline-block;
                font-size: 0.95rem;
            }
            .feature-list { list-style: none; padding: 0; }
            .feature-list li {
                padding: 0.75rem 0;
                border-bottom: 1px solid #eef2f7;
                font-size: 1.05rem;
                color: #475569;
            }
            .feature-list li:last-child { border-bottom: none; }
            .cta-section {
                background: linear-gradient(135deg, #f8fafc 0%, #eef2ff 100%);
                border-radius: 20px;
                padding: 2.5rem;
                margin: 3rem 0;
                text-align: center;
                border: 2px dashed #0d6efd;
            }
            .btn-calculate {
                background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%);
                border: none;
                padding: 15px 40px;
                font-size: 1.1rem;
                font-weight: 600;
                border-radius: 50px;
                color: white;
                transition: all 0.3s ease;
                box-shadow: 0 10px 25px rgba(13, 110, 253, 0.3);
                text-decoration: none;
                display: inline-block;
            }
            .btn-calculate:hover {
                transform: translateY(-3px);
                box-shadow: 0 15px 35px rgba(13, 110, 253, 0.4);
                color: white;
                text-decoration: none;
            }
            .back-link {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                color: #64748b;
                text-decoration: none;
                font-weight: 500;
                margin-bottom: 1.5rem;
                transition: color 0.2s;
            }
            .back-link:hover { color: #0d6efd; }
            
            /* Калькулятор */
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
            .calculator-body { padding: 2rem; }
            .form-label { font-weight: 600; color: #334155; margin-bottom: 0.5rem; }
            .form-control-lg, .form-select-lg {
                border-radius: 15px;
                border: 2px solid #e2e8f0;
                padding: 0.75rem 1.25rem;
            }
            .form-control-lg:focus, .form-select-lg:focus {
                border-color: #0d6efd;
                box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.15);
            }
            .btn-calc-submit {
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
            .btn-calc-submit:hover {
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
            @keyframes fadeInUp {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .animate-fade { animation: fadeInUp 0.5s ease forwards; }
            .animate-fade:nth-child(2) { animation-delay: 0.1s; }
            .animate-fade:nth-child(3) { animation-delay: 0.2s; }
        </style>
        ';

        $featuresHtml = '';
        foreach ($product['features'] as $feature) {
            $featuresHtml .= '<li class="animate-fade">' . $feature . '</li>';
        }

        $content = $customStyles . '
        <section class="container py-5">
            <a href="/services" class="back-link">← Назад к услугам</a>
            
            <div class="product-hero animate-fade">
                <div class="product-icon-lg">' . $product['icon'] . '</div>
                <h1 class="display-5 fw-bold mb-3">' . $product['title'] . '</h1>
                <p class="lead mb-4 opacity-90">' . $product['description'] . '</p>
                <div class="product-price">' . $product['price_from'] . '</div>
                <span class="coverage-badge">🛡️ Покрытие: ' . $product['coverage'] . '</span>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4 p-4">
                        <h3 class="fw-bold mb-4">✨ Преимущества тарифа</h3>
                        <ul class="feature-list">
                            ' . $featuresHtml . '
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="cta-section">
                        <h4 class="fw-bold mb-3">🎯 Готовы оформить?</h4>
                        <p class="text-muted mb-4">Рассчитайте стоимость полиса за 1 минуту</p>
                        <a href="#calculator" class="btn btn-calculate w-100">
                            Рассчитать стоимость
                        </a>
                        <p class="small text-muted mt-3 mb-0">
                            📞 Или звоните: <a href="tel:+79999999999" class="text-decoration-none fw-bold">8 999 999 99 99</a>
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Калькулятор -->
            <div id="calculator" class="mt-5">
                ' . self::getCalculator($productId) . '
            </div>
        </section>
        ';

        return sprintf($template, $title, $content);
    }

    // 🧮 Калькулятор (вспомогательный метод)
    private static function getCalculator(int $productId): string
    {
        $productRates = [
            1 => ['rate' => 0.20, 'name' => 'Автострахование'],
            2 => ['rate' => 0.25, 'name' => 'Имущество'],
            3 => ['rate' => 0.10, 'name' => 'Здоровье'],
            4 => ['rate' => 0.15, 'name' => 'Бизнес'],
            5 => ['rate' => 0.08, 'name' => 'Путешествия'],
            6 => ['rate' => 0.12, 'name' => 'Жизнь']
        ];
        
        $currentProduct = $productRates[$productId] ?? $productRates[1];
        
        return '
        <div class="calculator-card">
            <div class="calculator-header">
                <h3 class="mb-0 fw-bold">🧮 Калькулятор: ' . $currentProduct['name'] . '</h3>
            </div>
            <div class="calculator-body">
                <form id="insuranceCalculator" class="row g-4">
                    <input type="hidden" id="productRate" value="' . $currentProduct['rate'] . '">
                    
                    <div class="col-md-6">
                        <label class="form-label">💰 Стоимость объекта (₽)</label>
                        <input type="number" id="objectValue" class="form-control form-control-lg" 
                               placeholder="Например: 1500000" min="1000" step="1000" required>
                        <div class="form-text small">Укажите примерную стоимость для расчёта</div>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">🛡️ Уровень покрытия</label>
                        <select id="coverageLevel" class="form-select form-select-lg">
                            <option value="0.5">Базовый (50%)</option>
                            <option value="0.75" selected>Оптимальный (75%)</option>
                            <option value="1.0">Полный (100%)</option>
                            <option value="1.2">Расширенный (120%)</option>
                        </select>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">⏱️ Срок страхования</label>
                        <select id="term" class="form-select form-select-lg">
                            <option value="1">1 год</option>
                            <option value="2">2 года (−5%)</option>
                            <option value="3">3 года (−10%)</option>
                        </select>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">🎁 Опции</label>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" id="option1" value="500">
                            <label class="form-check-label" for="option1">Помощь на дорогах (+500₽)</label>
                        </div>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" id="option2" value="1000">
                            <label class="form-check-label" for="option2">Юр. поддержка (+1000₽)</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="option3" value="1500">
                            <label class="form-check-label" for="option3">Диагностика (+1500₽)</label>
                        </div>
                    </div>
                    
                    <div class="col-12">
                        <button type="submit" class="btn btn-calc-submit">
                            ✨ Рассчитать
                        </button>
                    </div>
                </form>
                
                <div id="calculationResult" class="result-box text-center">
                    <h5 class="fw-bold mb-3">📊 Результат</h5>
                    <div class="row text-center mb-3">
                        <div class="col-4">
                            <div class="small text-muted">Объект</div>
                            <div class="fw-bold" id="resObjectValue">—</div>
                        </div>
                        <div class="col-4">
                            <div class="small text-muted">Покрытие</div>
                            <div class="fw-bold" id="resCoverage">—</div>
                        </div>
                        <div class="col-4">
                            <div class="small text-muted">Срок</div>
                            <div class="fw-bold" id="resTerm">—</div>
                        </div>
                    </div>
                    <hr>
                    <div class="fs-6 text-muted mb-2">💵 Взнос в год</div>
                    <div class="result-value" id="finalPrice">0 ₽</div>
                    <p class="text-muted small mt-2 mb-3">* Сумма может измениться после оценки</p>
                    <a href="tel:+79999999999" class="btn btn-success rounded-pill px-4 fw-bold">
                        📞 Оформить
                    </a>
                </div>
            </div>
        </div>
        
        <script>
        document.getElementById("insuranceCalculator").addEventListener("submit", function(e) {
            e.preventDefault();
            const objectValue = parseFloat(document.getElementById("objectValue").value) || 0;
            const coverageLevel = parseFloat(document.getElementById("coverageLevel").value);
            const term = parseInt(document.getElementById("term").value);
            const baseRate = parseFloat(document.getElementById("productRate").value);
            
            let optionsTotal = 0;
            if(document.getElementById("option1").checked) optionsTotal += 500;
            if(document.getElementById("option2").checked) optionsTotal += 1000;
            if(document.getElementById("option3").checked) optionsTotal += 1500;
            
            const coverageAmount = objectValue * coverageLevel;
            let premium = coverageAmount * baseRate;
            if(term === 2) premium *= 0.95;
            if(term === 3) premium *= 0.90;
            premium += optionsTotal;
            
            const formatRub = (num) => new Intl.NumberFormat("ru-RU", {
                style: "currency", currency: "RUB", maximumFractionDigits: 0
            }).format(num);
            
            document.getElementById("resObjectValue").textContent = formatRub(objectValue);
            document.getElementById("resCoverage").textContent = (coverageLevel * 100) + "%";
            document.getElementById("resTerm").textContent = term + " год" + (term > 1 ? "а" : "");
            document.getElementById("finalPrice").textContent = formatRub(premium);
            
            const resultBlock = document.getElementById("calculationResult");
            resultBlock.style.display = "block";
            resultBlock.scrollIntoView({behavior: "smooth", block: "center"});
        });
        </script>
        ';
    }

    // ⚠️ Обязательно: оставляем совместимый getTemplate() для наследования
    public static function getTemplate(): string
    {
        return parent::getTemplate();
    }
}