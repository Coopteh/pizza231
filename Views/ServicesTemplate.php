<?php
namespace Views;

class ServicesTemplate extends BaseTemplate
{
    public static function getTemplate(): string
    {
        $template = parent::getTemplate();
        $title = 'Зоны и услуги — Фитнес-клуб «gym low cortisol»';
        $customStyles = '
        <style>
        .service-card { border: none; border-radius: 20px; transition: transform 0.3s ease, box-shadow 0.3s ease; background: #ffffff; overflow: hidden; position: relative; height: 100%; }
        .service-card:hover { transform: translateY(-6px); box-shadow: 0 16px 40px rgba(0, 0, 0, 0.12); }
        .service-card::before { content: ""; position: absolute; top: 0; left: 0; width: 100%; height: 5px; background: linear-gradient(90deg, #8b5cf6, #0dcaf0); }
        .icon-box { width: 85px; height: 85px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 2.75rem; margin: 0 auto 1.5rem; background: linear-gradient(135deg, #eef2ff 0%, #f0f4ff 100%); color: #8b5cf6; transition: all 0.3s ease; }
        .service-card:hover .icon-box { background: linear-gradient(135deg, #8b5cf6 0%, #0dcaf0 100%); color: #ffffff; transform: scale(1.05); }
        .card-title { font-weight: 600; color: #1e293b; margin-bottom: 0.75rem; font-size: 1.25rem; }
        .card-text { color: #64748b; line-height: 1.6; margin-bottom: 1.5rem; font-size: 0.95rem; }
        .btn-custom { border-radius: 50px; padding: 10px 28px; font-weight: 500; transition: all 0.3s ease; border: 2px solid #8b5cf6; color: #8b5cf6; background: transparent; text-decoration: none; display: inline-block; font-size: 0.95rem; }
        .btn-custom:hover { background: #8b5cf6; color: #ffffff; box-shadow: 0 6px 20px rgba(13, 110, 253, 0.25); text-decoration: none; }
        .cta-card { background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); color: #ffffff; }
        .cta-card::before { background: linear-gradient(90deg, #fbbf24, #f59e0b); }
        .cta-card .card-title { color: #ffffff; }
        .cta-card .card-text { color: #cbd5e1; }
        .cta-card .icon-box { background: rgba(255, 255, 255, 0.1); color: #fbbf24; }
        .cta-card:hover .icon-box { background: #fbbf24; color: #1e293b; }
        .cta-card .btn-custom { border-color: #fbbf24; color: #fbbf24; }
        .cta-card .btn-custom:hover { background: #fbbf24; color: #1e293b; }
        .calc-card { background: #ffffff; border-radius: 24px; border: 1px solid #e2e8f0; padding: 2.5rem; box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06); }
        .calc-header { background: linear-gradient(135deg, #8b5cf6 0%, #0dcaf0 100%); color: #ffffff; padding: 1.5rem 2rem; border-radius: 20px 20px 0 0; margin: -2.5rem -2.5rem 2rem; text-align: center; }
        .calc-header h3 { margin: 0; font-weight: 600; font-size: 1.35rem; }
        .form-label { font-weight: 500; color: #334155; margin-bottom: 0.5rem; font-size: 0.95rem; }
        .form-control, .form-select { border-radius: 12px; border: 1px solid #cbd5e1; padding: 0.75rem 1rem; font-size: 0.95rem; transition: all 0.2s ease; }
        .form-control:focus, .form-select:focus { border-color: #8b5cf6; box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.12); outline: none; }
        .form-check-input:checked { background-color: #8b5cf6; border-color: #8b5cf6; }
        .btn-calc { background: linear-gradient(135deg, #8b5cf6 0%, #0dcaf0 100%); border: none; padding: 14px 32px; border-radius: 50px; color: #ffffff; font-weight: 600; font-size: 1rem; width: 100%; transition: all 0.3s ease; box-shadow: 0 6px 20px rgba(13, 110, 253, 0.25); }
        .btn-calc:hover { transform: translateY(-2px); box-shadow: 0 10px 30px rgba(13, 110, 253, 0.35); color: #ffffff; }
        .result-box { background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); border: 2px solid #0dcaf0; border-radius: 20px; padding: 2rem; margin-top: 2rem; display: none; animation: slideUp 0.4s ease; }
        @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .result-value { font-size: 2.25rem; font-weight: 700; color: #8b5cf6; line-height: 1.2; }
        .result-label { font-size: 0.9rem; color: #64748b; margin-bottom: 0.5rem; }
        .term-switch { display: flex; gap: 1rem; margin-bottom: 1rem; padding: 0.5rem; background: #f8fafc; border-radius: 12px; }
        .term-switch .form-check { margin: 0; flex: 1; }
        .term-switch .form-check-input { display: none; }
        .term-switch .form-check-label { display: block; text-align: center; padding: 0.5rem 1rem; border-radius: 10px; cursor: pointer; font-weight: 500; color: #64748b; transition: all 0.2s ease; }
        .term-switch .form-check-input:checked + .form-check-label { background: #8b5cf6; color: #ffffff; }
        .input-group-text { background: #f8fafc; border: 1px solid #cbd5e1; color: #64748b; font-weight: 500; }
        .options-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; }
        .option-card { border: 1px solid #e2e8f0; border-radius: 12px; padding: 1rem; transition: all 0.2s ease; }
        .option-card:hover { border-color: #8b5cf6; background: #f8fafc; }
        .section-title { font-size: 1.75rem; font-weight: 700; color: #1e293b; margin-bottom: 0.75rem; }
        .section-subtitle { color: #64748b; font-size: 1rem; max-width: 600px; margin: 0 auto; }
        .divider { width: 70px; height: 4px; background: linear-gradient(90deg, #8b5cf6, #0dcaf0); margin: 20px auto; border-radius: 2px; }
        </style>';

        $content = $customStyles . '
        <section class="container py-5">
            <div class="text-center mb-5">
                <h2 class="section-title">Зоны и услуги</h2>
                <p class="section-subtitle">Всё для комфортных тренировок. Выберите направление ниже.</p>
                <div class="divider"></div>
            </div>
            <div class="row g-4 justify-content-center">
                <a href="/product/1" class="col-md-6 col-lg-4 text-decoration-none">
                    <div class="card service-card shadow-sm p-4">
                        <div class="card-body text-center">
                            <div class="icon-box">🏋️</div>
                            <h4 class="card-title">Тренажёрный зал</h4>
                            <p class="card-text">Кардио и силовые зоны. Современное оборудование.</p>
                            <span class="btn btn-custom">Подробнее</span>
                        </div>
                    </div>
                </a>
                <a href="/product/2" class="col-md-6 col-lg-4 text-decoration-none">
                    <div class="card service-card shadow-sm p-4">
                        <div class="card-body text-center">
                            <div class="icon-box">🧘</div>
                            <h4 class="card-title">Групповые</h4>
                            <p class="card-text">Йога, пилатес, зумба, кроссфит и другие направления.</p>
                            <span class="btn btn-custom">Подробнее</span>
                        </div>
                    </div>
                </a>
                <a href="/product/3" class="col-md-6 col-lg-4 text-decoration-none">
                    <div class="card service-card shadow-sm p-4">
                        <div class="card-body text-center">
                            <div class="icon-box">🏊</div>
                            <h4 class="card-title">Бассейн</h4>
                            <p class="card-text">25 метров, подогрев, индивидуальные дорожки.</p>
                            <span class="btn btn-custom">Подробнее</span>
                        </div>
                    </div>
                </a>
                <a href="/product/4" class="col-md-6 col-lg-4 text-decoration-none">
                    <div class="card service-card shadow-sm p-4">
                        <div class="card-body text-center">
                            <div class="icon-box">💪</div>
                            <h4 class="card-title">Персональный</h4>
                            <p class="card-text">Индивидуальные тренировки с тренером.</p>
                            <span class="btn btn-custom">Подробнее</span>
                        </div>
                    </div>
                </a>
                <a href="/product/5" class="col-md-6 col-lg-4 text-decoration-none">
                    <div class="card service-card shadow-sm p-4">
                        <div class="card-body text-center">
                            <div class="icon-box">🧖</div>
                            <h4 class="card-title">SPA-зона</h4>
                            <p class="card-text">Сауна, хаммам, массаж для восстановления.</p>
                            <span class="btn btn-custom">Подробнее</span>
                        </div>
                    </div>
                </a>
                <div class="col-md-6 col-lg-4">
                    <div class="card service-card cta-card shadow-lg p-4">
                        <div class="card-body text-center d-flex flex-column justify-content-center">
                            <div class="icon-box">🤝</div>
                            <h4 class="card-title">Консультация</h4>
                            <p class="card-text">Поможем подобрать карту. Пробное занятие бесплатно.</p>
                            <a href="tel:+79999999999" class="btn btn-custom mt-2">+7 (999) 999-99-99</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="container my-5" id="calculator">
            <div class="text-center mb-4">
                <h2 class="section-title">Калькулятор стоимости карты</h2>
                <p class="section-subtitle">Расчёт стоимости членства с учётом срока и опций</p>
                <div class="divider"></div>
            </div>
            <div class="calc-card">
                <form id="insuranceForm" class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label" for="productType">Тип карты</label>
                        <select id="productType" class="form-select" required>
                            <option value="" disabled selected>Выберите карту...</option>
                            <option value="1" data-rate="0.20">Безлимит (20%)</option>
                            <option value="2" data-rate="0.25">Утро (25%)</option>
                            <option value="3" data-rate="0.10">Базовая (10%)</option>
                            <option value="4" data-rate="0.15">Бизнес (15%)</option>
                            <option value="5" data-rate="0.08">Студент (8%)</option>
                            <option value="6" data-rate="0.12">Год (12%)</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="objectValue">Базовая стоимость (₽)</label>
                        <input type="number" id="objectValue" class="form-control" placeholder="Например: 30 000" min="1000" step="1000" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="coverageLevel">Уровень доступа</label>
                        <select id="coverageLevel" class="form-select">
                            <option value="0.5">Базовый (Тренажёрный зал)</option>
                            <option value="0.75" selected>Оптимальный (Зал + Групповые)</option>
                            <option value="1.0">Полный (Всё включено)</option>
                            <option value="1.2">VIP (Персональный тренер)</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Срок действия</label>
                        <div class="term-switch">
                            <div class="form-check"><input class="form-check-input" type="radio" name="termMode" id="termPreset" value="preset" checked><label class="form-check-label" for="termPreset">Готовые варианты</label></div>
                            <div class="form-check"><input class="form-check-input" type="radio" name="termMode" id="termCustom" value="custom"><label class="form-check-label" for="termCustom">Свой срок</label></div>
                        </div>
                        <select id="termPresetSelect" class="form-select">
                            <option value="1">1 месяц (базовый тариф)</option>
                            <option value="2">3 месяца (скидка 5%)</option>
                            <option value="3">6 месяцев (скидка 10%)</option>
                            <option value="5">12 месяцев (скидка 15%)</option>
                        </select>
                        <div id="termCustomInput" class="d-none">
                            <div class="input-group">
                                <input type="number" id="termCustomValue" class="form-control" placeholder="Срок" min="1" max="120" value="12">
                                <select id="termUnit" class="form-select" style="max-width: 100px;">
                                    <option value="12">мес</option>
                                    <option value="1" selected>лет</option>
                                </select>
                            </div>
                            <small class="text-muted" style="font-size: 0.8rem;">Доступно от 1 до 120 месяцев</small>
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Дополнительные опции</label>
                        <div class="options-grid">
                            <div class="option-card">
                                <div class="form-check"><input class="form-check-input" type="checkbox" id="optRoad" value="500"><label class="form-check-label" for="optRoad"><strong>Заморозка</strong><div class="text-muted small">+500 ₽</div></label></div>
                            </div>
                            <div class="option-card">
                                <div class="form-check"><input class="form-check-input" type="checkbox" id="optLegal" value="1000"><label class="form-check-label" for="optLegal"><strong>Гостевые визиты</strong><div class="text-muted small">+1 000 ₽</div></label></div>
                            </div>
                            <div class="option-card">
                                <div class="form-check"><input class="form-check-input" type="checkbox" id="optDiag" value="1500"><label class="form-check-label" for="optDiag"><strong>Персональный вводный</strong><div class="text-muted small">+1 500 ₽</div></label></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12"><button type="submit" class="btn btn-calc">Рассчитать стоимость</button></div>
                </form>
                <div id="calcResult" class="result-box text-center">
                    <p class="result-label">Итоговая стоимость карты</p>
                    <div class="result-value mb-3" id="finalPrice">0 ₽</div>
                    <div class="row g-3 mb-3">
                        <div class="col-4"><div class="small text-muted">База</div><div class="fw-semibold" id="resObject">—</div></div>
                        <div class="col-4"><div class="small text-muted">Доступ</div><div class="fw-semibold" id="resCoverage">—</div></div>
                        <div class="col-4"><div class="small text-muted">Срок</div><div class="fw-semibold" id="resTerm">—</div></div>
                    </div>
                    <p class="text-muted small mb-3">* Итоговая сумма может быть скорректирована в клубе</p>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                        <a href="tel:+79999999999" class="btn btn-success btn-lg rounded-pill px-4 fw-bold">Оформить по телефону</a>
                        <button type="button" class="btn btn-outline-primary btn-lg rounded-pill px-4" onclick="document.getElementById(\'calcResult\').style.display=\'none\'">Новый расчёт</button>
                    </div>
                </div>
            </div>
        </section>
        <script>
        document.querySelectorAll(\'input[name="termMode"]\').forEach(radio => {
            radio.addEventListener(\'change\', function() {
                const presetSelect = document.getElementById(\'termPresetSelect\');
                const customInput = document.getElementById(\'termCustomInput\');
                if (this.value === \'custom\') { presetSelect.classList.add(\'d-none\'); customInput.classList.remove(\'d-none\'); }
                else { presetSelect.classList.remove(\'d-none\'); customInput.classList.add(\'d-none\'); }
            });
        });
        document.getElementById("insuranceForm").addEventListener("submit", function(e) {
            e.preventDefault();
            const productSelect = document.getElementById("productType");
            const baseRate = parseFloat(productSelect.options[productSelect.selectedIndex].dataset.rate);
            const objectValue = parseFloat(document.getElementById("objectValue").value) || 0;
            const coverageLevel = parseFloat(document.getElementById("coverageLevel").value);
            let termYears = 1, termDiscount = 0;
            const termMode = document.querySelector(\'input[name="termMode"]:checked\').value;
            if (termMode === \'preset\') {
                const preset = parseInt(document.getElementById(\'termPresetSelect\').value);
                termYears = preset;
                if (preset === 2) termDiscount = 0.05;
                if (preset === 3) termDiscount = 0.10;
                if (preset === 5) termDiscount = 0.15;
            } else {
                const customValue = parseInt(document.getElementById(\'termCustomValue\').value) || 12;
                const unit = parseInt(document.getElementById(\'termUnit\').value);
                const totalMonths = unit === 1 ? customValue * 12 : customValue;
                termYears = totalMonths / 12;
                if (totalMonths >= 24) termDiscount = 0.05;
                if (totalMonths >= 36) termDiscount = 0.10;
                if (totalMonths >= 60) termDiscount = 0.15;
            }
            let optionsTotal = 0;
            if (document.getElementById(\'optRoad\').checked) optionsTotal += 500;
            if (document.getElementById(\'optLegal\').checked) optionsTotal += 1000;
            if (document.getElementById(\'optDiag\').checked) optionsTotal += 1500;
            const coverageAmount = objectValue * coverageLevel;
            let premium = coverageAmount * baseRate;
            premium = premium * (1 - termDiscount);
            premium += optionsTotal;
            const formatRub = (num) => new Intl.NumberFormat("ru-RU", { style: "currency", currency: "RUB", maximumFractionDigits: 0 }).format(num);
            document.getElementById("finalPrice").textContent = formatRub(premium);
            document.getElementById("resObject").textContent = formatRub(objectValue);
            document.getElementById("resCoverage").textContent = (coverageLevel * 100) + "%";
            let termText = termYears % 1 === 0 ? termYears + " год" : termYears.toFixed(1) + " года";
            if (termDiscount > 0) termText += " (−" + (termDiscount * 100) + "%)";
            document.getElementById("resTerm").textContent = termText;
            const resultBox = document.getElementById("calcResult");
            resultBox.style.display = "block";
            resultBox.scrollIntoView({ behavior: "smooth", block: "center" });
        });
        </script>';

        return sprintf($template, $title, $content);
    }
}