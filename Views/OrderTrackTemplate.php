<?php
namespace Views;

class OrderTrackTemplate extends BaseTemplate
{
    public static function getTemplate(): string
    {
        $template = parent::getTemplate();
        $title = 'Отследить заказ - CodeStart Academy';

        $customStyles = '
<style>
.track-container {
    background: white;
    border-radius: 30px;
    padding: 3rem;
    box-shadow: 0 20px 60px rgba(99, 102, 241, 0.15);
    max-width: 600px;
    margin: 0 auto;
}
.status-step {
    display: flex;
    align-items: center;
    padding: 1.5rem;
    border-radius: 15px;
    margin-bottom: 1rem;
    background: #f8fafc;
    transition: all 0.3s ease;
}
.status-step.active {
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    color: white;
}
.status-step .step-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    margin-right: 1rem;
    background: white;
    color: #6366f1;
}
.status-step.active .step-icon {
    background: rgba(255,255,255,0.2);
    color: white;
}
</style>';

        $content = $customStyles . '
<section class="container py-5">
    <div class="track-container">
        <h1 class="display-5 fw-bold text-center mb-4">📍 Отследить заказ</h1>
        
        <form id="trackForm" class="mb-4">
            <div class="mb-3">
                <label class="form-label">Номер заказа</label>
                <input type="text" id="orderId" class="form-control" placeholder="ORD-20240101-ABC123" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" id="orderEmail" class="form-control" placeholder="your@email.com" required>
            </div>
            <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 w-100">
                🔍 Найти заказ
            </button>
        </form>

        <div id="trackResult" style="display: none;">
            <div class="status-step" id="status-new">
                <div class="step-icon">📝</div>
                <div>
                    <div class="fw-bold">Заказ создан</div>
                    <small>Ожидает подтверждения</small>
                </div>
            </div>
            <div class="status-step" id="status-processing">
                <div class="step-icon">⚙️</div>
                <div>
                    <div class="fw-bold">В обработке</div>
                    <small>Менеджер проверяет заказ</small>
                </div>
            </div>
            <div class="status-step" id="status-paid">
                <div class="step-icon">✅</div>
                <div>
                    <div class="fw-bold">Оплачен</div>
                    <small>Ожидает активации</small>
                </div>
            </div>
            <div class="status-step" id="status-completed">
                <div class="step-icon">🎉</div>
                <div>
                    <div class="fw-bold">Выполнен</div>
                    <small>Доступ к курсу открыт</small>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.getElementById("trackForm").addEventListener("submit", function(e) {
    e.preventDefault();
    
    const orderId = document.getElementById("orderId").value;
    const email = document.getElementById("orderEmail").value;
    
    fetch("/order/track-api?order_id=" + encodeURIComponent(orderId) + "&email=" + encodeURIComponent(email))
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                document.getElementById("trackResult").style.display = "block";
                // Подсветка статуса
                const statusMap = {
                    "new": "status-new",
                    "processing": "status-processing", 
                    "paid": "status-paid",
                    "completed": "status-completed"
                };
                document.querySelectorAll(".status-step").forEach(el => el.classList.remove("active"));
                if (statusMap[data.order.status]) {
                    document.getElementById(statusMap[data.order.status]).classList.add("active");
                }
            } else {
                alert("❌ Заказ не найден. Проверьте номер и email.");
            }
        })
        .catch(() => {
            alert("❌ Ошибка соединения");
        });
});
</script>';

        return str_replace(['{{TITLE}}', '{{CONTENT}}'], [$title, $content], $template);
    }
}