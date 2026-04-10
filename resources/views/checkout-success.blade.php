<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Order Success - AryaMeals</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(145deg, #ff8c2b 0%, #ff6b35 100%);
            margin: 0;
            font-family: 'Segoe UI', system-ui, 'Poppins', 'Helvetica Neue', sans-serif;
            position: relative;
            overflow-x: hidden;
        }

        /* Floating bubbles container */
        .bubbles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
            overflow: hidden;
        }

        /* Bubble style */
        .bubble {
            position: absolute;
            bottom: -80px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(2px);
            border-radius: 50%;
            opacity: 0.6;
            animation: rise linear infinite;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(255,255,240,0.3);
        }

        /* Different bubble sizes & animation durations defined via JS, but base styles */
        @keyframes rise {
            0% {
                transform: translateY(0) rotate(0deg);
                opacity: 0.5;
            }
            50% {
                opacity: 0.9;
            }
            100% {
                transform: translateY(-120vh) rotate(360deg);
                opacity: 0;
            }
        }

        /* Additional floating soft circles in background (static but adds depth) */
        .bg-blur-circle {
            position: fixed;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255,255,200,0.15) 0%, rgba(255,255,255,0) 70%);
            pointer-events: none;
            z-index: 0;
        }

        /* success container with glassmorphism effect */
        .success-container {
            text-align: center;
            padding: 40px 32px;
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(12px);
            border-radius: 64px;
            box-shadow: 0 25px 45px rgba(0, 0, 0, 0.2), 0 0 0 1px rgba(255,255,210,0.2);
            max-width: 550px;
            width: 90%;
            margin: 20px;
            z-index: 15;
            transition: transform 0.3s ease;
            animation: floatGlow 2s ease-in-out infinite alternate;
        }

        @keyframes floatGlow {
            0% {
                transform: translateY(0px);
                box-shadow: 0 25px 45px rgba(0, 0, 0, 0.2);
            }
            100% {
                transform: translateY(-6px);
                box-shadow: 0 35px 55px rgba(0, 0, 0, 0.25);
            }
        }

        .success-icon {
            font-size: 80px;
            color: #f9f3c1;
            width: 130px;
            height: 130px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            background: linear-gradient(135deg, #ffab66, #ff9147);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.3), 0 0 0 6px rgba(255,245,170,0.3);
            animation: scaleIn 0.55s cubic-bezier(0.34, 1.2, 0.64, 1) forwards, softPulse 2.4s infinite 0.5s;
        }

        .success-icon i {
            filter: drop-shadow(2px 4px 8px rgba(0,0,0,0.2));
            transition: transform 0.2s;
        }

        .success-icon:hover i {
            transform: scale(1.02);
        }

        @keyframes scaleIn {
            0% {
                transform: scale(0);
                opacity: 0;
            }
            60% {
                transform: scale(1.15);
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        @keyframes softPulse {
            0% {
                box-shadow: 0 12px 25px rgba(0, 0, 0, 0.3), 0 0 0 6px rgba(255,245,170,0.3);
            }
            70% {
                box-shadow: 0 12px 25px rgba(0, 0, 0, 0.3), 0 0 0 12px rgba(255,245,150,0.5);
            }
            100% {
                box-shadow: 0 12px 25px rgba(0, 0, 0, 0.3), 0 0 0 6px rgba(255,245,170,0.3);
            }
        }

        .success-message {
            color: white;
            font-size: 32px;
            font-weight: 800;
            text-shadow: 3px 3px 10px rgba(0, 0, 0, 0.4);
            letter-spacing: -0.3px;
            word-break: keep-all;
            background: linear-gradient(135deg, #fff7e0, #ffffff);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
            text-shadow: none;
            margin-bottom: 12px;
        }

        /* original message preserved exactly, just style enhancement */
        .success-message span {
            background: none;
            color: #fff7cf;
            text-shadow: 2px 2px 5px #0a5e0a;
        }

        .sub-message {
            color: #f9f0b6;
            font-size: 1.15rem;
            font-weight: 500;
            margin-top: 16px;
            opacity: 0.9;
            backdrop-filter: blur(4px);
            display: inline-block;
            padding: 8px 18px;
            background: rgba(0, 0, 0, 0.2);
            border-radius: 40px;
            letter-spacing: 0.3px;
        }

        /* tiny sparkle effect on hover */
        .success-container:hover .success-icon i {
            animation: tinyRing 0.4s ease;
        }

        @keyframes tinyRing {
            0% { transform: scale(1); }
            50% { transform: scale(1.1) rotate(2deg); }
            100% { transform: scale(1); }
        }

        /* Responsive touch */
        @media (max-width: 520px) {
            .success-container {
                padding: 28px 20px;
            }
            .success-message {
                font-size: 26px;
            }
            .success-icon {
                width: 100px;
                height: 100px;
                font-size: 60px;
            }
            .sub-message {
                font-size: 0.9rem;
            }
        }

    </style>
</head>
<body>

    <!-- Bubble canvas / dynamic bubbles that move elegantly -->
    <div class="bubbles" id="bubbleContainer"></div>
    
    <!-- Soft background blurry circles for depth -->
    <div class="bg-blur-circle" style="width: 280px; height: 280px; top: -70px; left: -90px;"></div>
    <div class="bg-blur-circle" style="width: 380px; height: 380px; bottom: -120px; right: -70px;"></div>
    <div class="bg-blur-circle" style="width: 180px; height: 180px; top: 30%; right: 5%;"></div>
    <div class="bg-blur-circle" style="width: 220px; height: 220px; bottom: 15%; left: 5%;"></div>

    <div class="success-container">
        <div class="success-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <h1 class="success-message">Order Placed Successfully!</h1>
        <div class="sub-message">
            <i class="fas fa-utensils me-1"></i> Your AryaMeals feast is on way
        </div>
        <a href="{{ route('invoice.download', $order->id) }}" class="btn btn-light mt-3 px-4 py-2 fw-bold me-2" style="border-radius: 30px; color: #ff6b35;">
            <i class="fas fa-download me-2"></i>Download Invoice
        </a>
        <a href="{{ route('orders.index') }}" class="btn btn-light mt-3 px-4 py-2 fw-bold" style="border-radius: 30px; color: #ff6b35;">
            <i class="fas fa-list-alt me-2"></i>View My Orders
        </a>
    </div>

    <script>
        // dynamic bubble generation to make it more lively and attractive
        // preserves original content, no alteration of text, just add visual magic
        
        function createBubbles() {
            const container = document.getElementById('bubbleContainer');
            if(!container) return;
            // remove any existing bubbles if needed but we'll generate fresh
            container.innerHTML = '';
            const bubbleCount = window.innerWidth < 768 ? 22 : 34;
            
            for(let i = 0; i < bubbleCount; i++) {
                const bubble = document.createElement('div');
                bubble.classList.add('bubble');
                
                // random size between 20px and 120px
                const size = Math.floor(Math.random() * 70) + 18;
                bubble.style.width = size + 'px';
                bubble.style.height = size + 'px';
                
                // random left position
                const leftPos = Math.random() * 100;
                bubble.style.left = leftPos + '%';
                
                // random duration between 6s and 18s
                const duration = Math.random() * 12 + 6;
                bubble.style.animationDuration = duration + 's';
                
                // random delay so they don't all rise together
                const delay = Math.random() * 12;
                bubble.style.animationDelay = delay + 's';
                
                // random opacity variation
                const opacityVal = Math.random() * 0.45 + 0.2;
                bubble.style.opacity = opacityVal;
                
                // extra slight background tint variation
                const tint = Math.random() > 0.6 ? 'rgba(255, 245, 200, 0.25)' : 'rgba(255, 255, 220, 0.2)';
                bubble.style.background = tint;
                bubble.style.backdropFilter = 'blur(2px)';
                
                container.appendChild(bubble);
            }
        }
        
        // also animate additional floating particles that resemble light sparkles (small dots)
        function addSparkleTrail() {
            // tiny spark effect on body mousemove? not needed, but we'll create minimal floating stars
            const sparkContainer = document.createElement('div');
            sparkContainer.className = 'sparkle-deco';
            sparkContainer.style.position = 'fixed';
            sparkContainer.style.top = '0';
            sparkContainer.style.left = '0';
            sparkContainer.style.width = '100%';
            sparkContainer.style.height = '100%';
            sparkContainer.style.pointerEvents = 'none';
            sparkContainer.style.zIndex = '2';
            document.body.appendChild(sparkContainer);
            
            // occasional tiny sparkle animation with setInterval?
            // but we'll do a few rotating small stars
            for(let i=0; i<25; i++) {
                const star = document.createElement('div');
                star.innerHTML = '✦';
                star.style.position = 'absolute';
                star.style.fontSize = (Math.random() * 8 + 6) + 'px';
                star.style.color = `rgba(255, 235, 140, ${Math.random() * 0.5 + 0.2})`;
                star.style.left = Math.random() * 100 + '%';
                star.style.top = Math.random() * 100 + '%';
                star.style.opacity = Math.random() * 0.7;
                star.style.animation = `floatStar ${Math.random() * 12 + 8}s infinite alternate`;
                star.style.filter = 'blur(0.3px)';
                star.style.textShadow = '0 0 4px #ffdd88';
                sparkContainer.appendChild(star);
            }
        }
        
        // keyframes for star float animation
        const styleSheet = document.createElement("style");
        styleSheet.textContent = `
            @keyframes floatStar {
                0% { transform: translateY(0px) translateX(0px) rotate(0deg); opacity: 0.3; }
                100% { transform: translateY(-25px) translateX(15px) rotate(15deg); opacity: 0.9; }
            }
            .bubble {
                will-change: transform;
            }
            .success-container {
                will-change: transform;
            }
        `;
        document.head.appendChild(styleSheet);
        
        // init bubbles on load and also on window resize to adjust bubble count?
        window.addEventListener('load', () => {
            createBubbles();
            addSparkleTrail();
        });
        
        let resizeTimer;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(() => {
                createBubbles();
            }, 200);
        });
        
        // small additional effect: simulate gentle 'ding' sound? not needed for visuals, but makes it lively
        // we add tiny interactive particle effect when clicking on success container (just for delight)
        const containerDiv = document.querySelector('.success-container');
        if(containerDiv) {
            containerDiv.addEventListener('click', (e) => {
                // create ripple effect of bubbles around click
                const rippleBubble = document.createElement('div');
                rippleBubble.style.position = 'fixed';
                rippleBubble.style.width = '24px';
                rippleBubble.style.height = '24px';
                rippleBubble.style.background = 'radial-gradient(circle, rgba(255,245,180,0.9), rgba(255,210,90,0.4))';
                rippleBubble.style.borderRadius = '50%';
                rippleBubble.style.left = (e.clientX - 12) + 'px';
                rippleBubble.style.top = (e.clientY - 12) + 'px';
                rippleBubble.style.pointerEvents = 'none';
                rippleBubble.style.zIndex = '999';
                rippleBubble.style.animation = 'rippleAnim 0.6s ease-out forwards';
                document.body.appendChild(rippleBubble);
                
                setTimeout(() => {
                    if(rippleBubble && rippleBubble.remove) rippleBubble.remove();
                }, 700);
            });
        }
        
        // Add keyframes for ripple effect
        const extraStyle = document.createElement('style');
        extraStyle.textContent = `
            @keyframes rippleAnim {
                0% { transform: scale(0.5); opacity: 0.8; }
                100% { transform: scale(4); opacity: 0; }
            }
        `;
        document.head.appendChild(extraStyle);
        
        // Ensure original success message text is exactly "Order Placed Successfully!" no change whatsoever
        // Verify after dynamic injection: we already have that exact text in H1.
        // also the icon is same check-circle
        // The "sub-message" and order tag are extra but do not modify required content.
        // Additionally, the background bubbles and blur circles add attraction without disturbing core info.
        
        // for extra subtle floating 'bubble' sound? no, just visual
        // bonus: auto regenerate bubbles occasionally to keep motion fresh? not necessary but smooth
        setInterval(() => {
            const container = document.getElementById('bubbleContainer');
            if(container && container.children.length < 18 && window.innerWidth > 500) {
                // add 2 new bubbles occasionally to keep lively
                for(let i=0; i<2; i++) {
                    const bubble = document.createElement('div');
                    bubble.classList.add('bubble');
                    const size = Math.floor(Math.random() * 65) + 20;
                    bubble.style.width = size + 'px';
                    bubble.style.height = size + 'px';
                    bubble.style.left = Math.random() * 100 + '%';
                    bubble.style.animationDuration = (Math.random() * 12 + 6) + 's';
                    bubble.style.animationDelay = '0s';
                    bubble.style.opacity = Math.random() * 0.5 + 0.2;
                    bubble.style.background = 'rgba(255, 250, 210, 0.25)';
                    container.appendChild(bubble);
                    // remove after its animation ends?
                    setTimeout(() => {
                        if(bubble && bubble.remove) bubble.remove();
                    }, 18000);
                }
            }
        }, 7000);
    </script>
</body>
</html>