<!-- profile.html.php - используется ProfileTemplate::render() -->
<!-- Шаблон генерируется напрямую в PHP, этот файл для справки -->

<!-- Секция аватара -->
<div class="card">
    <div class="card-body text-center">
        <h5 class="card-title mb-3">{{ avatar_title }}</h5>
        
        <div class="avatar-container mb-3">
            <img id="avatar-preview" src="{{ avatar_url }}" alt="Аватар" class="rounded-circle profile-avatar">
        </div>
        
        <form id="avatar-form" enctype="multipart/form-data">
            <input type="file" id="avatar-input" name="avatar" accept="image/*" class="d-none">
            <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('avatar-input').click()">
                <i class="bi bi-camera me-2"></i>{{ change_avatar }}
            </button>
        </form>
    </div>
</div>

<!-- Форма профиля -->
<form id="profile-form">
    <div class="mb-3">
        <label for="name" class="form-label">{{ name_label }}</label>
        <input type="text" class="form-control" id="name" name="name" value="{{ name }}" required>
    </div>
    
    <div class="mb-3">
        <label for="email" class="form-label">{{ email_label }}</label>
        <input type="email" class="form-control" id="email" value="{{ email }}" readonly>
    </div>
    
    <div class="mb-3">
        <label for="phone" class="form-label">{{ phone_label }}</label>
        <input type="tel" class="form-control" id="phone" value="{{ phone }}" readonly>
    </div>
    
    <div class="mb-3">
        <label for="address" class="form-label">{{ address_label }}</label>
        <textarea class="form-control" id="address" name="address" rows="3">{{ address }}</textarea>
    </div>
    
    <button type="submit" class="btn btn-primary">{{ save }}</button>
</form>
