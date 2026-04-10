<nav class="custom-header">
  <div class="container-fluid px-4">
    <div class="header-row">
      <!-- MOBILE TOGGLE -->
      <div class="mobile-toggle d-lg-none order-0" onclick="toggleMobileMenu()">
        <i class="fas fa-bars"></i>
      </div>

      <!-- LOGO - Left Corner -->
      <div class="logo m-0">
        <a href="{{ route('home') }}" class="text-decoration-none">
          <img src="{{ asset('images/home/Arya-1-1.png') }}" alt="AryaMeals Logo">
        </a>
      </div>

      <!-- MENU (Mobile Only) -->
      <div class="menu d-none">
        <a href="{{ route('home') }}">Home</a>
        <a href="#restaurants">Restaurants</a>
        <a href="#categoryScroll">Categories</a>
        <a href="#why">Why Us</a>
        <a href="#area">Service Area</a>
      </div>

      <!-- DELIVERY ADDRESS (Desktop) - Left of Search Bar -->
      @php
      $lastDeliveryAddress = null;
      if(Auth::check()) {
      $lastOrder = \App\Models\Order::where('user_id', Auth::id())->latest()->first();
      $lastDeliveryAddress = $lastOrder ? $lastOrder->customer_address : session('delivery_address', 'Select Address');
      } else {
      $lastDeliveryAddress = session('delivery_address', 'Select Address');
      }
      // Extract village/area name (first part before comma)
      $shortAddress = explode(',', $lastDeliveryAddress)[0] ?? $lastDeliveryAddress;
      $shortAddress = Str::limit($shortAddress, 25);
      @endphp
      <div class="d-none d-lg-flex align-items-center ms-5 me-5" style="min-width: 200px; max-width: 250px;">
        <div class="d-flex align-items-center">
          <i class="fas fa-map-marker-alt me-2" style="color: #ff6b35; font-size: 18px;"></i>
          <div class="text-truncate">
            <small class="text-white d-block" style="font-size: 11px; line-height: 1;">Deliver to</small>
            <span class="fw-bold text-white" style="font-size: 13px; line-height: 1.2;">
              {{ $shortAddress }}
            </span>
          </div>
        </div>
      </div>

      <!-- SEARCH BAR (Desktop - Centered) -->
      <div class="d-none d-lg-flex flex-grow-1 justify-content-center">
        <form action="{{ route('search') }}" method="GET" class="position-relative" style="width: 100%; max-width: 600px;">
          <input type="text" name="q" class="form-control" placeholder="Search food, restaurants..."
            style="border-radius: 10px; padding: 10px 45px 10px 20px;margin-left:-33px">
          <button type="submit" class="btn position-absolute" style="right: 30px; top: 50%; transform: translateY(-50%); color: #ff6b35;">
            <i class="fas fa-search"></i>
          </button>
        </form>
      </div>

      <!-- RIGHT SIDE -->
      <div class="header-actions d-none d-lg-flex">
        <!-- CART -->
        <a href="#" onclick="openCartSidebar(event)" class="cart d-lg-flex position-relative text-decoration-none">
          <i class="fas fa-shopping-cart"></i>
          <span class="cart-count-badge" id="cartCount">0</span>
        </a>

        <!-- AUTH BUTTONS -->
        @if(Auth::check())
        <div class="dropdown">
          <button class="btn btn-link dropdown-toggle user-dropdown-btn d-flex align-items-center gap-2" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            @if(Auth::user()->avatar)
            <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}" class="rounded-circle" style="width: 32px; height: 32px; object-fit: cover;">
            @else
            <i class="fas fa-user-circle fa-lg text-white"></i>
            @endif
            <span class="text-white">{{ Auth::user()->name }}</span>
          </button>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
            <li>
              <form method="POST" action="{{ route('auth.logout') }}" class="m-0" onsubmit="return true;">
                @csrf
                <button type="submit" class="dropdown-item text-danger">
                  <i class="fas fa-sign-out-alt me-2"></i> Logout
                </button>
              </form>
            </li>
          </ul>
        </div>
        @else
        <a href="{{ route('login') }}" class="join-btn">Login / Sign Up</a>
        @endif
      </div>

      <!-- CART (Mobile) -->
      <div class="cart d-lg-none d-flex align-items-center gap-3">
        <a href="{{ route('cart.index') }}" class="position-relative text-decoration-none">
          <i class="fas fa-shopping-cart text-white"></i>
          <span class="cart-count-badge" id="cartCountMobile">0</span>
        </a>
        @if(Auth::check())
        <div class="mobile-user-profile d-flex align-items-center gap-2">
          @if(Auth::user()->avatar)
          <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}" class="rounded-circle" style="width: 28px; height: 28px; object-fit: cover;">
          @else
          <i class="fas fa-user-circle text-white" style="font-size: 20px;"></i>
          @endif
        </div>
        @endif
      </div>



    </div>
  </div>
</nav>

<!-- MOBILE MENU -->
<div class="mobile-menu-overlay" id="mobileMenuOverlay" onclick="closeMobileMenu()"></div>
<div class="mobile-menu-sidebar" id="mobileMenuSidebar">
  <div class="mobile-menu-header">
    <h4>Menu</h4>
    <button class="mobile-menu-close" onclick="closeMobileMenu()">
      <i class="fas fa-times"></i>
    </button>
  </div>
  <div class="mobile-menu-content">
    <a href="{{ route('home') }}" class="mobile-menu-link">Home</a>
    <a href="#restaurants" class="mobile-menu-link">Restaurants</a>
    <a href="#categoryScroll" class="mobile-menu-link">Categories</a>
    <a href="#why" class="mobile-menu-link">Why Us</a>
    <a href="#area" class="mobile-menu-link">Service Area</a>

    <div class="mobile-menu-divider"></div>

    <!-- Mobile Auth Buttons -->
    <div class="mobile-menu-auth">
      @if(Auth::check())
      <div class="mobile-user-info mb-3">
        <div class="d-flex align-items-center mb-2">
          @if(Auth::user()->avatar)
          <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}" class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover;">
          @else
          <i class="fas fa-user-circle fa-2x me-2"></i>
          @endif
          <div>
            <strong>{{ Auth::user()->name }}</strong>
            <br>
            <small class="text-muted">{{ Auth::user()->email }}</small>
          </div>
        </div>
      </div>
      <a href="{{ route('cart.index') }}" class="mobile-menu-link">
        <i class="fas fa-shopping-cart me-2"></i> My Cart
      </a>

      <form method="POST" action="{{ route('auth.logout') }}" class="mobile-logout-form" onsubmit="return true;">
        @csrf
        <button type="submit" class="mobile-menu-btn mobile-logout-btn">
          <i class="fas fa-sign-out-alt"></i>
          Logout
        </button>
      </form>
      @else
      <a href="{{ route('login') }}" class="mobile-menu-link">
        <i class="fas fa-sign-in-alt me-2"></i> Login / Sign Up
      </a>
      @endif
    </div>
  </div>
</div>

<script>
  function toggleMobileMenu() {
    const overlay = document.getElementById('mobileMenuOverlay');
    const sidebar = document.getElementById('mobileMenuSidebar');

    overlay.style.display = 'block';
    sidebar.style.transform = 'translateX(0)';
    document.body.style.overflow = 'hidden';
  }

  function closeMobileMenu() {
    const overlay = document.getElementById('mobileMenuOverlay');
    const sidebar = document.getElementById('mobileMenuSidebar');

    overlay.style.display = 'none';
    sidebar.style.transform = 'translateX(-100%)';
    document.body.style.overflow = 'auto';
  }

  // Handle logout forms - ensure normal form submission
  document.addEventListener('DOMContentLoaded', function() {
    // Get all logout forms
    const logoutForms = document.querySelectorAll('form[action*="logout"]');

    logoutForms.forEach(form => {
      form.addEventListener('submit', function(e) {
        // Let the form submit normally - don't prevent default
        // This ensures proper redirect to login page
      });
    });
  });

  // Close menu on escape key
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      closeMobileMenu();
    }
  });

  // Handle smooth scrolling for anchor links
  document.addEventListener('DOMContentLoaded', function() {
    // Handle navigation links
    const navLinks = document.querySelectorAll('.menu a[href^="#"], .mobile-menu-link[href^="#"]');

    navLinks.forEach(link => {
      link.addEventListener('click', function(e) {
        e.preventDefault();

        // Get the target section
        const targetId = this.getAttribute('href').substring(1);
        const targetSection = document.getElementById(targetId);

        if (targetSection) {
          // Close mobile menu if open
          closeMobileMenu();

          // Smooth scroll to target
          targetSection.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
          });
        } else {
          // If section not found on current page, redirect to home with hash
          window.location.href = '{{ route("home") }}#' + targetId;
        }
      });
    });

    // Handle home link separately
    const homeLinks = document.querySelectorAll('.menu a[href="{{ route("home") }}"], .mobile-menu-link[href="{{ route("home") }}"]');
    homeLinks.forEach(link => {
      link.addEventListener('click', function(e) {
        // If not on home page, navigate to home
        if (window.location.pathname !== '/') {
          return; // Let default navigation happen
        }
        // If on home page, scroll to top
        e.preventDefault();
        window.scrollTo({
          top: 0,
          behavior: 'smooth'
        });
      });
    });
  });
</script>