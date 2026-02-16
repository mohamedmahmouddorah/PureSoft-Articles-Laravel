<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Artical System')</title>
    <!-- Bootstrap 5 CSS (LTR) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    
    <style>
        .reply-form { display: none; margin-top: 15px; animation: fadeIn 0.3s; }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container">
        <a class="navbar-brand" href="{{ route('articles.index') }}"><i class="bi bi-house-door-fill"></i></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <div class="navbar-nav ms-auto align-items-center">
                @auth
                    <a href="#" class="nav-link position-relative me-3" data-bs-toggle="modal" data-bs-target="#cartModal">
                        <i class="bi bi-cart4" style="font-size: 1.2rem;"></i>
                        <span id="cart-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="display: none; font-size: 0.6rem;">0</span>
                    </a>
                    <span class="nav-link text-info">Welcome, <a href="{{ route('users.show', Auth::user()) }}" class="text-info text-decoration-none fw-bold">{{ Auth::user()->username }}</a></span>
                    <a href="{{ route('articles.create') }}" class="nav-link">Create Article</a>
                    <a href="{{ route('users.index') }}" class="nav-link">Users</a>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm ms-3 glow-on-hover">Logout</button>
                    </form>
                @else
                    <a href="{{ route('users.index') }}" class="nav-link">Users</a>
                    <a href="{{ route('login') }}" class="btn btn-info btn-sm ms-3 glow-on-hover">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm ms-2 glow-on-hover">Register</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<div style="height: 80px;"></div> <!-- Spacer for fixed navbar -->

<div class="container flex-grow-1">
    @if(session('success'))
        <div class="alert alert-success text-center mt-3 alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger text-center mt-3 alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @yield('content')
</div>

<!-- Cart Modal -->
<div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content glass-card border-secondary text-white" style="background: rgba(20, 20, 30, 0.95); backdrop-filter: blur(15px);">
            <div class="modal-header border-secondary">
                <h5 class="modal-title" id="cartModalLabel"><i class="bi bi-cart4 text-info me-2"></i> Shopping Cart</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="cart-items-container">
                    <!-- Cart items will be loaded here via AJAX -->
                    <div class="text-center py-4">
                        <div class="spinner-border text-info" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
                
                <div id="checkout-form-container" style="display: none;" class="mt-4 pt-4 border-top border-secondary">
                    <h5 class="mb-3">Checkout Information</h5>
                    <form id="checkout-form">
                        @csrf
                        <div class="mb-3">
                            <input type="text" class="form-control bg-dark text-white border-secondary" placeholder="Shipping Address" required>
                        </div>
                        <div class="mb-3">
                            <select class="form-select bg-dark text-white border-secondary">
                                <option value="cod">Cash on Delivery</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Confirm Order</button>
                    </form>
                </div>
            </div>
            <div class="modal-footer border-secondary">
                <div class="w-100 d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Total: <span id="cart-total" class="text-success">0.00 $</span></h4>
                    <div>
                        <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Continue Shopping</button>
                        <button type="button" id="btn-checkout" class="btn btn-success px-4" style="display: none;">Checkout</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

        // Function to update cart badge and items
        function refreshCart() {
            fetch('{{ route("cart.data") }}', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                }
            })
                .then(response => {
                    if (response.status === 401) return { cart: [], total: 0 }; // Handle unauthenticated
                    return response.json();
                })
                .then(data => {
                    const container = document.getElementById('cart-items-container');
                    const totalEl = document.getElementById('cart-total');
                    const badge = document.getElementById('cart-badge');
                    const checkoutBtn = document.getElementById('btn-checkout');
                    
                    let itemsHtml = '';
                    let count = 0;

                    if (!data.cart || Object.keys(data.cart).length === 0) {
                        itemsHtml = '<div class="text-center py-5 text-muted"><i class="bi bi-cart-x" style="font-size: 3rem;"></i><p class="mt-2">Your cart is empty.</p></div>';
                        badge.style.display = 'none';
                        checkoutBtn.style.display = 'none';
                    } else {
                        itemsHtml = '<div class="table-responsive"><table class="table table-dark table-hover align-middle"><thead><tr><th>Product</th><th>Price</th><th>Qty</th><th>Subtotal</th><th></th></tr></thead><tbody>';
                        
                        for (const [id, item] of Object.entries(data.cart)) {
                            count++;
                            itemsHtml += `
                                <tr>
                                    <td>${item.name}</td>
                                    <td>${parseFloat(item.price).toFixed(2)} $</td>
                                    <td>${item.quantity}</td>
                                    <td>${(item.price * item.quantity).toFixed(2)} $</td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-danger btn-remove" data-id="${id}"><i class="bi bi-trash"></i></button>
                                    </td>
                                </tr>
                            `;
                        }
                        itemsHtml += '</tbody></table></div>';
                        badge.textContent = count;
                        badge.style.display = 'block';
                        checkoutBtn.style.display = 'block';
                    }

                    container.innerHTML = itemsHtml;
                    totalEl.textContent = data.total.toFixed(2) + ' $';

                    // Re-bind remove buttons
                    document.querySelectorAll('.btn-remove').forEach(btn => {
                        btn.addEventListener('click', function() {
                            removeCartItem(this.dataset.id);
                        });
                    });
                });
        }

        // Add to cart logic using event delegation
        document.addEventListener('click', function(e) {
            if (e.target.closest('.add-to-cart')) {
                const btn = e.target.closest('.add-to-cart');
                const id = btn.dataset.id;
                const originalText = btn.innerHTML;
                
                if (btn.disabled) return; // Prevent double clicks
                
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Adding...';

                fetch(`/cart/add/${id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                    if (data.success) {
                        refreshCart();
                        const toast = document.createElement('div');
                        toast.className = 'position-fixed bottom-0 end-0 p-3';
                        toast.style.zIndex = '1060';
                        toast.innerHTML = `<div class="alert alert-success shadow"><i class="bi bi-check-circle me-2"></i> ${data.message}</div>`;
                        document.body.appendChild(toast);
                        setTimeout(() => toast.remove(), 3000);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                });
            }
        });

        function removeCartItem(id) {
            fetch(`/cart/remove/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    refreshCart();
                }
            });
        }

        // Checkout UI toggle
        document.getElementById('btn-checkout')?.addEventListener('click', function() {
            const checkoutContainer = document.getElementById('checkout-form-container');
            const cartContainer = document.getElementById('cart-items-container');
            
            if (checkoutContainer.style.display === 'none') {
                checkoutContainer.style.display = 'block';
                this.textContent = 'Back to Cart';
                this.classList.replace('btn-success', 'btn-secondary');
                cartContainer.style.opacity = '0.5';
            } else {
                checkoutContainer.style.display = 'none';
                this.textContent = 'Checkout';
                this.classList.replace('btn-secondary', 'btn-success');
                cartContainer.style.opacity = '1';
            }
        });

        // Checkout submission
        document.getElementById('checkout-form')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = this.querySelector('button[type="submit"]');
            const originalText = btn.textContent;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';

            fetch('{{ route("checkout.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                btn.disabled = false;
                btn.textContent = originalText;
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert(data.message);
                }
            });
        });

        // Initial cart load
        refreshCart();
    });
</script>
</body>
</html>
