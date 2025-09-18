@extends('layouts.app')

@section('title', 'Home - Fashion Shop')

@section('styles')
<style>/* ===== HERO SECTION ===== */
.hero {
    background: linear-gradient(135deg, #5b21b6, #a855f7);
    color: #ffffff;
    border-radius: 16px;
    padding: 4rem 2rem;
    margin-bottom: 3rem;
    text-align: center;
    position: relative;
    overflow: hidden;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
}
.hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.1), transparent);
    z-index: 0;
}
.hero-content {
    position: relative;
    z-index: 1;
}
.hero h1 {
    font-size: 2.8rem;
    font-weight: 700;
    letter-spacing: 0.5px;
    margin-bottom: 1rem;
    text-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}
.hero p {
    font-size: 1.2rem;
    font-weight: 400;
    margin-bottom: 1.5rem;
}
.hero .btn-primary {
    font-size: 1rem;
    padding: 0.75rem 2rem;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
}

/* ===== DANH MỤC ===== */
.categories-section {
    margin-bottom: 3rem;
}
.btn-category {
    background: linear-gradient(45deg, #3b82f6, #60a5fa);
    border: none;
    border-radius: 30px;
    font-size: 1.1rem;
    padding: 0.75rem 1.5rem;
    color: #ffffff;
    font-weight: 600;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}
.btn-category:hover {
    background: linear-gradient(45deg, #2563eb, #3b82f6);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}
.categories-container {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    padding: 1rem 0;
}
.category-card {
    flex: 1 1 120px;
    max-width: 150px;
    background: #ffffff;
    border-radius: 12px;
    padding: 1rem;
    text-align: center;
    transition: all 0.3s ease;
    cursor: pointer;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
}
.category-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
}
.category-icon {
    font-size: 1.8rem;
    color: #5b21b6; /* Changed to purple */
    margin-bottom: 0.5rem;
}
.category-card .card-text {
    font-weight: 500;
    font-size: 0.9rem;
    color: #1f2937;
}

/* ===== PRODUCT CARD ===== */
.product-card {
    border-radius: 12px;
    overflow: hidden;
    background: #ffffff;
    border: none;
    position: relative;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
}
.product-card img {
    height: 200px;
    object-fit: cover;
    width: 100%;
    transition: transform 0.3s ease;
}
.product-card:hover img {
    transform: scale(1.03);
}
.product-price {
    font-weight: 600;
    font-size: 1.1rem;
    color: #5b21b6; /* Changed to purple */
}
.card-text {
    font-size: 0.9rem;
    color: #4b5563;
}
.stock-status {
    font-size: 0.85rem;
    font-weight: 500;
}
.product-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    background: linear-gradient(45deg, #ef4444, #f87171);
    color: #ffffff;
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.3rem 0.6rem;
    border-radius: 8px;
    z-index: 1;
}

/* ===== BUTTONS ===== */
.btn-success, .btn-primary, .btn-wishlist {
    border-radius: 25px;
    font-size: 0.85rem;
    transition: all 0.3s ease;
}
.btn-success {
    background: linear-gradient(45deg, #5b21b6, #a855f7); /* Changed to purple */
    border: none;
}
.btn-success:hover {
    background: linear-gradient(45deg, #4f46e5, #9333ea); /* Changed to purple */
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}
.btn-primary {
    background: linear-gradient(45deg, #6366f1, #a855f7);
    border: none;
}
.btn-primary:hover {
    background: linear-gradient(45deg, #4f46e5, #9333ea);
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}
.btn-wishlist {
    background: none;
    border: none;
    color: #ef4444;
    font-size: 1.2rem;
    cursor: pointer;
}
.btn-wishlist:hover {
    transform: scale(1.15);
    color: #dc2626;
}

/* ===== CAROUSEL STYLING ===== */
.carousel {
    position: relative;
    margin-bottom: 3rem;
}
.carousel-inner {
    border-radius: 12px;
    overflow: hidden;
}
.carousel-item {
    padding: 0 1rem;
    transition: transform 0.5s ease;
}
.carousel-control-prev, .carousel-control-next {
    width: 5%;
    background: rgba(0, 0, 0, 0.2);
    border-radius: 12px;
    transition: background 0.3s ease;
}
.carousel-control-prev:hover, .carousel-control-next:hover {
    background: rgba(0, 0, 0, 0.4);
}
.carousel-control-prev-icon, .carousel-control-next-icon {
    background-color: #5b21b6; /* Changed to purple */
    border-radius: 50%;
    padding: 12px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}

/* ===== SECTION HEADINGS ===== */
.section-heading {
    color: #5b21b6; /* Changed to purple */
    font-weight: 600;
    font-size: 1.8rem;
    margin-bottom: 1.5rem;
    position: relative;
    display: inline-block;
}
.section-heading::after {
    content: '';
    position: absolute;
    bottom: -4px;
    left: 0;
    width: 50%;
    height: 2px;
    background: linear-gradient(90deg, #5b21b6, #a855f7); /* Changed to purple */
    border-radius: 2px;
}

/* ===== PAGINATION STYLING ===== */
.pagination {
    justify-content: center;
    margin-top: 2rem;
}
.pagination .page-item {
    margin: 0 5px;
}
.pagination .page-link {
    border-radius: 25px;
    color: #5b21b6; /* Changed to purple */
    border: none;
    font-weight: 500;
    padding: 0.5rem 1rem;
    transition: all 0.3s ease;
    background-color: #ffffff;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}
.pagination .page-item.active .page-link {
    background: linear-gradient(45deg, #5b21b6, #a855f7); /* Changed to purple */
    color: #ffffff;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
}
.pagination .page-link:hover {
    background: linear-gradient(45deg, #4f46e5, #9333ea); /* Changed to purple */
    color: #ffffff;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}
.pagination .page-item.disabled .page-link {
    color: #6b7280;
    background: #f3f4f6;
    cursor: not-allowed;
    box-shadow: none;
}
.pagination .page-link:focus {
    box-shadow: 0 0 0 0.2rem rgba(91, 33, 182, 0.5); /* Changed to purple */
    outline: none;
}

/* ===== RESPONSIVE DESIGN ===== */
@media (max-width: 768px) {
    .hero h1 {
        font-size: 2rem;
    }
    .hero p {
        font-size: 1rem;
    }
    .hero .btn-primary {
        font-size: 0.9rem;
        padding: 0.6rem 1.5rem;
    }
    .section-heading {
        font-size: 1.5rem;
    }
    .product-card img {
        height: 180px;
    }
    .category-card {
        flex: 1 1 100px;
        max-width: 120px;
    }
    .category-icon {
        font-size: 1.5rem;
    }
    .category-card .card-text {
        font-size: 0.8rem;
    }
    .carousel-control-prev, .carousel-control-next {
        width: 10%;
    }
    .pagination .page-link {
        font-size: 0.9rem;
        padding: 0.4rem 0.8rem;
    }
}
</style>
@endsection

@section('content')
<div class="container mt-4">
    <!-- HERO SECTION -->
    <div class="hero">
        <div class="hero-content">
            <h1>Khám Phá Thế Giới Thời Trang</h1>
            <p>Bộ sưu tập mới nhất với phong cách dẫn đầu xu hướng!</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary">Mua Sắm Ngay</a>
        </div>
    </div>

    <!-- DANH MỤC -->
    <div class="categories-section">
        <button class="btn btn-category w-100 fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#categoriesCollapse" aria-expanded="false" aria-controls="categoriesCollapse">
            <i class="bi bi-list me-2"></i> Danh Mục
        </button>
        <div class="collapse mt-3" id="categoriesCollapse">
            <div class="categories-container">
                @foreach($categories as $category)
                <a href="{{ route('category.show', $category->id) }}" class="category-card text-decoration-none">
                    <i class="bi bi-tags category-icon"></i>
                    <p class="card-text mb-0">{{ $category->name }}</p>
                </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ===== SẢN PHẨM GỢI Ý ===== --}}
    @if($suggestedProducts->count() > 0)
    <h3 class="section-heading text-center">Sản Phẩm Gợi Ý</h3>
    <div id="suggestedProductsCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            @foreach($suggestedProducts->chunk(4) as $chunkIndex => $productsChunk)
            <div class="carousel-item {{ $chunkIndex == 0 ? 'active' : '' }}">
                <div class="row justify-content-center">
                    @foreach($productsChunk as $product)
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                        <div class="card product-card">
                            <a href="{{ route('products.show', $product->id) }}">
                                <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/300x200' }}" 
                                     alt="{{ $product->name }}" class="card-img-top">
                            </a>
                            <div class="card-body">
                                <h6 class="card-title text-truncate">
                                    <a href="{{ route('products.show', $product->id) }}" class="text-dark text-decoration-none">
                                        {{ $product->name }}
                                    </a>
                                </h6>
                                <p class="product-price">{{ number_format($product->price, 0, ',', '.') }}₫</p>
                                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="quantity" value="1">
                                    <button class="btn btn-success btn-sm w-100"><i class="bi bi-cart-plus me-1"></i> Thêm Giỏ Hàng</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
        @if($suggestedProducts->count() > 4)
        <button class="carousel-control-prev" type="button" data-bs-target="#suggestedProductsCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#suggestedProductsCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
        @endif
    </div>
    @endif

    {{-- ===== SẢN PHẨM MỚI ===== --}}
    @if($newProducts->count() > 0)
    <h3 class="section-heading text-center">Sản Phẩm Mới</h3>
    <div id="newProductsCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            @foreach($newProducts->chunk(4) as $chunkIndex => $productsChunk)
            <div class="carousel-item {{ $chunkIndex == 0 ? 'active' : '' }}">
                <div class="row justify-content-center">
                    @foreach($productsChunk as $product)
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                        <div class="card product-card">
                            <span class="product-badge">Mới</span>
                            <a href="{{ route('products.show', $product->id) }}">
                                <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/300x200' }}" 
                                     alt="{{ $product->name }}" class="card-img-top">
                            </a>
                            <div class="card-body">
                                <h6 class="card-title text-truncate">
                                    <a href="{{ route('products.show', $product->id) }}" class="text-dark text-decoration-none">
                                        {{ $product->name }}
                                    </a>
                                </h6>
                                <p class="product-price">{{ number_format($product->price, 0, ',', '.') }}₫</p>
                                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="quantity" value="1">
                                    <button class="btn btn-success btn-sm w-100"><i class="bi bi-cart-plus me-1"></i> Thêm Giỏ Hàng</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
        @if($newProducts->count() > 4)
        <button class="carousel-control-prev" type="button" data-bs-target="#newProductsCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#newProductsCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
        @endif
    </div>
    @endif

    {{-- ===== SẢN PHẨM HOT ===== --}}
    @if($hotProducts->count() > 0)
    <h3 class="section-heading text-center">Sản Phẩm Hot</h3>
    <div id="hotProductsCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            @foreach($hotProducts->chunk(4) as $chunkIndex => $productsChunk)
            <div class="carousel-item {{ $chunkIndex == 0 ? 'active' : '' }}">
                <div class="row justify-content-center">
                    @foreach($productsChunk as $product)
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                        <div class="card product-card">
                            <span class="product-badge">Hot</span>
                            <a href="{{ route('products.show', $product->id) }}">
                                <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/300x200' }}" 
                                     alt="{{ $product->name }}" class="card-img-top">
                            </a>
                            <div class="card-body">
                                <h6 class="card-title text-truncate">
                                    <a href="{{ route('products.show', $product->id) }}" class="text-dark text-decoration-none">
                                        {{ $product->name }}
                                    </a>
                                </h6>
                                <p class="product-price">{{ number_format($product->price, 0, ',', '.') }}₫</p>
                                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="quantity" value="1">
                                    <button class="btn btn-success btn-sm w-100"><i class="bi bi-cart-plus me-1"></i> Thêm Giỏ Hàng</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
        @if($hotProducts->count() > 4)
        <button class="carousel-control-prev" type="button" data-bs-target="#hotProductsCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#hotProductsCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
        @endif
    </div>
    @endif

    <!-- TẤT CẢ SẢN PHẨM -->
    <h2 class="section-heading text-center">Tất Cả Sản Phẩm</h2>
    <div class="row g-4">
        @forelse($products as $product)
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="card product-card shadow-sm h-100 d-flex flex-column">
                @php
                    $isHot = $hotProducts->contains('id', $product->id);
                    $isNew = $newProducts->contains('id', $product->id);
                @endphp
                @if($isHot)
                    <span class="product-badge">Hot</span>
                @elseif($isNew)
                    <span class="product-badge">Mới</span>
                @endif
                <a href="{{ route('products.show', $product->id) }}" class="text-decoration-none text-dark">
                    <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/300x200' }}"
                         alt="{{ $product->name }}"
                         class="card-img-top">
                </a>
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title text-truncate">
                        <a href="{{ route('products.show', $product->id) }}" class="text-dark text-decoration-none">
                            {{ $product->name }}
                        </a>
                    </h5>
                    <p class="product-price">{{ number_format($product->price, 0, ',', '.') }}₫</p>

                    @php
                        $avgRating = round($product->reviews->avg('rating'), 1);
                    @endphp
                    @if($avgRating > 0)
                        <p class="mb-1 text-warning">⭐ {{ $avgRating }}/5 ({{ $product->reviews->count() }} đánh giá)</p>
                    @endif

                    <p class="card-text text-truncate">{{ $product->description }}</p>

                    @if($product->stock > 0)
                        <p class="stock-status"><strong>Trạng thái:</strong> <span class="text-success">Còn hàng</span></p>
                        <p class="stock-status"><strong>Số lượng tồn:</strong> {{ $product->stock }}</p>
                    @else
                        <p class="stock-status"><strong>Trạng thái:</strong> <span class="text-danger">Hết hàng</span></p>
                        <p class="stock-status"><strong>Số lượng tồn:</strong> 0</p>
                    @endif

                    <div class="mt-auto d-flex gap-2 align-items-center">
                        @auth
                            @if((int)$product->stock > 0)
                                <form action="{{ route('cart.add', $product->id) }}" method="POST" class="m-0">
                                    @csrf
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="bi bi-cart-plus"></i>
                                    </button>
                                </form>
                                <a href="{{ route('order.create', $product->id) }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-bag-check"></i>
                                </a>
                            @endif
                            <form action="{{ route('wishlist.add') }}" method="POST" class="m-0 ms-auto">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <button type="submit" class="btn btn-wishlist">
                                    @php
                                        $isWishlisted = \App\Models\Wishlist::where('user_id', auth()->id())
                                            ->where('product_id', $product->id)
                                            ->exists();
                                    @endphp
                                    <i class="bi {{ $isWishlisted ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                                </button>
                            </form>
                        @endauth

                        @guest
                            <a href="{{ route('login') }}" class="btn btn-success btn-sm">
                                <i class="bi bi-cart-plus"></i>
                            </a>
                            <a href="{{ route('login') }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-bag-check"></i>
                            </a>
                            <a href="{{ route('login') }}" class="btn btn-wishlist">
                                <i class="bi bi-heart"></i>
                            </a>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
        @empty
            <p class="text-center fw-bold text-muted">Không có sản phẩm nào.</p>
        @endforelse
    </div>

    <!-- PAGINATION -->
    @if($products->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $products->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection