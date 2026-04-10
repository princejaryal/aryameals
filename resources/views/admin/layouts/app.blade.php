<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Arya Meals - Admin Dashboard')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        body::-webkit-scrollbar,
        aside::-webkit-scrollbar {
            display: none;
        }
        /* Color Variables */
        :root {
            /* Primary Colors */
            --primary-50: #f0fdf4;
            --primary-100: #dcfce7;
            --primary-200: #bbf7d0;
            --primary-300: #86efac;
            --primary-400: #4ade80;
            --primary-500: #22c55e;
            --primary-600: #16a34a;
            --primary-700: #15803d;
            --primary-800: #166534;
            --primary-900: #14532d;
            
            /* Secondary Colors */
            --secondary-50: #f8fafc;
            --secondary-100: #f1f5f9;
            --secondary-200: #e2e8f0;
            --secondary-300: #cbd5e1;
            --secondary-400: #94a3b8;
            --secondary-500: #64748b;
            --secondary-600: #475569;
            --secondary-700: #334155;
            --secondary-800: #1e293b;
            --secondary-900: #0f172a;
            --secondary-950: #020617;
            
            /* Accent Colors */
            --accent-amber: #f59e0b;
            --accent-amber-dark: #d97706;
            --accent-blue: #3b82f6;
            --accent-blue-dark: #1e40af;
            --accent-purple: #8b5cf6;
            --accent-purple-dark: #6d28d9;
            --accent-red: #ef4444;
            --accent-red-dark: #dc2626;
            
            /* Neutral Colors */
            --white: #ffffff;
            --black: #000000;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
            
            /* Gradients */
            --gradient-primary: linear-gradient(135deg, var(--primary-500), var(--primary-600));
            --gradient-secondary: linear-gradient(135deg, var(--secondary-100), var(--secondary-200), var(--secondary-300));
            --gradient-sidebar: linear-gradient(to bottom, #1e3a8a, #1e40af, #172554);
            --gradient-glass: rgba(255, 255, 255, 0.95);
            --gradient-orders: linear-gradient(to right, var(--primary-500), var(--primary-700));
            --gradient-preparing: linear-gradient(to right, var(--accent-amber), var(--accent-amber-dark));
            --gradient-delivery: linear-gradient(to right, var(--accent-blue), var(--accent-blue-dark));
            --gradient-revenue: linear-gradient(to right, var(--accent-purple), var(--accent-purple-dark));
            
            /* Shadows */
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            --shadow-glass: 0 8px 32px rgba(31, 38, 135, 0.1);
            
            /* Transitions */
            --transition-fast: all 0.2s ease;
            --transition-normal: all 0.3s ease;
            
            /* Border Radius */
            --radius-sm: 0.375rem;
            --radius-md: 0.5rem;
            --radius-lg: 0.75rem;
            --radius-xl: 1rem;
            --radius-2xl: 1.5rem;
            --radius-full: 9999px;
            
            /* Font Family */
            --font-primary: 'Inter', ui-sans-serif, system-ui, sans-serif;
        }

        * {
            font-family: 'Inter', sans-serif;
          
        }

        body {
            background: var(--gradient-secondary);
            min-height: 100vh;
        }

        .sidebar {
            background: var(--gradient-sidebar) !important;
        }
        .stat-card.orders {
            background: var(--gradient-orders);
        }
        .stat-card.preparing {
            background: var(--gradient-preparing);
        }
        .stat-card.delivery {
            background: var(--gradient-delivery);
        }
        .stat-card.revenue {
            background: var(--gradient-revenue);
        }

        .sidebar-fixed {
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            z-index: 50;
            transition: width 0.3s ease-in-out;
        }

        .header-fixed {
            position: fixed;
            top: 0;
            left: 16rem;
            right: 0;
            z-index: 40;
            transition: left 0.3s ease-in-out;
        }

        .main-content {
            margin-left: 16rem;
            margin-top: 4rem;
            padding: 2rem;
            transition: margin-left 0.3s ease-in-out;
        }

        .sidebar-collapsed .sidebar-fixed {
            width: 4rem !important;
        }

        .sidebar-collapsed .header-fixed {
            left: 4rem;
        }

        .sidebar-collapsed .main-content {
            margin-left: 4rem;
        }

        .sidebar-collapsed .sidebar-label {
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.2s ease-in-out, visibility 0.2s ease-in-out;
        }

        .sidebar-label {
            opacity: 1;
            visibility: visible;
            transition: opacity 0.2s ease-in-out, visibility 0.2s ease-in-out;
        }

        .glass-effect {
            background: var(--gradient-glass);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: var(--shadow-glass);
        }

        .stat-card {
            background: linear-gradient(135deg, var(--white), var(--secondary-50));
            border: 1px solid var(--secondary-200);
            transition: var(--transition-normal);
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-md);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--gradient-start), var(--gradient-end));
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-xl);
        }

        .stat-card.orders {
            --gradient-start: #10b981;
            --gradient-end: #34d399;
        }

        .stat-card.orders-today {
            --gradient-start: #f59e0b;
            --gradient-end: #fbbf24;
        }

        .stat-card.avg-order {
            --gradient-start: #8b5cf6;
            --gradient-end: #6366f1;
        }

        .stat-card.delivery {
            --gradient-start: #3b82f6;
            --gradient-end: #60a5fa;
        }

        .stat-card.revenue {
            --gradient-start: #8b5cf6;
            --gradient-end: #a78bfa;
        }

        .status-badge {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.8;
            }
        }

        .nav-item {
            transition: var(--transition-fast);
            position: relative;
        }

        .nav-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 0;
            background: var(--gradient-primary);
            transition: height 0.3s ease;
            border-radius: 0 var(--radius-sm) var(--radius-sm) 0;
        }

        .nav-item:hover::before,
        .nav-item.active::before {
            height: 70%;
        }

        .nav-item.active {
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.15), rgba(22, 163, 74, 0.1));
            border: 1px solid rgba(34, 197, 94, 0.3);
            border-radius: var(--radius-xl);
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.1);
            backdrop-filter: blur(8px);
        }

        .data-table {
            border-radius: 12px;
            overflow: hidden;
        }

        .data-table thead {
            background: linear-gradient(135deg, var(--secondary-50), var(--secondary-100));
        }

        /* Common Component Styles */
        .glass-effect {
            background: var(--gradient-glass);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: var(--shadow-glass);
        }

        .sidebar {
            background: var(--gradient-sidebar);
        }

        .stat-card {
            background: linear-gradient(135deg, var(--white), var(--secondary-50));
            border: 1px solid var(--secondary-200);
            transition: var(--transition-normal);
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-md);
        }

        .stat-card.customers {
            background: var(--gradient-orders);
        }

        .stat-card.total-orders {
            background: var(--gradient-preparing);
        }

        .stat-card.avg-order {
            background: var(--gradient-delivery);
        }

        .stat-card.total-revenue {
            background: var(--gradient-revenue);
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-xl);
        }

        .nav-item {
            transition: var(--transition-fast);
            position: relative;
        }

        .nav-item.active {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.2), rgba(99, 102, 241, 0.1));
            border: 1px solid rgba(59, 130, 246, 0.4);
            border-radius: var(--radius-xl);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
            backdrop-filter: blur(8px);
        }

        .nav-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 0;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            transition: height 0.3s ease;
            border-radius: 0 var(--radius-sm) var(--radius-sm) 0;
        }

        .nav-item:hover::before,
        .nav-item.active::before {
            height: 70%;
        }

        /* Animation Classes */
        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.8;
            }
        }

        /* Test colors to verify variables are working */
        .test-primary {
            background-color: var(--primary-500);
            color: white;
            padding: 10px;
            margin: 5px;
            border-radius: 5px;
        }

        .test-secondary {
            background-color: var(--secondary-500);
            color: white;
            padding: 10px;
            margin: 5px;
            border-radius: 5px;
        }

        .test-accent {
            background-color: var(--accent-amber);
            color: white;
            padding: 10px;
            margin: 5px;
            border-radius: 5px;
        }

        /* Validation Error Animations */
        .validation-error {
            animation: slideInDown 0.4s ease-out;
            transition: all 0.3s ease;
        }

        .validation-error.hide {
            animation: slideOutUp 0.3s ease-out forwards;
        }

        @keyframes slideInDown {
            0% {
                transform: translateY(-20px);
                opacity: 0;
                max-height: 0;
            }
            100% {
                transform: translateY(0);
                opacity: 1;
                max-height: 200px;
            }
        }

        @keyframes slideOutUp {
            0% {
                transform: translateY(0);
                opacity: 1;
                max-height: 200px;
            }
            100% {
                transform: translateY(-20px);
                opacity: 0;
                max-height: 0;
            }
        }

        .field-error {
            animation: shake 0.5s ease-in-out;
            border-color: #ef4444 !important;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1) !important;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }

        .error-message {
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(-10px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Toast Notification Styles */
        .toast-notification {
            animation: slideInRight 0.4s ease-out;
            transition: all 0.3s ease;
        }

        .toast-notification.hide {
            animation: slideOutRight 0.3s ease-out forwards;
        }

        @keyframes slideInRight {
            0% {
                transform: translateX(100%);
                opacity: 0;
            }
            100% {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOutRight {
            0% {
                transform: translateX(0);
                opacity: 1;
            }
            100% {
                transform: translateX(100%);
                opacity: 0;
            }
        }

        /* Hidden Scrollbar Styles */
        .scrollbar-hide {
            -ms-overflow-style: none;  /* Internet Explorer 10+ */
            scrollbar-width: none;  /* Firefox */
        }
        .scrollbar-hide::-webkit-scrollbar {
            display: none;  /* Safari and Chrome */
        }
    </style>
    @stack('styles')
</head>

<body class="sidebar-collapsed bg-gradient-to-br from-slate-100 via-gray-100 to-slate-200 min-h-screen">
    <!-- Include Sidebar -->
    @include('admin.layouts.sidebar')

    <!-- Include Header -->
    @include('admin.layouts.header')

    <!-- Main Content Area -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- Scripts -->
    <script>
        // Sidebar hover functionality
        document.addEventListener('DOMContentLoaded', function() {
            var body = document.body;
            var sidebar = document.querySelector('.sidebar-fixed');

            if (body && sidebar) {
                // Sidebar hover functionality - open on hover when collapsed
                sidebar.addEventListener('mouseenter', function() {
                    if (body.classList.contains('sidebar-collapsed')) {
                        body.classList.remove('sidebar-collapsed');
                        body.classList.add('sidebar-temp-expanded');
                    }
                });

                sidebar.addEventListener('mouseleave', function() {
                    if (body.classList.contains('sidebar-temp-expanded')) {
                        body.classList.add('sidebar-collapsed');
                        body.classList.remove('sidebar-temp-expanded');
                    }
                });
            }
        });
    </script>

    @stack('scripts')
</body>

</html>