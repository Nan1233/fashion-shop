<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Fashion Shop')</title>

    <!-- Bootstrap CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* ===== BODY ===== */
        body {
            background-color: #f9fafb;
            font-family: 'Inter', sans-serif;
            color: #1f2937;
        }

        /* ===== NAVBAR ===== */
        .navbar {
            background: linear-gradient(90deg, #6366f1, #a855f7);
            padding: 1rem 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .navbar-brand {
            font-weight: 600;
            font-size: 1.7rem;
            color: #ffffff !important;
            letter-spacing: 0.5px;
            transition: color 0.3s ease;
        }
        .navbar-brand:hover {
            color: #e0e7ff !important;
        }
        .nav-link {
            color: #e0e7ff !important;
            font-size: 1rem;
            font-weight: 500;
            padding: 0.5rem 1.2rem;
            transition: all 0.3s ease;
        }
        .nav-link:hover {
            color: #ffffff !important;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
        }
        .nav-icon {
            font-size: 1.2rem;
            margin-right: 8px;
            vertical-align: middle;
        }
        .navbar-toggler {
            border: none;
            color: #ffffff;
        }
        .navbar-toggler:focus {
            box-shadow: none;
        }

        /* ===== CART/WISHLIST BADGE ===== */
        .cart-badge {
            position: absolute;
            top: -10px;
            right: -10px;
            font-size: 0.75rem;
            background: linear-gradient(45deg, #3b82f6, #60a5fa);
            color: #ffffff;
            border-radius: 50%;
            padding: 4px 8px;
            font-weight: 600;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
        }

        /* ===== BUTTONS ===== */
        .btn-success, .btn-primary {
            border-radius: 30px;
            font-weight: 600;
            padding: 0.6rem 1.8rem;
            transition: all 0.3s ease;
        }
        .btn-success {
            background: linear-gradient(45deg, #10b981, #34d399);
            border: none;
        }
        .btn-success:hover {
            background: linear-gradient(45deg, #059669, #10b981);
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

        /* ===== FLASH MESSAGES ===== */
        .alert-success {
            background: linear-gradient(45deg, #d1fae5, #a7f3d0);
            color: #065f46;
            border: none;
            border-radius: 10px;
            font-weight: 500;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            position: relative;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }
        .alert-success .btn-close {
            position: absolute;
            top: 0.75rem;
            right: 1rem;
            color: #065f46;
        }

        /* ===== MAIN CONTENT ===== */
        main {
            padding: 2.5rem 0;
        }

        /* ===== RESPONSIVE DESIGN ===== */
        @media (max-width: 768px) {
            .navbar-nav {
                padding: 1rem 0;
                background: linear-gradient(90deg, #6366f1, #a855f7);
                border-radius: 8px;
            }
            .nav-link {
                padding: 0.8rem 1rem;
            }
            .navbar-brand {
                font-size: 1.5rem;
            }
            .cart-badge {
                top: -8px;
                right: -8px;
            }
        }
    </style>

    @yield('styles')
</head>
<body>
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">Fashion Shop</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    @guest
                        <li class="nav-item me-3">
                            <a class="nav-link" href="{{ route('login') }}"><i class="bi bi-box-arrow-in-right nav-icon"></i> Đăng nhập</a>
                        </li>
                        <li class="nav-item me-3">
                            <a class="nav-link" href="{{ route('register') }}"><i class="bi bi-person-plus nav-icon"></i> Đăng ký</a>
                        </li>
                    @endguest

                    @auth
                        @if(Auth::user()->role === 'admin')
                            <li class="nav-item me-3">
                                <a class="nav-link" href="{{ route('dashboard') }}">
                                    <i class="bi bi-speedometer2 nav-icon"></i> Dashboard
                                </a>
                            </li>
                        @else
                            <li class="nav-item me-3 position-relative">
                                <a class="nav-link" href="{{ route('cart.index') }}">
                                    <i class="bi bi-cart nav-icon"></i> Giỏ hàng
                                    @if(isset($cartCount) && $cartCount > 0)
                                        <span class="cart-badge">{{ $cartCount }}</span>
                                    @endif
                                </a>
                            </li>

                            <li class="nav-item me-3">
                                <a class="nav-link" href="{{ route('orders.index') }}">
                                    <i class="bi bi-bag-check nav-icon"></i> Đơn hàng
                                </a>
                            </li>

                            <li class="nav-item me-3 position-relative">
                                <a class="nav-link" href="{{ route('wishlist.index') }}">
                                    <i class="bi bi-heart nav-icon"></i> Wishlist
                                    @if(isset($wishlistCount) && $wishlistCount > 0)
                                        <span class="cart-badge">{{ $wishlistCount }}</span>
                                    @endif
                                </a>
                            </li>

                            <li class="nav-item me-3">
                                <a class="nav-link" href="{{ route('profile.edit') }}">
                                    <i class="bi bi-person-circle nav-icon"></i> Tài khoản
                                </a>
                            </li>
                        @endif
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- FLASH MESSAGES -->
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    <!-- MAIN CONTENT -->
    <main class="container">
        @yield('content')
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    @yield('scripts')
</body>
</html>