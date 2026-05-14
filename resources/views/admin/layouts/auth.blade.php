<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Login - Find My Roommate</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        .admin-login-bg {
            min-height: 100vh;
            background: #0f0f1a;
            background-image:
                radial-gradient(ellipse 80% 60% at 50% -20%, rgba(99, 102, 241, 0.25) 0%, transparent 60%),
                radial-gradient(ellipse 60% 40% at 80% 80%, rgba(139, 92, 246, 0.15) 0%, transparent 50%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        /* Animated grid pattern */
        .admin-login-bg::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(99, 102, 241, 0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(99, 102, 241, 0.05) 1px, transparent 1px);
            background-size: 60px 60px;
            mask-image: radial-gradient(ellipse 70% 70% at 50% 50%, black 30%, transparent 100%);
        }

        /* Floating orbs */
        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            animation: float 8s ease-in-out infinite;
            pointer-events: none;
        }

        .orb-1 {
            width: 400px;
            height: 400px;
            background: rgba(99, 102, 241, 0.15);
            top: -100px;
            left: -100px;
            animation-delay: 0s;
        }

        .orb-2 {
            width: 300px;
            height: 300px;
            background: rgba(139, 92, 246, 0.12);
            bottom: -80px;
            right: -80px;
            animation-delay: 3s;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(20px, -20px) scale(1.05); }
            66% { transform: translate(-10px, 15px) scale(0.97); }
        }

        .login-card {
            background: rgba(17, 17, 34, 0.85);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(99, 102, 241, 0.2);
            border-radius: 1.5rem;
            padding: 2.5rem;
            width: 100%;
            max-width: 420px;
            position: relative;
            z-index: 10;
            box-shadow:
                0 0 0 1px rgba(255,255,255,0.03),
                0 25px 50px rgba(0, 0, 0, 0.6),
                0 0 80px rgba(99, 102, 241, 0.08);
        }

        .admin-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            background: rgba(99, 102, 241, 0.15);
            border: 1px solid rgba(99, 102, 241, 0.3);
            border-radius: 9999px;
            padding: 0.25rem 0.75rem;
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: rgba(165, 168, 255, 1);
            margin-bottom: 1.25rem;
        }

        .admin-badge .dot {
            width: 6px;
            height: 6px;
            background: #6366f1;
            border-radius: 50%;
            box-shadow: 0 0 6px #6366f1;
            animation: pulse-dot 2s ease-in-out infinite;
        }

        @keyframes pulse-dot {
            0%, 100% { opacity: 1; box-shadow: 0 0 6px #6366f1; }
            50% { opacity: 0.6; box-shadow: 0 0 12px #6366f1; }
        }

        .form-label {
            display: block;
            font-size: 0.8rem;
            font-weight: 500;
            color: rgba(200, 200, 220, 0.9);
            margin-bottom: 0.5rem;
            letter-spacing: 0.01em;
        }

        .form-input {
            display: block;
            width: 100%;
            padding: 0.65rem 0.875rem;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 0.625rem;
            color: #ffffff;
            font-size: 0.875rem;
            transition: all 0.2s ease;
            box-sizing: border-box;
        }

        .form-input::placeholder {
            color: rgba(150, 150, 175, 0.5);
        }

        .form-input:focus {
            outline: none;
            border-color: rgba(99, 102, 241, 0.7);
            background: rgba(99, 102, 241, 0.06);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
        }

        .form-input:hover:not(:focus) {
            border-color: rgba(255, 255, 255, 0.18);
            background: rgba(255, 255, 255, 0.06);
        }

        .btn-signin {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
            padding: 0.75rem 1.25rem;
            background: linear-gradient(135deg, #6366f1 0%, #7c3aed 100%);
            color: white;
            font-size: 0.875rem;
            font-weight: 600;
            border-radius: 0.625rem;
            border: none;
            cursor: pointer;
            transition: all 0.25s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(99, 102, 241, 0.4);
            letter-spacing: 0.01em;
        }

        .btn-signin::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, transparent 60%);
            opacity: 0;
            transition: opacity 0.2s ease;
        }

        .btn-signin:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 30px rgba(99, 102, 241, 0.55);
        }

        .btn-signin:hover::before {
            opacity: 1;
        }

        .btn-signin:active {
            transform: translateY(0px);
        }

        .divider {
            border: none;
            border-top: 1px solid rgba(255, 255, 255, 0.07);
            margin: 1.5rem 0;
        }

        .error-text {
            font-size: 0.75rem;
            color: #f87171;
            margin-top: 0.375rem;
        }

        .status-msg {
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.25);
            border-radius: 0.5rem;
            padding: 0.625rem 0.875rem;
            font-size: 0.8rem;
            color: #86efac;
            margin-bottom: 1.25rem;
        }

        .forgot-link {
            font-size: 0.75rem;
            color: rgba(165, 168, 255, 0.8);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }

        .forgot-link:hover {
            color: #a5b4fc;
        }

        .user-login-link {
            color: rgba(165, 168, 255, 0.7);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.8rem;
            transition: color 0.2s;
        }

        .user-login-link:hover {
            color: #a5b4fc;
        }
    </style>
</head>
<body>
<div class="admin-login-bg">
    <!-- Floating orbs -->
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>

    @yield('content')
</div>
</body>
</html>
