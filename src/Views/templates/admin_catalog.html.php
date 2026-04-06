<?php
/**
 * Шаблон страницы управления каталогом товаров
 * Доступные переменные:
 * - $texts - массив текстов из storage/templates/admin.json
 * - $catalogText - тексты для каталога
 */

$texts = $texts ?? [];
$catalogText = $texts['catalog'] ?? [];

// Получаем категории из модели Product
$categories = $categories ?? [];
$products = $products ?? [];
?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><?= htmlspecialchars($catalogText['title'] ?? 'Управление каталогом') ?></h2>
        <div>
            <a href="/admin" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> <?= htmlspecialchars($catalogText['back'] ?? 'Назад в панель') ?>
            </a>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#productModal">
                <i class="bi bi-plus-lg"></i> <?= htmlspecialchars($catalogText['addProduct'] ?? 'Добавить товар') ?>
            </button>
        </div>
    </div>

    <!-- Поиск и фильтр -->
    <div class="row mb-4">
        <div class="col-md-6">
            <input type="text" class="form-control" id="searchInput" 
                   placeholder="<?= htmlspecialchars($catalogText['searchPlaceholder'] ?? 'Поиск по названию...') ?>">
        </div>
        <div class="col-md-4">
            <select class="form-select" id="categoryFilter">
                <option value=""><?= htmlspecialchars($catalogText['allCategories'] ?? 'Все категории') ?></option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= htmlspecialchars($category) ?>"><?= htmlspecialchars($category) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <!-- Таблица товаров -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="productsTable">
                    <thead class="table-light">
                        <tr>
                            <th><?= htmlspecialchars($catalogText['id'] ?? 'ID') ?></th>
                            <th><?= htmlspecialchars($catalogText['image'] ?? 'Изображение') ?></th>
                            <th><?= htmlspecialchars($catalogText['name'] ?? 'Название') ?></th>
                            <th><?= htmlspecialchars($catalogText['category'] ?? 'Категория') ?></th>
                            <th><?= htmlspecialchars($catalogText['price'] ?? 'Цена') ?></th>
                            <th><?= htmlspecialchars($catalogText['actions'] ?? 'Действия') ?></th>
                        </tr>
                    </thead>
                    <tbody id="productsTableBody">
                        <?= $productsHtml ?? '' ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно для добавления/редактирования товара -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalLabel">
                    <?= htmlspecialchars($catalogText['addProduct'] ?? 'Добавить товар') ?>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="productForm">
                    <input type="hidden" id="productId" name="id" value="">
                    
                    <div class="mb-3">
                        <label for="productName" class="form-label">
                            <?= htmlspecialchars($catalogText['name'] ?? 'Название') ?> *
                        </label>
                        <input type="text" class="form-control" id="productName" name="name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="productDescription" class="form-label">
                            <?= htmlspecialchars($catalogText['description'] ?? 'Описание') ?>
                        </label>
                        <textarea class="form-control" id="productDescription" name="description" rows="4"></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="productPrice" class="form-label">
                                <?= htmlspecialchars($catalogText['price'] ?? 'Цена') ?> *
                            </label>
                            <input type="number" class="form-control" id="productPrice" name="price" 
                                   min="1" step="1" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="productCategory" class="form-label">
                                <?= htmlspecialchars($catalogText['category'] ?? 'Категория') ?> *
                            </label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="productCategory" name="category" 
                                       list="categoryList" required>
                                <datalist id="categoryList">
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?= htmlspecialchars($category) ?>">
                                    <?php endforeach; ?>
                                </datalist>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="productImage" class="form-label">
                            <?= htmlspecialchars($catalogText['image'] ?? 'Изображение') ?>
                        </label>
                        <input type="text" class="form-control" id="productImage" name="image" 
                               placeholder="<?= htmlspecialchars($catalogText['imageHint'] ?? 'URL или путь к изображению') ?>"
                               value="/assets/img/no-image.jpg">
                        <small class="text-muted"><?= htmlspecialchars($catalogText['imageHint'] ?? 'Например: /assets/img/photo.jpg или https://example.com/img.jpg') ?></small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <?= htmlspecialchars($catalogText['cancel'] ?? 'Отмена') ?>
                </button>
                <button type="button" class="btn btn-primary" id="saveProductBtn">
                    <?= htmlspecialchars($catalogText['save'] ?? 'Сохранить') ?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно подтверждения удаления -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">
                    <?= htmlspecialchars($catalogText['deleteConfirm'] ?? 'Подтверждение удаления') ?>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?= htmlspecialchars($catalogText['confirmDelete'] ?? 'Вы уверены, что хотите удалить этот товар?') ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <?= htmlspecialchars($catalogText['cancel'] ?? 'Отмена') ?>
                </button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                    <?= htmlspecialchars($catalogText['delete'] ?? 'Удалить') ?>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Данные товаров для JavaScript
const productsData = <?= json_encode(array_values($products), JSON_UNESCAPED_UNICODE) ?>;
let categoriesData = <?= json_encode($categories, JSON_UNESCAPED_UNICODE) ?>;

document.addEventListener('DOMContentLoaded', function() {
    const productModal = new bootstrap.Modal(document.getElementById('productModal'));
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    
    let currentProductId = null;
    let products = productsData;
    
    // Поиск
    document.getElementById('searchInput').addEventListener('input', function(e) {
        filterProducts();
    });
    
    // Фильтр по категории
    document.getElementById('categoryFilter').addEventListener('change', function(e) {
        filterProducts();
    });
    
    function filterProducts() {
        const search = document.getElementById('searchInput').value.toLowerCase();
        const category = document.getElementById('categoryFilter').value;
        
        const filtered = products.filter(p => {
            const matchSearch = !search || (p.name && p.name.toLowerCase().includes(search));
            const matchCategory = !category || p.category === category;
            return matchSearch && matchCategory;
        });
        
        renderProducts(filtered);
    }
    
    function renderProducts(productsList) {
        const tbody = document.getElementById('productsTableBody');
        
        if (productsList.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">
                        <i class="bi bi-box fs-1 d-block mb-2"></i>
                        ${<?= json_encode($catalogText['noProducts'] ?? 'Товаров пока нет', JSON_UNESCAPED_UNICODE) ?>}
                    </td>
                </tr>`;
            return;
        }
        
        tbody.innerHTML = productsList.map(p => `
            <tr data-id="${p.id}">
                <td>${p.id}</td>
                <td>
                    <img src="${p.image || '/assets/img/no-image.jpg'}" alt="${p.name}" 
                         class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                </td>
                <td>${escapeHtml(p.name)}</td>
                <td>${escapeHtml(p.category || 'Без категории')}</td>
                <td>${Number(p.price).toLocaleString()} ₽</td>
                <td>
                    <button class="btn btn-sm btn-primary me-1 btn-edit" data-id="${p.id}">
                        <i class="bi bi-pencil"></i> ${<?= json_encode($catalogText['edit'] ?? 'Редактировать', JSON_UNESCAPED_UNICODE) ?>}
                    </button>
                    <button class="btn btn-sm btn-danger btn-delete" data-id="${p.id}">
                        <i class="bi bi-trash"></i> ${<?= json_encode($catalogText['delete'] ?? 'Удалить', JSON_UNESCAPED_UNICODE) ?>}
                    </button>
                </td>
            </tr>
        `).join('');
        
        // Перепривязываем обработчики
        attachProductHandlers();
    }
    
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    function attachProductHandlers() {
        // Редактирование
        document.querySelectorAll('.btn-edit').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = parseInt(this.dataset.id);
                const product = products.find(p => p.id === id);
                if (product) {
                    openEditModal(product);
                }
            });
        });
        
        // Удаление
        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', function() {
                currentProductId = parseInt(this.dataset.id);
                deleteModal.show();
            });
        });
    }
    
    function openEditModal(product) {
        document.getElementById('productId').value = product.id;
        document.getElementById('productName').value = product.name || '';
        document.getElementById('productDescription').value = product.description || '';
        document.getElementById('productPrice').value = product.price || '';
        document.getElementById('productCategory').value = product.category || '';
        document.getElementById('productImage').value = product.image || '/assets/img/no-image.jpg';
        
        document.querySelector('#productModal .modal-title').textContent = 
            <?= json_encode($catalogText['editProduct'] ?? 'Редактировать товар', JSON_UNESCAPED_UNICODE) ?>;
        
        productModal.show();
    }
    
    // Открытие модального окна добавления
    document.querySelector('[data-bs-target="#productModal"]').addEventListener('click', function() {
        document.getElementById('productForm').reset();
        document.getElementById('productId').value = '';
        document.getElementById('productImage').value = '/assets/img/no-image.jpg';
        
        document.querySelector('#productModal .modal-title').textContent = 
            <?= json_encode($catalogText['addProduct'] ?? 'Добавить товар', JSON_UNESCAPED_UNICODE) ?>;
    });
    
    // Сохранение товара
    document.getElementById('saveProductBtn').addEventListener('click', async function() {
        const form = document.getElementById('productForm');
        
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }
        
        const id = document.getElementById('productId').value;
        const data = {
            id: id ? parseInt(id) : null,
            name: document.getElementById('productName').value,
            description: document.getElementById('productDescription').value,
            price: parseInt(document.getElementById('productPrice').value),
            category: document.getElementById('productCategory').value,
            image: document.getElementById('productImage').value || '/assets/img/no-image.jpg'
        };
        
        if (!data.name || !data.price || !data.category) {
            alert(<?= json_encode($catalogText['required'] ?? 'Заполните все обязательные поля', JSON_UNESCAPED_UNICODE) ?>);
            return;
        }
        
        if (data.price <= 0) {
            alert(<?= json_encode($catalogText['minPrice'] ?? 'Цена должна быть больше 0', JSON_UNESCAPED_UNICODE) ?>);
            return;
        }
        
        try {
            const url = id ? `/api/admin/product/${id}` : '/api/admin/product';
            const method = id ? 'PUT' : 'POST';
            
            console.log('Saving product:', url, method, data);
            
            const response = await fetch(url, {
                method: method,
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            console.log('Save result:', result);
            
            if (result.success) {
                productModal.hide();
                showAlert(result.message, 'success');
                // Небольшая задержка чтобы убедиться, что данные сохранились
                setTimeout(() => loadProducts(), 100);
            } else {
                alert(result.error || <?= json_encode($catalogText['error'] ?? 'Ошибка', JSON_UNESCAPED_UNICODE) ?>);
            }
        } catch (error) {
            console.error('Error:', error);
            alert(<?= json_encode($catalogText['error'] ?? 'Ошибка', JSON_UNESCAPED_UNICODE) ?>);
        }
    });
    
    // Подтверждение удаления
    document.getElementById('confirmDeleteBtn').addEventListener('click', async function() {
        if (!currentProductId) return;
        
        try {
            const response = await fetch(`/api/admin/product/${currentProductId}`, {
                method: 'DELETE',
                headers: {'Content-Type': 'application/json'}
            });
            
            const result = await response.json();
            
            if (result.success) {
                deleteModal.hide();
                showAlert(result.message, 'success');
                loadProducts();
            } else {
                alert(result.error || <?= json_encode($catalogText['error'] ?? 'Ошибка', JSON_UNESCAPED_UNICODE) ?>);
            }
        } catch (error) {
            console.error('Error:', error);
            alert(<?= json_encode($catalogText['error'] ?? 'Ошибка', JSON_UNESCAPED_UNICODE) ?>);
        }
        
        currentProductId = null;
    });
    
    // Загрузка товаров
    async function loadProducts() {
        try {
            console.log('Loading products...');
            const response = await fetch('/api/admin/products');
            const data = await response.json();
            
            console.log('API response:', data);
            
            products = data.products || [];
            categoriesData = data.categories || [];
            
            console.log('Products after load:', products);
            
            filterProducts();
        } catch (error) {
            console.error('Error loading products:', error);
        }
    }
    
    function showAlert(message, type) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 end-0 m-3`;
        alertDiv.style.zIndex = '9999';
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(alertDiv);
        
        setTimeout(() => {
            alertDiv.remove();
        }, 3000);
    }
    
    // Инициализация
    attachProductHandlers();
});
</script>
