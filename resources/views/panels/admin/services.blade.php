@extends(Auth::user()->role === 'director' ? 'layouts.director' : 'layouts.admin')

@php
    $servicesIndexRoute = Auth::user()->role === 'director' ? 'director.services' : 'admin.services';
@endphp

@section('title', 'Услуги — Шоколад')

@section('content')
    <div class="header">
        <h1>Услуги</h1>
        <button class="btn-add" style="background: var(--gold); border: none; padding: 10px 20px; border-radius: 5px; font-weight: 600; cursor: pointer;" onclick="openModal('modal-service-add')">+ Новая услуга</button>
    </div>

    <!-- Форма поиска и фильтров -->
    <div class="content-card" style="margin-bottom: 20px; padding: 20px;">
        <form method="GET" action="{{ route($servicesIndexRoute) }}" style="display: flex; gap: 15px; align-items: center; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 200px;">
                <input type="text" name="search" placeholder="Поиск по названию или описанию..." 
                       value="{{ request('search') }}" 
                       style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px;">
            </div>
            
            <div style="min-width: 120px;">
                <input type="number" name="price_min" placeholder="Мин. цена" 
                       value="{{ request('price_min') }}" 
                       style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px;">
            </div>
            
            <div style="min-width: 120px;">
                <input type="number" name="price_max" placeholder="Макс. цена" 
                       value="{{ request('price_max') }}" 
                       style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px;">
            </div>
            
            <div style="min-width: 120px;">
                <input type="number" name="duration_min" placeholder="Мин. длительность" 
                       value="{{ request('duration_min') }}" 
                       style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px;">
            </div>
            
            <div style="min-width: 120px;">
                <input type="number" name="duration_max" placeholder="Макс. длительность" 
                       value="{{ request('duration_max') }}" 
                       style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px;">
            </div>
            
            <button type="submit" style="background: var(--chocolate); color: white; border: none; padding: 10px 20px; border-radius: 5px; font-weight: 600; cursor: pointer;">
                Найти
            </button>
            
            <a href="{{ route($servicesIndexRoute) }}" style="background: #f5f5f5; color: #666; text-decoration: none; padding: 10px 20px; border-radius: 5px; font-weight: 600; display: inline-block;">
                Сбросить
            </a>
        </form>
    </div>

    <div class="content-card">
        <ul class="service-list">
            @forelse($services as $service)
            <li class="service-item" style="list-style: none; padding: 15px; border-bottom: 1px solid #f5f5f5; display: flex; justify-content: space-between; align-items: center;">
                <div class="service-info">
                    <h4 style="margin: 0; color: var(--chocolate);">{{ $service->name }}</h4>
                    <p style="margin: 5px 0 0; font-size: 13px; color: #888;">{{ $service->description }} ({{ $service->duration_minutes }} мин)</p>
                </div>
                <div style="display: flex; align-items: center;">
                    <div class="service-price" style="margin-right: 20px; font-weight: 600; color: var(--chocolate);">{{ number_format($service->price, 0, '.', ' ') }} ₽</div>
                    <button class="btn-edit" style="color: var(--gold); background: none; border: none; cursor: pointer; font-weight: 600;" onclick="editService({{ json_encode($service) }})">Редакт.</button>
                    <form action="{{ Auth::user()->role === 'director' ? route('director.services.delete', $service->id) : route('admin.services.delete', $service->id) }}" method="POST" class="ajax-form" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="color: #f44336; background: none; border: none; cursor: pointer; margin-left: 10px;" onclick="return confirm('Удалить услугу?')">×</button>
                    </form>
                </div>
            </li>
            @empty
            <li class="service-item">Услуг пока нет</li>
            @endforelse
        </ul>
        
        @if($services->hasPages())
            <div style="margin-top: 20px; text-align: center;">
                {{ $services->links() }}
            </div>
        @endif
    </div>
@endsection

@push('styles')
<style>
/* Pagination Styles - переопределяем все стили */
.pagination {
    display: flex !important;
    justify-content: center !important;
    gap: 8px !important;
    margin: 30px 0 !important;
    flex-wrap: wrap !important;
    list-style: none !important;
    padding: 0 !important;
}

.pagination .page-item {
    display: inline-block !important;
}

.pagination .page-link {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    min-width: 44px !important;
    height: 44px !important;
    padding: 0 12px !important;
    border: 2px solid transparent !important;
    border-radius: 8px !important;
    background: #ffffff !important;
    color: #3E2723 !important;
    text-decoration: none !important;
    font-size: 14px !important;
    font-weight: 600 !important;
    font-family: 'Montserrat', sans-serif !important;
    transition: all 0.3s ease !important;
    box-shadow: 0 2px 8px rgba(62, 39, 35, 0.1) !important;
    position: relative !important;
    overflow: hidden !important;
}

/* Переопределяем Tailwind стили для SVG */
.pagination .page-link svg {
    width: 16px !important;
    height: 16px !important;
    display: none !important; /* Скрываем SVG */
}

.pagination .page-link:hover {
    background: #D4AF37 !important;
    color: #3E2723 !important;
    border-color: #D4AF37 !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3) !important;
}

.pagination .page-item.active .page-link {
    background: #3E2723 !important;
    color: #ffffff !important;
    border-color: #3E2723 !important;
    box-shadow: 0 4px 12px rgba(62, 39, 35, 0.3) !important;
    transform: translateY(-1px) !important;
}

.pagination .page-item.disabled .page-link {
    background: #f5f5f5 !important;
    color: #999 !important;
    border-color: #e0e0e0 !important;
    cursor: not-allowed !important;
    transform: none !important;
    box-shadow: none !important;
}

.pagination .page-item.disabled .page-link:hover {
    transform: none !important;
    box-shadow: none !important;
}

/* Убираем все Tailwind классы для пагинации */
.pagination .page-link[class*="w-"],
.pagination .page-link[class*="h-"] {
    width: auto !important;
    height: auto !important;
    min-width: 44px !important;
    min-height: 44px !important;
}

.pagination .page-item:first-child .page-link,
.pagination .page-item:last-child .page-link {
    font-size: 18px !important;
    font-weight: 700 !important;
}

/* Скрываем SVG и показываем текстовые стрелки */
.pagination .page-link::before {
    content: attr(data-arrow) !important;
    font-size: 18px !important;
    font-weight: 700 !important;
}

.pagination .page-item:first-child .page-link::before {
    content: "«" !important;
}

.pagination .page-item:last-child .page-link::before {
    content: "»" !important;
}

/* Responsive */
@media (max-width: 768px) {
    .pagination {
        gap: 4px !important;
        margin: 20px 0 !important;
    }
    
    .pagination .page-link {
        min-width: 36px !important;
        height: 36px !important;
        font-size: 12px !important;
        padding: 0 8px !important;
    }
    
    .pagination .page-item:first-child .page-link::before,
    .pagination .page-item:last-child .page-link::before {
        font-size: 16px !important;
    }
}

@media (max-width: 480px) {
    .pagination {
        gap: 2px !important;
    }
    
    .pagination .page-link {
        min-width: 32px !important;
        height: 32px !important;
        font-size: 11px !important;
        padding: 0 6px !important;
    }
    
    .pagination .page-item:first-child .page-link::before,
    .pagination .page-item:last-child .page-link::before {
        font-size: 14px !important;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Заменяем SVG на текстовые стрелки
    const paginationLinks = document.querySelectorAll('.pagination .page-link');
    
    paginationLinks.forEach((link, index) => {
        // Скрываем все SVG внутри пагинации
        const svg = link.querySelector('svg');
        if (svg) {
            svg.style.display = 'none';
        }
        
        // Определяем тип ссылки и добавляем текстовую стрелку
        const pageItem = link.closest('.page-item');
        const isFirst = pageItem && pageItem.previousElementSibling === null;
        const isLast = pageItem && pageItem.nextElementSibling === null;
        
        if (isFirst) {
            link.innerHTML = '«';
            link.classList.add('arrow-prev');
        } else if (isLast) {
            link.innerHTML = '»';
            link.classList.add('arrow-next');
        }
    });
    
    // Принудительно применяем стили
    const style = document.createElement('style');
    style.textContent = `
        .pagination .page-link svg { display: none !important; }
        .pagination .page-link.arrow-prev,
        .pagination .page-link.arrow-next {
            font-size: 18px !important;
            font-weight: 700 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
    `;
    document.head.appendChild(style);
});
</script>
@endpush

@section('modals')
    <!-- Modals -->
    <div class="modal-overlay" id="modal-service-add">
        <div class="modal-container">
            <div class="modal-header">
                <h3>Добавить услугу</h3>
                <button class="close-modal" onclick="closeModal('modal-service-add')">&times;</button>
            </div>
            <form action="{{ Auth::user()->role === 'director' ? route('director.services.store') : route('admin.services.store') }}" method="POST" class="ajax-form modal-form" onsubmit="handleFormSubmit(event)">
                @csrf
                <div class="form-group">
                    <label>Название</label>
                    <input type="text" name="name" required>
                </div>
                <div class="form-group">
                    <label>Описание</label>
                    <textarea name="description" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label>Цена (₽)</label>
                    <input type="number" name="price" required>
                </div>
                <div class="form-group">
                    <label>Длительность (мин)</label>
                    <input type="number" name="duration_minutes" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeModal('modal-service-add')">Отмена</button>
                    <button type="submit" class="btn-confirm">Создать</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal-overlay" id="modal-service-edit">
        <div class="modal-container">
            <div class="modal-header">
                <h3>Редактировать услугу</h3>
                <button class="close-modal" onclick="closeModal('modal-service-edit')">&times;</button>
            </div>
            <form action="" method="POST" class="ajax-form modal-form" id="form-service-edit">
                @csrf
                @method('PATCH')
                <div class="form-group">
                    <label>Название</label>
                    <input type="text" name="name" required>
                </div>
                <div class="form-group">
                    <label>Описание</label>
                    <textarea name="description" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label>Цена (₽)</label>
                    <input type="number" name="price" required>
                </div>
                <div class="form-group">
                    <label>Длительность (мин)</label>
                    <input type="number" name="duration_minutes" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeModal('modal-service-edit')">Отмена</button>
                    <button type="submit" class="btn-confirm">Сохранить</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function editService(service) {
            const form = document.getElementById('form-service-edit');
            const baseUrl = '{{ Auth::user()->role === "director" ? url("/director/services") : url("/admin/services") }}';
            form.action = `${baseUrl}/${service.id}`;
            form.querySelector('[name="name"]').value = service.name;
            form.querySelector('[name="description"]').value = service.description || '';
            form.querySelector('[name="price"]').value = service.price;
            form.querySelector('[name="duration_minutes"]').value = service.duration_minutes;
            openModal('modal-service-edit');
        }
    </script>
@endsection

