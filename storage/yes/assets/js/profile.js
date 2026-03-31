/**
 * Скрипты для страницы профиля
 */

document.addEventListener('DOMContentLoaded', function() {
    // Загрузка данных профиля при загрузке страницы
    loadProfile();
    
    // Обработчик формы профиля
    document.getElementById('profile-form')?.addEventListener('submit', async function(e) {
        e.preventDefault();
        await saveProfile();
    });
    
    // Обработчик загрузки аватара
    document.getElementById('avatar-input')?.addEventListener('change', async function(e) {
        if (this.files && this.files[0]) {
            await uploadAvatar(this.files[0]);
        }
    });
});

/**
 * Загрузить данные профиля
 */
async function loadProfile() {
    try {
        const response = await fetch('/api/profile');
        const data = await response.json();
        
        if (data.profile) {
            const profile = data.profile;
            
            // Заполнить форму
            document.getElementById('name').value = profile.name || '';
            document.getElementById('email').value = profile.email || '';
            document.getElementById('phone').value = profile.phone || '';
            document.getElementById('address').value = profile.address || '';
            
            // Обновить аватар
            if (profile.avatar) {
                updateAvatarDisplay(profile.avatar);
            }
        }
    } catch (error) {
        console.error('Ошибка загрузки профиля:', error);
    }
}

/**
 * Сохранить профиль
 */
async function saveProfile() {
    const form = document.getElementById('profile-form');
    const saveBtn = document.getElementById('save-btn');
    const btnText = saveBtn.querySelector('.btn-text');
    const spinner = saveBtn.querySelector('.spinner-border');
    
    // Блокируем кнопку
    saveBtn.disabled = true;
    btnText.textContent = 'Сохранение...';
    spinner.classList.remove('d-none');
    
    const formData = {
        name: document.getElementById('name').value,
        address: document.getElementById('address').value
    };
    
    try {
        const response = await fetch('/api/profile/update', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(formData)
        });
        
        const result = await response.json();
        
        if (result.success) {
            showToast('Профиль успешно обновлён!', 'success');
            
            // Обновить имя в навбаре если изменилось
            if (result.profile && result.profile.name) {
                updateNavbarName(result.profile.name);
            }
        } else {
            showToast(result.error || 'Ошибка сохранения', 'danger');
        }
    } catch (error) {
        showToast('Ошибка соединения', 'danger');
    } finally {
        // Разблокировать кнопку
        saveBtn.disabled = false;
        btnText.textContent = 'Сохранить изменения';
        spinner.classList.add('d-none');
    }
}

/**
 * Загрузить аватар
 */
async function uploadAvatar(file) {
    const formData = new FormData();
    formData.append('avatar', file);
    
    // Показать индикатор загрузки
    const container = document.querySelector('.avatar-container');
    container.classList.add('avatar-uploading');
    
    try {
        const response = await fetch('/api/profile/avatar', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Обновить отображение аватара
            updateAvatarDisplay(result.avatar);
            showToast('Аватар обновлён!', 'success');
        } else {
            showToast(result.error || 'Ошибка загрузки', 'danger');
        }
    } catch (error) {
        showToast('Ошибка соединения', 'danger');
    } finally {
        container.classList.remove('avatar-uploading');
        // Очистить input
        document.getElementById('avatar-input').value = '';
    }
}

/**
 * Обновить отображение аватара
 */
function updateAvatarDisplay(avatarUrl) {
    const preview = document.getElementById('avatar-preview');
    
    if (preview) {
        // Если это placeholder - заменяем на img
        if (preview.classList.contains('profile-avatar-placeholder')) {
            const img = document.createElement('img');
            img.id = 'avatar-preview';
            img.className = 'rounded-circle profile-avatar';
            img.alt = 'Аватар';
            img.src = avatarUrl + '?t=' + Date.now(); // Add cache buster
            preview.replaceWith(img);
        } else {
            // Просто обновляем src
            preview.src = avatarUrl + '?t=' + Date.now();
        }
    }
    
    // Также обновить аватар в навбаре
    updateNavbarAvatar(avatarUrl);
}

/**
 * Обновить имя в навбаре
 */
function updateNavbarName(name) {
    const dropdownToggle = document.querySelector('.nav-link.dropdown-toggle');
    if (dropdownToggle) {
        dropdownToggle.innerHTML = `<i class="bi bi-person-circle me-1"></i>${name}`;
    }
}

/**
 * Обновить аватар в навбаре
 */
function updateNavbarAvatar(avatarUrl) {
    let navAvatar = document.querySelector('.nav-avatar');
    
    if (avatarUrl) {
        if (!navAvatar) {
            // Создать элемент аватара в навбаре
            const authItem = document.getElementById('auth-nav-item');
            if (authItem) {
                // Заменить dropdown на аватар + dropdown
                const dropdown = authItem.closest('.dropdown');
                if (dropdown) {
                    const name = dropdown.querySelector('.dropdown-toggle').textContent.trim();
                    navAvatar = document.createElement('div');
                    navAvatar.className = 'nav-avatar-container me-2';
                    navAvatar.innerHTML = `
                        <img src="${avatarUrl}" alt="Аватар" class="nav-avatar rounded-circle" style="width: 32px; height: 32px; object-fit: cover;">
                    `;
                    dropdown.insertBefore(navAvatar, dropdown.firstChild);
                }
            }
        } else {
            navAvatar.src = avatarUrl + '?t=' + Date.now();
        }
    }
}

/**
 * Показать toast
 */
function showToast(message, type = 'success') {
    const toastEl = document.getElementById('profile-toast');
    if (!toastEl) return;
    
    const toastBody = toastEl.querySelector('.toast-body');
    toastEl.className = `toast align-items-center text-bg-${type} border-0`;
    toastBody.textContent = message;
    
    const toast = new bootstrap.Toast(toastEl, { delay: 3000 });
    toast.show();
}
