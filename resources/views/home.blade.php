@extends('layouts.app')

@section('title', 'AryaMeals - Order Food Online from Best Restaurants')

@section('content')
<!-- User Welcome Section (only when logged in) -->
<!-- HERO SECTION START -->
<section class="hero-section">

    <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">

        <!-- Indicators -->
        <div class="carousel-indicators custom-indicators">
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
        </div>

        <!-- Slides -->
        <div class="carousel-inner">

            <!-- SLIDE 1 -->
            <div class="carousel-item active">
                <div class="hero-slide d-flex align-items-center">

                    <div class="container">
                        <div class="row align-items-center">

                            <div class="col-lg-7 text-white">
                                <p class="hero-tag">Fresh food, fast delivery</p>

                                <h1 class="hero-title">
                                    Arya Meals – Ghar <br> jaisa khana <br> delivery fast
                                </h1>

                                <p class="hero-sub">
                                    Chamba me fast pickup & delivery.
                                </p>

                                <a href="{{ url('/restaurants') }}" class="arya-btn">
                                    Join AryaMeals Now
                                </a>
                            </div>

                            <div class="col-lg-5 text-center">
                                <div class="hero-card">
                                    <img src="{{ asset('images/home/slide-11-1-1.png') }}" alt="Food App">
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>

            <!-- SLIDE 2 -->
            <div class="carousel-item">
                <div class="hero-slide d-flex align-items-center">

                    <div class="container">
                        <div class="row align-items-center">

                            <div class="col-lg-7 text-white">
                                <p class="hero-tag">One Place. Many Choices</p>

                                <h1 class="hero-title">
                                    Arya Meals – Veg <br> Non-Veg Fast Food <br> Grocery
                                </h1>

                                <p class="hero-sub">
                                    Lunch ho ya dinner, Arya Meals delivers fresh & fast.
                                </p>

                                <a href="{{ url('/restaurants') }}" class="arya-btn">
                                    Join AryaMeals Now
                                </a>
                            </div>

                            <div class="col-lg-5 text-center">
                                <div class="hero-card">
                                    <img src="{{ asset('images/home/slide-13-2-1.png') }}" alt="Grocery App">
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>

            <!-- SLIDE 3 -->
            <div class="carousel-item">
                <div class="hero-slide d-flex align-items-center">

                    <div class="container">
                        <div class="row align-items-center">

                            <div class="col-lg-7 text-white">
                                <p class="hero-tag">Best Quality Food</p>

                                <h1 class="hero-title">
                                    Arya Meals – Premium <br> Quality <br> Affordable Price
                                </h1>

                                <p class="hero-sub">
                                    Order from the best restaurants in your city.
                                </p>

                                <a href="{{ url('/restaurants') }}" class="arya-btn">
                                    Join AryaMeals Now
                                </a>
                            </div>

                            <div class="col-lg-5 text-center">
                                <div class="hero-card">
                                    <img src="{{ asset('images/home/slide-12-1-1.png') }}" alt="Premium Food">
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>

        </div>

        <!-- Controls -->
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon custom-arrow"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon custom-arrow"></span>
        </button>

    </div>

</section>
<!-- HERO SECTION END -->

<!-- CATEGORY SECTION START -->
    <section class="category-section py-5 mt-5">
        <div class="container-fluid px-4">

            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="category-title">Order our best food options</h2>

            <div class="category-nav">
                <button class="nav-btn prev-btn">&#8592;</button>
                <button class="nav-btn next-btn">&#8594;</button>
            </div>
            </div>

            <div class="category-scroll" id="categoryScroll">

                <!-- COLUMN (each column has 2 items) -->
                <div class="category-column">
                    <div class="category-item">
                    <a href="{{ route('category.show', 'fried-chicken') }}" class="text-decoration-none">
                    <img src="{{ asset('images/home/fried-chicken.jpg') }}">
                    <p class="text-dark">Fried Chicken</p>
                    </a>
                    </div>
                    <div class="category-item">
                    <a href="{{ route('category.show', 'salads') }}" class="text-decoration-none">
                    <img src="{{ asset('images/home/salads.jpg') }}">
                    <p class="text-dark">Salads</p>
                    </a>
                    </div>
                </div>

                <div class="category-column">
                    <div class="category-item">
                    <a href="{{ route('category.show', 'wraps') }}" class="text-decoration-none">
                    <img src="{{ asset('images/home/wraps.jpg') }}">
                    <p class="text-dark">Wraps</p>
                    </a>
                    </div>
                    <div class="category-item">
                    <a href="{{ route('category.show', 'add-ons-&-extras') }}" class="text-decoration-none">
                    <img src="{{ asset('images/home/chips.jpg') }}">
                    <p class="text-dark">Groceries</p>
                    </a>
                    </div>
                </div>

                <div class="category-column">
                    <div class="category-item">
                    <a href="{{ route('category.show', 'pizza') }}" class="text-decoration-none">
                    <img src="{{ asset('images/home/top-view-chicken-pizza-with-green-bell-pepper-mushroom-cheese-tomato-sauce_141793-2429.webp') }}">
                    <p class="text-dark">Pizza</p>
                    </a>
                    </div>
                    <div class="category-item">
                    <a href="{{ route('category.show', 'chicken-dishes') }}" class="text-decoration-none">
                    <img src="{{ asset('images/home/szechuan-chicken-which-is-popular-indo-chinese-non-vegetarian-recipe-served-plate-with-chilli-sauce-selective-focus_466689-32647.jpg') }}">
                    <p class="text-dark">Non Veg</p>
                    </a>
                    </div>
                </div>

                <div class="category-column">
                    <div class="category-item">
                    <a href="{{ route('category.show', 'burgers') }}" class="text-decoration-none">
                    <img src="{{ asset('images/home/side-view-chicken-burger-with-sliced-tomato-lettuce-board_141793-4817.webp') }}">
                    <p class="text-dark">Burger</p>
                    </a>
                    </div>
                    <div class="category-item">
                    <a href="{{ route('category.show', 'beverages') }}" class="text-decoration-none">
                    <img src="{{ asset('images/home/mango-juice-wooden-floor-table_1150-9676.png') }}">
                    <p class="text-dark">Drinks</p>
                    </a>
                    </div>
                </div>

                <div class="category-column">
                    <div class="category-item">
                    <a href="{{ route('category.show', 'biryani') }}" class="text-decoration-none">
                    <img src="{{ asset('images/home/hq720.jpg') }}">
                    <p class="text-dark">Biryani</p>
                    </a>
                    </div>
                    <div class="category-item">
                    <a href="{{ route('category.show', 'snacks') }}" class="text-decoration-none">
                    <img src="{{ asset('images/home/egg-roll-fried-spring-rolls-white-plate-thai-food_1150-21488.webp') }}">
                    <p class="text-dark">Spring Roll</p>
                    </a>
                    </div>
                </div>

                <div class="category-column">
                    <div class="category-item">
                    <a href="{{ route('category.show', 'tandoori-&-starters') }}" class="text-decoration-none">
                    <img src="{{ asset('images/home/sour-curry-with-snakehead-fish-spicy-garden-hot-pot-thai-food_1150-26404.webp') }}">
                    <p class="text-dark">Fish</p>
                    </a>
                    </div>
                    <div class="category-item">
                    <a href="{{ route('category.show', 'himachali-specialties') }}" class="text-decoration-none">
                    <img src="{{ asset('images/home/dham.jpg') }}">
                    <p class="text-dark">Dhaam</p>
                    </a>
                    </div>
                </div>

                <div class="category-column">
                    <div class="category-item">
                    <a href="{{ route('category.show', 'breakfast-&-eggs') }}" class="text-decoration-none">
                    <img src="{{ asset('images/home/thumb__700_0_0_0_auto.jpg') }}">
                    <p class="text-dark">Omelette</p>
                    </a>
                    </div>
                    <div class="category-item">
                    <a href="{{ route('category.show', 'desserts') }}" class="text-decoration-none">
                    <img src="{{ asset('images/home/sweet-corn-with-sago-sweet-coconut-milk_51524-26719.jpg') }}">
                    <p class="text-dark">Malai Champ</p>
                    </a>
                    </div>
                </div>

                <div class="category-column">
                    <div class="category-item">
                    <a href="{{ route('category.show', 'breakfast-&-eggs') }}" class="text-decoration-none">
                    <img src="{{ asset('images/home/imagesdvsdss.jpg') }}">
                    <p class="text-dark">Egg Bhurji</p>
                    </a>
                    </div>
                    <div class="category-item">
                    <a href="{{ route('category.show', 'desserts') }}" class="text-decoration-none">
                    <img src="{{ asset('images/home/delicious-cherry-chocolate-milkshake-black-forest-blurred-background_1154968-97117.jpg') }}">
                    <p class="text-dark">Chocolate</p>
                    </a>
                    </div>
                </div>

                <div class="category-column">
                    <div class="category-item">
                    <a href="{{ route('category.show', 'sandwiches') }}" class="text-decoration-none">
                    <img src="{{ asset('images/home/front-view-tasty-ham-sandwiches-with-french-fries-dark-surface_179666-34644.webp') }}">
                    <p class="text-dark">Sandwich</p>
                    </a>
                    </div>
                    <div class="category-item">
                    <a href="{{ route('category.show', 'momos') }}" class="text-decoration-none">
                    <img src="{{ asset('images/home/tibetian-dumplings-momo-with-chicken-meat-vegetables_1472-119353_converted.png') }}">
                    <p class="text-dark">Momos</p>
                    </a>
                    </div>
                </div>

                <div class="category-column">
                    <div class="category-item">
                    <a href="{{ route('category.show', 'paneer-dishes') }}" class="text-decoration-none">
                    <img src="{{ asset('images/home/chole-paneer-curry-made-using-boiled-chickpea-with-cottage-cheese-with-spices-popular-north-indian-recipe-served-bowl-serving-pan-selective-focus_466689-30545.png') }}">
                    <p class="text-dark">Paneer</p>
                    </a>
                    </div>
                    <div class="category-item">
                    <a href="{{ route('category.show', 'indian-breads') }}" class="text-decoration-none">
                    <img src="{{ asset('images/home/phulka-chapati-roti-non-stick-tawa-indian-subcontinent-food-228034597.webp') }}">
                    <p class="text-dark">Roti</p>
                    </a>
                    </div>
                </div>

                <div class="category-column">
                    <div class="category-item">
                    <a href="{{ route('category.show', 'rice-&-fried-rice') }}" class="text-decoration-none">
                    <img src="{{ asset('images/home/white-rice-with-vegetables-black-bowl-wooden-table_123827-31645.webp') }}">
                    <p class="text-dark">Rice</p>
                    </a>
                    </div>
                    <div class="category-item">
                    <a href="{{ route('category.show', 'soups') }}" class="text-decoration-none">
                    <img src="{{ asset('images/home/side-view-delicious-noodle-soup-with-chicken-brown-bowl-dark-background_140725-140956.webp') }}">
                    <p class="text-dark">Soup</p>
                    </a>
                    </div>
                </div>

                <div class="category-column">
                    <div class="category-item">
                    <a href="{{ route('category.show', 'pasta') }}" class="text-decoration-none">
                    <img src="{{ asset('images/home/top-view-cooked-pasta-with-broccoli-gray-background-color-green-food-meal-pepper-dough-photo-italy_140725-160804.webp') }}">
                    <p class="text-dark">Pasta</p>
                    </a>
                    </div>
                    <div class="category-item">
                    <a href="{{ route('category.show', 'raita-&-salad') }}" class="text-decoration-none">
                    <img src="{{ asset('images/home/imagSCscvaes.jpg') }}">
                    <p class="text-dark">Raita</p>
                    </a>
                    </div>
                </div>

                <div class="category-column">
                    <div class="category-item">
                    <a href="{{ route('category.show', 'desserts') }}" class="text-decoration-none">
                    <img src="{{ asset('images/home/360_F_954716057_GrtgyL0ObUuj0dkqVlvGBlzYNOv4pINi.jpg') }}">
                    <p class="text-dark">Kheer</p>
                    </a>
                    </div>
                    <!-- <div class="category-item">
                    <img src="{{ asset('images/home/chocolate.jpg') }}">
                    <p>Chocolate</p>
                    </div> -->
                </div>
            </div>
        </div>
    </section>
    <!-- CATEGORY SECTION END -->

    <!-- Restaurants Section -->
    <section class="content-section" id="restaurants">
        <div class="container-fluid p-3">
            <div class="section-header d-flex flex-column align-items-center">
                <h5 class="text-uppercase fw-semibold sub-hcolor mb-2">
                    Choose Your Favourite Restaurant & Food Category
                </h5>
                <h2 class="fw-bold">
                    Order by Category – Arya Meals Chamba
                </h2>

                <p class="px-md-5 px-0">
                    Discover top local restaurants and a variety of food categories — from homestyle meals and vegetarian delights to fast food and daily tiffin services.
                </p>
            </div>
            
            <div class="row" id="restaurantsGrid">
                <!-- Skeleton loaders -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="restaurant-card">
                        <div class="skeleton" style="height: 12rem;"></div>
                        <div class="restaurant-card-content">
                            <div class="skeleton" style="height: 1.125rem; width: 70%; margin-bottom: 0.5rem;"></div>
                            <div class="skeleton" style="height: 0.875rem; width: 50%; margin-bottom: 0.5rem;"></div>
                            <div class="skeleton" style="height: 0.875rem; width: 90%; margin-bottom: 0.75rem;"></div>
                            <div class="skeleton" style="height: 0.875rem; width: 60%;"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="restaurant-card">
                        <div class="skeleton" style="height: 12rem;"></div>
                        <div class="restaurant-card-content">
                            <div class="skeleton" style="height: 1.125rem; width: 70%; margin-bottom: 0.5rem;"></div>
                            <div class="skeleton" style="height: 0.875rem; width: 50%; margin-bottom: 0.5rem;"></div>
                            <div class="skeleton" style="height: 0.875rem; width: 90%; margin-bottom: 0.75rem;"></div>
                            <div class="skeleton" style="height: 0.875rem; width: 60%;"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="restaurant-card">
                        <div class="skeleton" style="height: 12rem;"></div>
                        <div class="restaurant-card-content">
                            <div class="skeleton" style="height: 1.125rem; width: 70%; margin-bottom: 0.5rem;"></div>
                            <div class="skeleton" style="height: 0.875rem; width: 50%; margin-bottom: 0.5rem;"></div>
                            <div class="skeleton" style="height: 0.875rem; width: 90%; margin-bottom: 0.75rem;"></div>
                            <div class="skeleton" style="height: 0.875rem; width: 60%;"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="restaurant-card">
                        <div class="skeleton" style="height: 12rem;"></div>
                        <div class="restaurant-card-content">
                            <div class="skeleton" style="height: 1.125rem; width: 70%; margin-bottom: 0.5rem;"></div>
                            <div class="skeleton" style="height: 0.875rem; width: 50%; margin-bottom: 0.5rem;"></div>
                            <div class="skeleton" style="height: 0.875rem; width: 90%; margin-bottom: 0.75rem;"></div>
                            <div class="skeleton" style="height: 0.875rem; width: 60%;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="restaurant-card">
                        <div class="skeleton" style="height: 12rem;"></div>
                        <div class="restaurant-card-content">
                            <div class="skeleton" style="height: 1.125rem; width: 70%; margin-bottom: 0.5rem;"></div>
                            <div class="skeleton" style="height: 0.875rem; width: 50%; margin-bottom: 0.5rem;"></div>
                            <div class="skeleton" style="height: 0.875rem; width: 90%; margin-bottom: 0.75rem;"></div>
                            <div class="skeleton" style="height: 0.875rem; width: 60%;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="restaurant-card">
                        <div class="skeleton" style="height: 12rem;"></div>
                        <div class="restaurant-card-content">
                            <div class="skeleton" style="height: 1.125rem; width: 70%; margin-bottom: 0.5rem;"></div>
                            <div class="skeleton" style="height: 0.875rem; width: 50%; margin-bottom: 0.5rem;"></div>
                            <div class="skeleton" style="height: 0.875rem; width: 90%; margin-bottom: 0.75rem;"></div>
                            <div class="skeleton" style="height: 0.875rem; width: 60%;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="restaurant-card">
                        <div class="skeleton" style="height: 12rem;"></div>
                        <div class="restaurant-card-content">
                            <div class="skeleton" style="height: 1.125rem; width: 70%; margin-bottom: 0.5rem;"></div>
                            <div class="skeleton" style="height: 0.875rem; width: 50%; margin-bottom: 0.5rem;"></div>
                            <div class="skeleton" style="height: 0.875rem; width: 90%; margin-bottom: 0.75rem;"></div>
                            <div class="skeleton" style="height: 0.875rem; width: 60%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- HOW WORKS START -->
    <section class="how-it-works">
        <div class="container">

            <!-- Heading -->
            <h2 class="how-title text-center mb-3">
                How Arya Meals Works?
            </h2>

            <!-- Steps Box -->
            <div class="steps-box">

                <div class="row text-center">

                    <div class="col-lg-3 col-6 step-item">
                        <h1>01</h1>
                        <p>Message Us on WhatsApp</p>
                    </div>

                    <div class="col-lg-3 col-6 step-item">
                        <h1>02</h1>
                        <p>Choose Your Food</p>
                    </div>

                    <div class="col-lg-3 col-6 step-item">
                        <h1>03</h1>
                        <p>Fast Delivery in Chamba</p>
                    </div>

                    <div class="col-lg-3 col-6 step-item">
                        <h1>04</h1>
                        <p>Cloud Kitchen</p>
                    </div>

                </div>

            </div>

        </div>
    </section>
    <!-- HOW WORKS END -->

    <!-- WHAT WE OFFER START -->
    <section class="offer-section py-5">
        <div class="container-fluid text-center">
            <!-- Heading -->
            <h2 class="offer-title">What We Offer at Arya Meals</h2>
            <p class="offer-sub">
                Hamara aim hai <strong>Chamba me best food delivery service</strong> provide karna.
            </p>

            <!-- Cards -->
            <div class="row mt-5">

                <!-- Card 1 -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="offer-card">
                        <div class="icon-box">
                            <i class="fa-solid fa-house"></i>
                        </div>
                        <h5>Home-style meals</h5>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="offer-card">
                        <div class="icon-box">
                            <i class="fa-solid fa-utensils"></i>
                        </div>
                        <h5>Restaurant pickup</h5>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="offer-card">
                        <div class="icon-box">
                            <i class="fa-solid fa-users"></i>
                        </div>
                        <h5>Party / bulk orders</h5>
                    </div>
                </div>

                <!-- Card 4 -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="offer-card">
                        <div class="icon-box">
                            <i class="fa-solid fa-box"></i>
                        </div>
                        <h5>Daily tiffin (optional)</h5>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- WHAT WE OFFER END -->

    <!-- PARTNER SECTION START -->
    <section class="partner-section" style="background-image:url('{{ asset('images/home/5-1-1.jpg') }}'); background-position:center;background-repeat: no-repeat;background-size:cover;">
        <div class="container">
            <div class="row align-items-center">

            <!-- LEFT IMAGE CARD -->
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="partner-card">
                <img src="{{ asset('images/home/f59bec53-15a4-4bba-b7ed-f34823f4841a-683x1024.jpg') }}" class="img-fluid rounded-4" alt="Partner">
                </div>
            </div>

            <!-- RIGHT CONTENT -->
            <div class="col-lg-6 text-white">
                <h5 class="text-uppercase sub-hcolor mb-2" style="font-size: 17px">
                Fresh, Ghar Jaisa Food Delivered Across Chamba
                </h5>
                <h2 class="partner-title">Arya Meals – Your Trusted Food Pickup & Delivery Partner</h2>
                <p class="partner-desc">
                At Arya Meals, we bring you hygienic, freshly prepared ghar jaisa khana
                from local kitchens, home chefs, and restaurants.
                </p>

                <ul class="partner-list">
                <li>Home-Style Veg & Non-Veg Meals</li>
                <li>Freshly Prepared Food</li>
                <li>Local Kitchens & Restaurants</li>
                <li>Hygienic Packaging</li>
                <li>Fast Pickup & Delivery</li>
                <li>Affordable & Transparent Pricing</li>
                </ul>

                <div class="d-flex gap-3 flex-wrap mt-4">
                <a href="https://aryameals.com/partner-register" class="arya-btn">
                    Join AryaMeals Now
                </a>

                <a href="#category" class="arya-btn arya-btn-outline">
                    ↑ Explore Menu
                </a>
                </div>

            </div>
            </div>
        </div>  
    </section>
    <!-- PARTNER SECTION END -->


    <!-- testimonial slider -->
<section class="container-fluid px-4 py-5 testimonial-sec" aria-label="Customer testimonials about food delivery service">

  <!-- Heading -->
  <div class="text-center mb-4">
    <h5 class="text-uppercase fw-semibold sub-hcolor mb-2">Testimonials</h5>
    <h2 class="fw-bold">Our Happy Clients</h2>
  </div>

  <!-- Carousel -->
  <div id="testimonialSlider" class="carousel slide" data-bs-ride="carousel" data-bs-touch="true">

    <!-- Indicators (mobile only) -->
    <div class="carousel-indicators d-md-none">
      <button type="button" data-bs-target="#testimonialSlider" data-bs-slide-to="0" class="active"></button>
      <button type="button" data-bs-target="#testimonialSlider" data-bs-slide-to="1"></button>
    </div>

    <div class="carousel-inner">

      <!-- ================= SLIDE 1 ================= -->
      <div class="carousel-item active">
        <div class="container">
          <div class="row g-4 justify-content-center">

            <!-- Card 1 -->
            <div class="col-lg-4 col-md-6">
              <article class="p-4 rounded-4 shadow-sm h-100 bg-light">
                <p class="mb-4">
                  “Lunch ke liye daily Arya Meals se order karti hoon. Hygienic cooking aur
                  reasonable pricing sabse achha part hai.”
                </p>
                <div class="d-flex align-items-center gap-3">
                  <img src="{{ asset('images/home/businesswoman-using-laptop-coffee-shop_23-2148002137.webp') }}" loading="lazy" class="rounded-circle" width="60" height="60">
                  <div>
                    <h6 class="mb-0 fw-semibold">Neha Verma</h6>
                    <small class="text-muted">Working Professional</small>
                  </div>
                </div>
              </article>
            </div>

            <!-- Card 2 -->
            <div class="col-lg-4 col-md-6 d-none d-md-block">
              <article class="p-4 rounded-4 shadow-sm h-100 bg-light">
                <p class="mb-4">
                  “Non-veg items ka taste kamaal ka hai. Chicken fresh hota hai aur packaging bhi proper hoti hai. Delivery hamesha time par milti hai.”
                </p>
                <div class="d-flex align-items-center gap-3">
                  <img src="{{ asset('images/home/smiling-man-near-modern-building_23-2147747855.webp') }}" loading="lazy" class="rounded-circle" width="60" height="60">
                  <div>
                    <h6 class="mb-0 fw-semibold">Aman Thakur</h6>
                    <small class="text-muted">Food Lover</small>
                  </div>
                </div>
              </article>
            </div>

            <!-- Card 3 -->
            <div class="col-lg-4 d-none d-lg-block">
              <article class="p-4 rounded-4 shadow-sm h-100 bg-light">
                <p class="mb-4">
                  “Arya Meals ka khana bilkul ghar jaisa hota hai. Taste fresh hota hai aur delivery bhi fast hai. Chamba me best service.”
                </p>
                <div class="d-flex align-items-center gap-3">
                  <img src="{{ asset('images/home/young-indian-man-traditional-wearing-showing-ok-sign-white-wall_1157-47467.webp') }}" loading="lazy" class="rounded-circle" width="60" height="60">
                  <div>
                    <h6 class="mb-0 fw-semibold">Rohit Sharma</h6>
                    <small class="text-muted">Local Resident, Chamba</small>
                  </div>
                </div>
              </article>
            </div>

          </div>
        </div>
      </div>

      <!-- ================= SLIDE 2 ================= -->
      <div class="carousel-item">
        <div class="container">
          <div class="row g-4 justify-content-center">

            <!-- Card 1 -->
            <div class="col-lg-4 col-md-6">
              <article class="p-4 rounded-4 shadow-sm h-100 bg-light">
                <p class="mb-4">
                  “Office meetings ke liye Arya Meals se bulk order kiya tha. Quantity perfect thi aur taste sabko bahut pasand aaya.”
                </p>
                <div class="d-flex align-items-center gap-3">
                  <img src="{{ asset('images/home/young-male-tourist-sitting-with-backpack-near-beautiful-river_23-2148187286.webp') }}" loading="lazy" class="rounded-circle" width="60" height="60">
                  <div>
                    <h6 class="mb-0 fw-semibold">Sandeep Mehta</h6>
                    <small class="text-muted">Corporate Client</small>
                  </div>
                </div>
              </article>
            </div>

            <!-- Card 2 -->
            <div class="col-lg-4 col-md-6 d-none d-md-block">
              <article class="p-4 rounded-4 shadow-sm h-100 bg-light">
                <p class="mb-4">
                  “Main hostel me rehta hoon aur daily mess food achha nahi milta tha. Arya Meals ne meri problem solve kar di.”
                </p>
                <div class="d-flex align-items-center gap-3">
                  <img src="{{ asset('images/home/young-indian-student-man-red-checkered-shirt-jeans-with-backpack-posed-street_627829-2685.webp') }}" loading="lazy" class="rounded-circle" width="60" height="60">
                  <div>
                    <h6 class="mb-0 fw-semibold">Rahul Patiyal</h6>
                    <small class="text-muted">College Student</small>
                  </div>
                </div>
              </article>
            </div>

            <!-- Card 3 -->
            <div class="col-lg-4 d-none d-lg-block">
              <article class="p-4 rounded-4 shadow-sm h-100 bg-light">
                <p class="mb-4">
                  “Taste consistent rehta hai aur customer support bhi fast hai. Highly recommended!”
                </p>
                <div class="d-flex align-items-center gap-3">
                  <img src="{{ asset('images/home/smiling-man-near-modern-building_23-2147747855.webp') }}" loading="lazy" class="rounded-circle" width="60" height="60">
                  <div>
                    <h6 class="mb-0 fw-semibold">Vikas Sharma</h6>
                    <small class="text-muted">Regular Customer</small>
                  </div>
                </div>
              </article>
            </div>

          </div>
        </div>
      </div>

    </div>

    <!-- Controls -->
    <button class="carousel-control-prev" type="button" data-bs-target="#testimonialSlider" data-bs-slide="prev">
      <span class="carousel-control-prev-icon"></span>
    </button>

    <button class="carousel-control-next" type="button" data-bs-target="#testimonialSlider" data-bs-slide="next">
      <span class="carousel-control-next-icon"></span>
    </button>

  </div>
</section>

<!-- why choose -->

<section class="why-choose bg-dark" id="why">
  <div class="container-fluid">
    <div class="row align-items-center">

      <!-- LEFT IMAGE CARD -->
      <div class="col-lg-6 mb-4 mb-lg-0">
        <div class="why-card">
          <img src="{{ asset('images/home/0aaea6e8-ec2a-4d01-bc2a-7d2f9b95d79f.jpg') }}" alt="Why choose arya meals">
        </div>
      </div>

      <!-- RIGHT CONTENT -->
      <div class="col-lg-6 text-white">
        <h2 class="fw-bold">Why Choose Arya Meals in Chamba?</h2>
        <ul class="why-list">
          <li>100% Local Chamba Service</li>
          <li>No App Download Needed</li>
          <li>Fast WhatsApp Response</li>
          <li>Cash / UPI Accepted</li>
          <li>Trusted Local Delivery</li>
        </ul>

        <div class="d-flex gap-3 flex-wrap mt-4">
          <a href="https://aryameals.com/partner-register" class="arya-btn">
            Join AryaMeals Now
          </a>
        </div>

      </div>
    </div>
  </div>
</section>
<!-- service area -->
<section class="py-5 bg-light" aria-label="Food delivery service areas in Chamba" id="area">
  <div class="container-fluid px-4">

    <!-- Heading -->
    <div class="text-center mb-4">
      <h2 class="fw-bold">Service Areas – Arya Meals Chamba</h2>
      <p class="mb-0">
        We deliver food across.
      </p>
    </div>

    <div class="row align-items-center g-4">

      <!-- Locations -->
      <div class="col-lg-6">
        <div class="row g-3">

          <div class="col-6">
            <div class="d-flex align-items-center gap-2">
              <span class="badge area-icon text-dark p-2 rounded-circle"><i class="fa-solid fa-location-dot"></i></span>
              <strong>Chamba</strong>
            </div>
          </div>

          <div class="col-6">
            <div class="d-flex align-items-center gap-2">
              <span class="badge area-icon text-dark p-2 rounded-circle"><i class="fa-solid fa-location-dot"></i></span>
              <strong>Kriya</strong>
            </div>
          </div>

          <div class="col-6">
            <div class="d-flex align-items-center gap-2">
              <span class="badge area-icon text-dark p-2 rounded-circle"><i class="fa-solid fa-location-dot"></i></span>
              <strong>Sultanpur</strong>
            </div>
          </div>

          <div class="col-6">
            <div class="d-flex align-items-center gap-2">
              <span class="badge area-icon text-dark p-2 rounded-circle"><i class="fa-solid fa-location-dot"></i></span>
              <strong>Balu</strong>
            </div>
          </div>

          <div class="col-6">
            <div class="d-flex align-items-center gap-2">
              <span class="badge area-icon text-dark p-2 rounded-circle"><i class="fa-solid fa-location-dot"></i></span>
              <strong>Bhadram</strong>
            </div>
          </div>

          <div class="col-6">
            <div class="d-flex align-items-center gap-2">
              <span class="badge area-icon text-dark p-2 rounded-circle"><i class="fa-solid fa-location-dot"></i></span>
              <strong>Sarol</strong>
            </div>
          </div>

          <div class="col-6">
            <div class="d-flex align-items-center gap-2">
              <span class="badge area-icon text-dark p-2 rounded-circle"><i class="fa-solid fa-location-dot"></i></span>
              <strong>Kiyani</strong>
            </div>
          </div>

          <div class="col-6">
            <div class="d-flex align-items-center gap-2">
              <span class="badge area-icon text-dark p-2 rounded-circle"><i class="fa-solid fa-location-dot"></i></span>
              <strong>Parel</strong>
            </div>
          </div>

        </div>

        <!-- Button -->
        <div class="mt-4">
          <a href="https://wa.me/918544772623"
            class="arya-btn"
            aria-label="Check food delivery availability on WhatsApp">
            Check Availability
          </a>
        </div>
      </div>

      <!-- Map -->
      <div class="col-lg-6">
        <div class="ratio ratio-16x9 rounded overflow-hidden shadow-sm">
          <iframe
            src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d430363.64841403515!2d76.126949!3d32.573763!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x391cbdc612ce985d%3A0x9cea4209f3cc1ea7!2sSultanpur%2C%20Chamba%2C%20Himachal%20Pradesh%20176314%2C%20India!5e0!3m2!1sen!2sus!4v1768566568511!5m2!1sen!2sus"
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
            title="Arya Meals delivery areas map">
          </iframe>
        </div>
      </div>

    </div>
  </div>
</section>
<!-- call to action -->
<section class="container-fluid px-4 py-5 text-center cta-section"
  id="call-to-action" style="background-image:url('images/home/27.jpg');background-position:center;background-repeat:no-repeat;background-size:cover;">
  <h5 class="text-uppercase fw-semibold sub-hcolor mb-2">
    READY TO ORDER
  </h5>
  <h2 class="fw-bold text-white">Hungry? Order Now from Arya Meals</h2>

  <p class="text-white">
    Fresh food, fast delivery aur easy ordering – sab kuch ek jagah.
  </p>

  <p class="mb-4 text-white">
    Abhi WhatsApp par order karein aur tasty khana enjoy karein
  </p>

  <div class="text-center mt-4">
    <a href="https://wa.me/918544772623"
      class="arya-btn arya-btn-lg"
      aria-label="Join AryaMeals on WhatsApp">
      Join AryaMeals Now
    </a>
  </div>
</section>
@endsection
