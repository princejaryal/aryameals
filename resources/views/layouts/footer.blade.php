<!-- Website Footer - Client Theme -->
<footer class="footer-main bg-dark text-white pt-5">
    <!-- SUPPORT BANNER -->
    <section class="container-fluid px-lg-4 mb-5">
        <div class="row">
            <div class="col-12">
                <div class="bgcontact-pannel rounded-4 p-4 p-lg-5 d-flex flex-column flex-lg-row align-items-center justify-content-between gap-4">

                    <div class="d-flex align-items-center gap-3 text-dark">
                        <div class="bg-white mobile-circlef rounded-circle d-flex align-items-center justify-content-center" style="width:60px;height:60px;">
                            <i class="fa-solid fa-headset fs-3 text-headma"></i>
                        </div>

                        <div>
                            <h5 class="fw-bold mb-1 text-white">Need Support?</h5>
                            <p class="mb-0 small text-white">
                                For any food order, pickup, or delivery assistance,
                                connect with Arya Meals support.
                            </p>
                        </div>
                    </div>

                    <div class="d-flex gap-3 flex-wrap">
                        <a href="tel:+918544772623" class="arya-btn arya-btn-dark px-4">
                            <i class="fa-solid fa-phone me-2"></i>Call Us Now
                        </a>

                        <a href="mailto:support@aryameals.com" class="arya-btn arya-btn-light px-4">
                            <i class="fa-solid fa-envelope me-2"></i>Email Us
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <!-- MAIN FOOTER -->
    <section class="container-fluid px-lg-4 mainfteer">
        <div class="row gy-4">

            <!-- ABOUT -->
            <article class="col-lg-3 col-md-6">
                <a href="{{ url('/') }}"> <img src="{{ asset('images/home/Arya-1.png') }}" alt="Arya Meals Logo" height="70" class="mb-3"></a>

                <p class="small opacity-75">
                    Arya Meals brings fresh & delicious food to your doorstep in Chamba.
                    Order veg, non-veg meals, fast food & grocery easily via WhatsApp.
                </p>
            </article>

            <!-- QUICK LINKS -->
            <nav class="col-lg-3 col-md-6">
                <h5 class="fw-semibold mb-3">Quick Links</h5>
                <ul class="list-unstyled small">
                    <li><a href="{{ url('/') }}" class="text-white text-decoration-none d-block py-1"><i class="fa-solid fa-angles-right"></i> Home</a></li>
                    <li><a href="{{ url('/restaurants') }}" class="text-white text-decoration-none d-block py-1"><i class="fa-solid fa-angles-right"></i> Restaurants</a></li>
                    <li><a href="{{ url('/about') }}" class="text-white text-decoration-none d-block py-1"><i class="fa-solid fa-angles-right"></i> About</a></li>
                    <li><a href="{{ url('/contact') }}" class="text-white text-decoration-none d-block py-1"><i class="fa-solid fa-angles-right"></i> Contact</a></li>
                </ul>
            </nav>

            <!-- SUPPORT -->
            <address class="col-lg-3 col-md-6 not-italic">
                <h5 class="fw-semibold mb-3">Support</h5>

                <p class="small mb-2">
                    <a href="tel: +918544772623"> <i class="fa-solid fa-phone me-2"></i> +918544772623</a>
                </p>

                <p class="small mb-2">
                    <a href="mailto: support@aryameals.com"><i class="fa-solid fa-envelope me-2"></i> support@aryameals.com</a>
                </p>

                <p class="small mb-2">
                    <a href="{{ url('/') }}"><i class="fa-solid fa-globe me-2"></i> aryameals.com</a>
                </p>
            </address>

            <!-- SUBSCRIBE -->
            <div class="col-lg-3 col-md-6">
                <h5 class="fw-semibold mb-3">Subscribe Us</h5>

                <p class="small opacity-75">
                    Get updates on new dishes, special offers & discounts from Arya Meals Chamba.
                </p>

                <form id="subscribeForm" class="d-flex gap-2" method="post">
                    <input type="email" name="email" class="form-control form-control-sm"
                        placeholder="Enter your email" required>
                    <button type="submit" class="arya-btn arya-btn-sm btn-sm px-3">
                        Subscribe
                    </button>
                </form>

                <div id="msg" style="margin-top:10px;"></div>

                <div class="d-flex social-iconsf gap-3 mt-3">
                    <a href="https://www.facebook.com/people/aryameals/61585436342663/?rdid=PmjpW534te9EygGt&share_url=https%3A%2F%2Fwww.facebook.com%2Fshare%2F1Cu7m9esiC%2F" class="text-white" target="_blank"><i class="fa-brands fa-facebook"></i></a>
                    <a href="https://www.instagram.com/aryameals/?utm_source=qr&igsh=bTBoYm0zcXplbzcz#" class="text-white" target="_blank"><i class="fa-brands fa-instagram"></i></a>
                    <a href="https://api.whatsapp.com/send/?phone=+918544772623&text&type=phone_number&app_absent=0" class="text-white"><i class="fa-brands fa-whatsapp"></i></a>
                </div>
            </div>

        </div>
    </section>

    <!-- COPYRIGHT -->
    <section class="border-top border-secondary mt-5">
        <div class="container-fluid text-center py-3 small opacity-75 copuright">
            © 2026 <a href="https://www.aryainfotechs.com/">Aryainfotechs</a>. All Rights Reserved.
        </div>
    </section>
</footer>