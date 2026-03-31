/**
 * Глобальные функции корзины
 */
const CartManager = {
    STORAGE_KEY: 'cart',
    _fullscreenToastShown: false, // Флаг: чтобы не показывать подряд

    showFullscreenToast(message = 'Товар добавлен в корзину!', duration = 2500) {
        const toast = document.getElementById('fullscreenToast');
        const video = document.getElementById('toastVideo');
        const messageEl = document.getElementById('fullscreenToastMessage');
        
        if (!toast || !video) return;
        
        // Обновляем текст
        if (messageEl) messageEl.textContent = message;
        
        // Сбрасываем видео на начало и запускаем
        video.currentTime = 0;
        video.play().catch(e => console.log('Video play error:', e));
        
        // Показываем Toast
        toast.classList.add('show');
        toast.setAttribute('aria-hidden', 'false');
        document.body.classList.add('toast-open');
        
        // Автоматическое закрытие
        if (this._toastTimer) clearTimeout(this._toastTimer);
        this._toastTimer = setTimeout(() => {
            this.hideFullscreenToast();
        }, duration);
    },
    
    // 👇 Скрыть полноэкранный Toast
    hideFullscreenToast() {
        const toast = document.getElementById('fullscreenToast');
        const video = document.getElementById('toastVideo');
        
        if (!toast) return;
        
        toast.classList.remove('show');
        toast.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('toast-open');
        
        // Останавливаем видео
        if (video) {
            video.pause();
            video.currentTime = 0;
        }
    },
    
    // 👇 Обновлённый showToast: выбирает тип уведомления
    showToast(message, type = 'success', useFullscreen = true) {
        // Если нужно полноэкранное уведомление и оно не показано прямо сейчас
        if (useFullscreen && !this._fullscreenToastShown) {
            this._fullscreenToastShown = true;
            this.showFullscreenToast(message);
            
            // Сбрасываем флаг через небольшую задержку (чтобы можно было показать снова)
            setTimeout(() => { this._fullscreenToastShown = false; }, 3000);
            return;
        }
        
        // Fallback на маленький Toast
        const toastEl = document.getElementById('cartToast');
        if (!toastEl) return;
        
        toastEl.className = `toast align-items-center text-bg-${type} border-0`;
        document.getElementById('toastMessage').textContent = message;
        
        const toast = new bootstrap.Toast(toastEl, { delay: 3000 });
        toast.show();
    },
    // Получить корзину
    get() {
        return JSON.parse(localStorage.getItem(this.STORAGE_KEY) || '[]');
    },
    
    // Сохранить корзину
    save(cart) {
        localStorage.setItem(this.STORAGE_KEY, JSON.stringify(cart));
        this.updateCounter();
    },
    
    // Добавить товар
    add(product, quantity = 1) {
        let cart = this.get();
        const existing = cart.find(item => item.id === product.id);
        
        if (existing) {
            existing.quantity += quantity;
        } else {
            cart.push({
                id: product.id,
                name: product.name,
                price: product.price,
                image: product.image,
                quantity: quantity
            });
        }
        
        this.save(cart);
        this.syncToBackend(product.id, product.name, product.price, product.image, quantity);
        return true;
    },
    
    // Обновить количество
    updateQuantity(id, quantity) {
        let cart = this.get();
        const item = cart.find(i => i.id === id);
        
        if (item) {
            if (quantity <= 0) {
                return this.remove(id);
            }
            item.quantity = quantity;
            this.save(cart);
            this.syncUpdateBackend(id, quantity);
            
            // Обновить input в каталоге
            this.updateCatalogInput(id, quantity);
            
            // Обновить сумму в строке таблицы корзины
            this.updateCartRowSubtotal(id);
            return true;
        }
        return false;
    },
    
    // Обновить сумму в строке корзины
    updateCartRowSubtotal(id) {
        const cart = this.get();
        const item = cart.find(i => i.id === id);
        if (!item) return;
        
        const row = document.querySelector(`tr[data-id="${id}"]`);
        if (row) {
            const subtotalCell = row.querySelector('td:nth-child(4)');
            if (subtotalCell) {
                const subtotal = item.price * item.quantity;
                subtotalCell.textContent = new Intl.NumberFormat('ru-RU').format(subtotal) + ' ₽';
            }
        }
    },
    
    // Удалить товар
    remove(id) {
        let cart = this.get().filter(item => item.id !== id);
        this.save(cart);
        this.syncRemoveBackend(id);
        
        // Удалить строку из таблицы на странице корзины
        this.removeCartRow(id);
        
        // Вернуть кнопку в каталоге
        this.resetCatalogButton(id);
        return true;
    },
    
    // Удалить строку товара из таблицы корзины
    removeCartRow(id) {
        // Ищем строку по data-id
        const row = document.querySelector(`tr[data-id="${id}"]`);
        if (row) {
            row.remove();
        }
        
        // Если корзина пуста - показать сообщение
        const cart = this.get();
        if (cart.length === 0) {
            this.showEmptyCart();
        }
    },
    
    // Показать пустую корзину
    showEmptyCart() {
        const cartTable = document.getElementById('cart-items');
        if (!cartTable) return;
        
        // Найти tbody и заменить содержимое
        const tbody = cartTable.querySelector('tbody');
        if (tbody) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="5" class="text-center py-5">
                        <i class="bi bi-cart-x display-1 text-muted mb-3"></i>
                        <h4>Корзина пуста 😔</h4>
                        <p class="text-muted">Добавьте товары из каталога</p>
                        <a href="/catalog" class="btn btn-primary mt-2">Перейти в каталог</a>
                    </td>
                </tr>
            `;
        }
        
        // Скрыть итоговые блоки
        const summaryEl = document.querySelector('.card-body:has(.h3.fw-bold.text-warning)');
        if (summaryEl) {
            summaryEl.innerHTML = '';
        }
    },
    
    // Обновить input количества в каталоге
    updateCatalogInput(id, quantity) {
        const controls = document.querySelector(`.quantity-controls[data-product-id="${id}"]`);
        if (controls) {
            const input = controls.querySelector('.qty-input');
            if (input) input.value = quantity;
        }
    },
    
    // Сбросить кнопку в каталоге (при удалении товара)
    resetCatalogButton(id) {
        const addBtn = document.querySelector(`.btn-add-to-cart[data-id="${id}"]`);
        if (addBtn) {
            const parent = addBtn.parentElement;
            const controls = parent.querySelector('.quantity-controls');
            if (controls) {
                controls.classList.add('d-none');
                addBtn.classList.remove('d-none');
                const input = controls.querySelector('.qty-input');
                input.value = 1;
            }
        }
    },
    
    // Очистить корзину
    clear() {
        localStorage.removeItem(this.STORAGE_KEY);
        this.updateCounter();
        this.syncClearBackend();
    },
    
    // Обновить счётчик в навбаре
    updateCounter() {
        const cart = this.get();
        const total = cart.reduce((sum, item) => sum + item.quantity, 0);
        const badge = document.querySelector('.cart-counter');
        
        if (badge) {
            badge.textContent = total;
            badge.style.display = total > 0 ? 'inline-block' : 'none';
        }
        
        // Обновляем на странице корзины
        this.updateCartPage();
    },
    
    // Показать уведомление
    /*showToast(message, type = 'success') {
        const toastEl = document.getElementById('cartToast');
        if (!toastEl) return;
        
        const toastBody = toastEl.querySelector('.toast-body');
        toastEl.className = `toast align-items-center text-bg-${type} border-0`;
        document.getElementById('toastMessage').textContent = message;
        
        const toast = new bootstrap.Toast(toastEl, { delay: 3000 });
        toast.show();
    },*/
    
    // Синхронизация с бэкендом (опционально)
    syncToBackend(id, name, price, image, quantity) {
        fetch('/api/cart/add', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({id, name, price, image, quantity})
        }).catch(() => {}); // Игнорируем ошибки, если бэкенд не настроен
    },
    
    syncUpdateBackend(id, quantity) {
        fetch('/api/cart/update', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({id, quantity})
        }).catch(() => {});
    },
    
    syncRemoveBackend(id) {
        fetch('/api/cart/remove', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({id})
        }).catch(() => {});
    },
    
    syncClearBackend() {
        fetch('/api/cart/clear', {method: 'POST'}).catch(() => {});
    },
    
    // Обновление на странице корзины
    updateCartPage() {
        if (!document.getElementById('cart-items')) return;
        
        const cart = this.get();
        const total = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
        const count = cart.reduce((sum, item) => sum + item.quantity, 0);
        
        // Обновляем сумму и количество
        const totalEl = document.querySelector('.h3.fw-bold.text-warning');
        const countEl = document.querySelector('.text-white-50 + .text-white.fw-bold');
        
        if (totalEl) totalEl.textContent = new Intl.NumberFormat('ru-RU').format(total) + ' ₽';
        if (countEl) countEl.textContent = count;
        
        // Если корзина пуста - перезагружаем
        if (cart.length === 0 && window.location.pathname === '/cart') {
            window.location.reload();
        }
    }
};

// Инициализация при загрузке
document.addEventListener('DOMContentLoaded', function() {
    CartManager.updateCounter();
    
    // Восстановить состояние кнопок на странице каталога
    restoreCatalogButtons();
    
    // Загрузить информацию о пользователе
    loadCurrentUser();
    
    // Обработчики для страницы корзины
    if (document.getElementById('cart-items')) {
        initCartPage();
    }
    
    // Обработчики для кнопок "В корзину"
    document.querySelectorAll('.btn-add-to-cart').forEach(btn => {
        btn.addEventListener('click', function() {
            const product = JSON.parse(this.dataset.product);
            CartManager.add(product);
            // Маленький toast справа снизу (без видео)
            CartManager.showToast('"' + product.name + '" добавлен в корзину!', 'success', false);
            // Переключаем на блок количества
            switchToQuantityControls(this);
        });
    });
    
    // Обработчики для кнопок +/- в каталоге
    document.querySelectorAll('.quantity-controls .btn-qty').forEach(btn => {
        btn.addEventListener('click', function() {
            const controls = this.closest('.quantity-controls');
            const input = controls.querySelector('.qty-input');
            const productId = parseInt(controls.dataset.productId);
            let value = parseInt(input.value) || 1;
            
            if (this.dataset.action === 'increase') {
                value++;
            } else {
                value = Math.max(0, value - 1); // Разрешаем 0 для удаления
            }
            
            input.value = value;
            CartManager.updateQuantity(productId, value);
        });
    });
    
    // Обработчики для прямого ввода количества в каталоге
    document.querySelectorAll('.quantity-controls .qty-input').forEach(input => {
        input.addEventListener('change', function() {
            const productId = parseInt(this.dataset.productId);
            const value = parseInt(this.value) || 1;
            CartManager.updateQuantity(productId, value);
        });
    });
});

// Восстановить состояние кнопок при загрузке страницы
function restoreCatalogButtons() {
    const cart = CartManager.get();
    
    cart.forEach(item => {
        const addBtn = document.querySelector(`.btn-add-to-cart[data-id="${item.id}"]`);
        if (addBtn) {
            const controls = addBtn.parentElement.querySelector('.quantity-controls');
            if (controls) {
                const input = controls.querySelector('.qty-input');
                input.value = item.quantity;
                addBtn.classList.add('d-none');
                controls.classList.remove('d-none');
            }
        }
    });
}

// Переключение на блок управления количеством
function switchToQuantityControls(btn) {
    const productId = parseInt(btn.dataset.id);
    const parent = btn.parentElement;
    const controls = parent.querySelector('.quantity-controls');
    
    if (controls) {
        btn.classList.add('d-none');
        controls.classList.remove('d-none');
        
        // Установить текущее количество из корзины
        const cart = CartManager.get();
        const item = cart.find(i => i.id === productId);
        if (item) {
            const input = controls.querySelector('.qty-input');
            input.value = item.quantity;
        }
    }
}

// Заполнить форму оформления заказа данными из профиля
async function fillCheckoutFormFromProfile() {
    // Проверяем, авторизован ли пользователь
    try {
        const authResponse = await fetch('/api/auth/current');
        const authData = await authResponse.json();
        
        if (!authData.user) {
            return; // Пользователь не авторизован
        }
        
        // Заполняем email из данных авторизации
        const emailInput = document.getElementById('email');
        if (emailInput && authData.user.email) {
            emailInput.value = authData.user.email;
        }
        
        // Загружаем профиль для остальных полей
        const profileResponse = await fetch('/api/profile');
        const profileData = await profileResponse.json();
        
        if (profileData.profile) {
            const profile = profileData.profile;
            
            // Заполняем форму
            const fioInput = document.getElementById('fio');
            const phoneInput = document.getElementById('phone');
            const addressInput = document.getElementById('address');
            
            if (fioInput && profile.name) {
                fioInput.value = profile.name;
            }
            
            if (phoneInput && profile.phone) {
                phoneInput.value = profile.phone;
            }
            
            if (addressInput && profile.address) {
                addressInput.value = profile.address;
            }
        }
    } catch (error) {
        console.log('Не удалось загрузить профиль для автозаполнения', error);
    }
}

// Инициализация страницы корзины
function initCartPage() {
    // Загрузить профиль и подставить данные в форму оформления заказа
    fillCheckoutFormFromProfile();
    
    // Изменение количества
    document.querySelectorAll('.btn-quantity').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = parseInt(this.dataset.id);
            const action = this.dataset.action;
            const input = document.querySelector(`.quantity-input[data-id="${id}"]`);
            let value = parseInt(input.value) || 1;
            
            value = action === 'increase' ? value + 1 : Math.max(0, value - 1);
            input.value = value;
            
            if (value === 0) {
                CartManager.remove(id);
                CartManager.showToast('Товар удалён');
            } else {
                CartManager.updateQuantity(id, value);
                CartManager.showToast('Количество обновлено', 'success', false);
            }
        });
    });
    
    // Прямой ввод количества
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', function() {
            const id = parseInt(this.dataset.id);
            const value = parseInt(this.value) || 1;
            
            if (value <= 0) {
                CartManager.remove(id);
                CartManager.showToast('Товар удалён');
            } else {
                CartManager.updateQuantity(id, value);
            }
        });
    });
    
    // Удаление товара
    document.querySelectorAll('.btn-remove').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = parseInt(this.dataset.id);
            if (confirm('Удалить этот товар из корзины?')) {
                CartManager.remove(id);
                CartManager.showToast('Товар удалён');
            }
        });
    });
    
    // Очистка корзины
    document.getElementById('clear-cart')?.addEventListener('click', function() {
        if (confirm('Очистить всю корзину?')) {
            CartManager.clear();
            CartManager.showToast('Корзина очищена');
            setTimeout(() => window.location.reload(), 500);
        }
    });
    
    // Оформление заказа
    document.getElementById('checkout-btn')?.addEventListener('click', function() {
        // Открывается модальное окно Bootstrap автоматически
    });
    
    // Обработка формы оформления заказа
    document.getElementById('checkout-form')?.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const form = this;
        const submitBtn = document.getElementById('submit-order');
        const spinner = submitBtn.querySelector('.spinner-border');
        const btnText = submitBtn.querySelector('.btn-text');
        
        // Блокируем кнопку
        submitBtn.disabled = true;
        spinner.classList.remove('d-none');
        btnText.textContent = 'Отправка...';
        
        // Собираем данные формы
        const formData = {
            fio: document.getElementById('fio').value,
            email: document.getElementById('email').value,
            phone: document.getElementById('phone').value,
            address: document.getElementById('address').value,
            payment: document.getElementById('payment').value,
            items: window.cartData || [],
            total: window.cartTotal || 0
        };
        
        try {
            const response = await fetch('/api/cart/order', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify(formData)
            });
            
            const result = await response.json();
            
            if (result.success) {
                // Закрываем модальное окно
                const modal = bootstrap.Modal.getInstance(document.getElementById('checkoutModal'));
                modal.hide();
                
                // Очищаем корзину в localStorage
                CartManager.clear();

                // Очищаем глобальные переменные корзины
                window.cartData = [];
                window.cartTotal = 0;

                // Показываем успех
                CartManager.showToast('Заказ #' + result.orderId + ' оформлен!', 'success', false);
                
                // Перезагружаем страницу
                window.location.reload();
            }
        } catch (error) {
            CartManager.showToast('Ошибка соединения', 'danger', false);
        } finally {
            // Разблокируем кнопку
            submitBtn.disabled = false;
            spinner.classList.add('d-none');
            btnText.textContent = 'Подтвердить заказ';
        }
    });
}

// Анимация кнопки при добавлении
function animateButton(button) {
    const original = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '<i class="bi bi-check-lg me-2"></i>Добавлено!';
    button.classList.replace('btn-light', 'btn-success');
    
    setTimeout(() => {
        button.innerHTML = original;
        button.disabled = false;
        button.classList.replace('btn-success', 'btn-light');
    }, 2000);
}

// Загрузка информации о текущем пользователе
async function loadCurrentUser() {
    try {
        const response = await fetch('/api/auth/current');
        const data = await response.json();
        
        console.log('User data:', data);
        
        if (data.user) {
            updateNavbarUser(data.user);
        } else {
            updateNavbarUser(null);
        }
    } catch (error) {
        console.log('Не удалось загрузить данные пользователя', error);
    }
}

// Обновление навбара с данными пользователя
async function updateNavbarUser(user) {
    const authItem = document.getElementById('auth-nav-item');
    if (!authItem) return;
    
    if (user) {
        // Попробовать загрузить профиль для получения аватара
        let avatarHtml = '';
        try {
            const profileResponse = await fetch('/api/profile');
            const profileData = await profileResponse.json();
            if (profileData.profile && profileData.profile.avatar) {
                avatarHtml = `<img src="${profileData.profile.avatar}" alt="Аватар" class="nav-avatar rounded-circle me-2" style="width: 28px; height: 28px; object-fit: cover;">`;
                // Сохранить профиль в глобальную переменную для использования в корзине
                window.userProfile = profileData.profile;
            }
        } catch (e) {
            console.log('Не удалось загрузить аватар');
        }
        
        let dropdownContent = `
            <li><span class="dropdown-item-text text-muted small">${user.email}</span></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="/profile"><i class="bi bi-person me-2"></i>Профиль</a></li>
        `;
        
        // Если пользователь админ - добавить ссылку в админку
        if (user.is_admin) {
            dropdownContent += `
                <li><a class="dropdown-item" href="/admin"><i class="bi bi-gear me-2"></i>Админ-панель</a></li>
            `;
        }
        
        dropdownContent += `<li><hr class="dropdown-divider"></li><li><a class="dropdown-item text-danger" href="/logout">Выйти</a></li>`;
        
        // Заменить на выпадающий список с именем пользователя и аватаром
        authItem.outerHTML = `
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                    ${avatarHtml}
                    <span>${user.name}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    ${dropdownContent}
                </ul>
            </li>
        `;
    }
    // Если user === null, оставляем "Вход" как есть
}