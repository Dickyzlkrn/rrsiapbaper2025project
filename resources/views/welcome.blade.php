<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIAP BAPER RRI - Sistem Informasi Pengajuan Barang Perlengkapan</title>
    <meta name="google-site-verification" content="hGITFWvC6oTWgLxrbIArOR2r5XPFu1Qj2lVNEnKcKmw" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            line-height: 1.6;
            overflow-x: hidden;
            background: #0a0a0a;
            color: #ffffff;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Performance optimizations */
        .will-change {
            will-change: transform;
        }

        .gpu-accelerated {
            transform: translateZ(0);
            backface-visibility: hidden;
            perspective: 1000px;
        }

        /* Gradient Background Animation */
        .gradient-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, #1a1a2e, #16213e, #0f3460);
            background-size: 400% 400%;
            animation: gradientShift 20s ease infinite;
            z-index: -2;
            will-change: background-position;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Floating particles */
        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(0, 212, 255, 0.3);
            border-radius: 50%;
            animation: float 8s ease-in-out infinite;
        }

        .particle:nth-child(odd) {
            background: rgba(255, 255, 255, 0.2);
            animation: floatReverse 10s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { 
                transform: translateY(100vh) translateX(0px) rotate(0deg) scale(0); 
                opacity: 0; 
            }
            10% { 
                opacity: 1; 
                transform: translateY(90vh) translateX(10px) rotate(36deg) scale(1); 
            }
            90% { 
                opacity: 1; 
                transform: translateY(10vh) translateX(-10px) rotate(324deg) scale(1); 
            }
        }

        @keyframes floatReverse {
            0%, 100% { 
                transform: translateY(-10vh) translateX(0px) rotate(360deg) scale(0); 
                opacity: 0; 
            }
            10% { 
                opacity: 1; 
                transform: translateY(0vh) translateX(-20px) rotate(324deg) scale(1); 
            }
            90% { 
                opacity: 1; 
                transform: translateY(100vh) translateX(20px) rotate(36deg) scale(1); 
            }
        }

        /* Glowing orbs */
        .glow-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(40px);
            animation: orbFloat 15s ease-in-out infinite;
            opacity: 0.1;
        }

        .glow-orb:nth-child(1) {
            width: 300px;
            height: 300px;
            background: linear-gradient(45deg, #00d4ff, #0099cc);
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }

        .glow-orb:nth-child(2) {
            width: 200px;
            height: 200px;
            background: linear-gradient(45deg, #ff6b6b, #ff8e8e);
            top: 60%;
            right: 15%;
            animation-delay: -5s;
        }

        .glow-orb:nth-child(3) {
            width: 250px;
            height: 250px;
            background: linear-gradient(45deg, #4ecdc4, #44a08d);
            bottom: 30%;
            left: 20%;
            animation-delay: -10s;
        }

        @keyframes orbFloat {
            0%, 100% { 
                transform: translateY(0px) translateX(0px) scale(1); 
            }
            33% { 
                transform: translateY(-30px) translateX(20px) scale(1.1); 
            }
            66% { 
                transform: translateY(20px) translateX(-15px) scale(0.9); 
            }
        }

        /* Header */
        header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            background: rgba(10, 10, 10, 0.8);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            z-index: 1000;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: clamp(0.5rem, 2vw, 1rem) clamp(1rem, 4vw, 2rem);
            max-width: 1400px;
            margin: 0 auto;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: clamp(0.5rem, 2vw, 1rem);
            font-size: clamp(1.2rem, 3vw, 1.5rem);
            font-weight: 700;
            color: #00d4ff;
            animation: logoGlow 3s ease-in-out infinite alternate;
        }

        .logo i {
            font-size: clamp(1.5rem, 4vw, 2rem);
            background: linear-gradient(45deg, #00d4ff, #0099cc);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: iconSpin 4s linear infinite;
        }

        @keyframes logoGlow {
            0% { text-shadow: 0 0 5px rgba(0, 212, 255, 0.3); }
            100% { text-shadow: 0 0 20px rgba(0, 212, 255, 0.8), 0 0 30px rgba(0, 212, 255, 0.4); }
        }

        @keyframes iconSpin {
            0% { transform: rotate(0deg) scale(1); }
            25% { transform: rotate(90deg) scale(1.1); }
            50% { transform: rotate(180deg) scale(1); }
            75% { transform: rotate(270deg) scale(1.1); }
            100% { transform: rotate(360deg) scale(1); }
        }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 2rem;
            align-items: center;
        }

        .nav-links a {
            text-decoration: none;
            color: #ffffff;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-links a:hover {
            color: #00d4ff;
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: #00d4ff;
            transition: width 0.3s ease;
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        .login-btn {
            background: linear-gradient(45deg, #00d4ff, #0099cc);
            color: white !important;
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(0, 212, 255, 0.3);
        }

        /* Mobile menu */
        .mobile-menu {
            display: none;
            flex-direction: column;
            cursor: pointer;
            padding: 0.5rem;
            z-index: 1001;
        }

        .mobile-menu span {
            width: 25px;
            height: 3px;
            background: #ffffff;
            margin: 3px 0;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 2px;
        }

        .mobile-menu.active span:nth-child(1) {
            transform: rotate(-45deg) translate(-5px, 6px);
        }

        .mobile-menu.active span:nth-child(2) {
            opacity: 0;
        }

        .mobile-menu.active span:nth-child(3) {
            transform: rotate(45deg) translate(-5px, -6px);
        }

        .mobile-nav {
            position: fixed;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100vh;
            background: rgba(10, 10, 10, 0.98);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 2rem;
            transition: left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 999;
        }

        .mobile-nav.active {
            left: 0;
        }

        .mobile-nav a {
            color: #ffffff;
            text-decoration: none;
            font-size: 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .mobile-nav a:hover {
            color: #00d4ff;
            transform: scale(1.1);
        }

        /* Hero Section */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 0 2rem;
            position: relative;
        }

        .hero-content {
            max-width: 800px;
            z-index: 2;
        }

        .hero h1 {
            font-size: 4rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            background: linear-gradient(45deg, #ffffff, #00d4ff, #ffffff);
            background-size: 200% 200%;
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: fadeInUp 1s ease 0.2s both, textShimmer 3s ease-in-out infinite;
        }

        @keyframes textShimmer {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .hero p {
            font-size: 1.3rem;
            margin-bottom: 2rem;
            color: rgba(255, 255, 255, 0.8);
            animation: fadeInUp 1s ease 0.4s both, textFloat 4s ease-in-out infinite;
        }

        @keyframes textFloat {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-5px); }
        }

        .hero-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            animation: fadeInUp 1s ease 0.6s both;
        }

        .btn-primary {
            background: linear-gradient(45deg, #00d4ff, #0099cc);
            color: white;
            padding: 1rem 2rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 1.1rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 212, 255, 0.3);
            animation: buttonPulse 2s ease-in-out infinite;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .btn-primary:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 15px 40px rgba(0, 212, 255, 0.5);
            animation: none;
        }

        @keyframes buttonPulse {
            0%, 100% { box-shadow: 0 5px 15px rgba(0, 212, 255, 0.3); }
            50% { box-shadow: 0 5px 25px rgba(0, 212, 255, 0.5), 0 0 30px rgba(0, 212, 255, 0.2); }
        }

        .btn-secondary {
            background: transparent;
            color: #ffffff;
            padding: 1rem 2rem;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            font-size: 1.1rem;
            position: relative;
            overflow: hidden;
        }

        .btn-secondary::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 0;
            height: 100%;
            background: rgba(0, 212, 255, 0.1);
            transition: width 0.3s ease;
            z-index: -1;
        }

        .btn-secondary:hover::before {
            width: 100%;
        }

        .btn-secondary:hover {
            border-color: #00d4ff;
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0, 212, 255, 0.2);
        }

        /* Features Section */
        .features {
            padding: 5rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .features h2 {
            text-align: center;
            font-size: 3rem;
            margin-bottom: 3rem;
            background: linear-gradient(45deg, #ffffff, #00d4ff);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .feature-card {
            background: rgba(255, 255, 255, 0.02);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            transition: all 0.5s ease;
            opacity: 0;
            transform: translateY(30px);
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, rgba(0, 212, 255, 0.1), rgba(0, 153, 204, 0.1));
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: -1;
        }

        .feature-card:hover::before {
            opacity: 1;
        }

        .feature-card:hover {
            transform: translateY(-15px) rotateY(5deg);
            box-shadow: 0 25px 60px rgba(0, 212, 255, 0.2);
            border-color: rgba(0, 212, 255, 0.4);
        }

        .feature-icon {
            font-size: 3rem;
            color: #00d4ff;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            animation: iconBounce 2s ease-in-out infinite;
        }

        .feature-card:hover .feature-icon {
            transform: scale(1.2) rotateY(360deg);
            text-shadow: 0 0 20px rgba(0, 212, 255, 0.5);
        }

        @keyframes iconBounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-10px); }
            60% { transform: translateY(-5px); }
        }

        .feature-card h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: #ffffff;
        }

        .feature-card p {
            color: rgba(255, 255, 255, 0.7);
            line-height: 1.6;
        }

        /* Stats Section */
        .stats {
            padding: 5rem 2rem;
            background: rgba(0, 212, 255, 0.05);
            backdrop-filter: blur(20px);
        }

        .stats-container {
            max-width: 1200px;
            margin: 0 auto;
            text-align: center;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .stat-item {
            padding: 1rem;
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 800;
            color: #00d4ff;
            display: block;
        }

        .stat-label {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.1rem;
            margin-top: 0.5rem;
        }

        /* Footer */
        footer {
            background: rgba(10, 10, 10, 0.9);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding: clamp(2rem, 5vw, 3rem) clamp(1rem, 4vw, 2rem) clamp(1rem, 3vw, 2rem);
            text-align: center;
        }

        .footer-content {
            max-width: 1400px;
            margin: 0 auto;
        }

        .footer-logo {
            font-size: clamp(1.5rem, 4vw, 2rem);
            font-weight: 700;
            color: #00d4ff;
            margin-bottom: clamp(0.8rem, 2vw, 1rem);
        }

        .footer-text {
            color: rgba(255, 255, 255, 0.6);
            margin-bottom: clamp(1.5rem, 4vw, 2rem);
            font-size: clamp(0.9rem, 2.5vw, 1rem);
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .social-links {
            display: flex;
            justify-content: center;
            gap: clamp(0.8rem, 2vw, 1rem);
            margin-bottom: clamp(1.5rem, 4vw, 2rem);
            flex-wrap: wrap;
        }

        .social-links a {
            color: rgba(255, 255, 255, 0.6);
            font-size: clamp(1.2rem, 3vw, 1.5rem);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            padding: 0.5rem;
        }

        .copyright {
            color: rgba(255, 255, 255, 0.4);
            font-size: clamp(0.8rem, 2vw, 0.9rem);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: clamp(1.5rem, 4vw, 2rem);
        }, 255, 0.6);
            font-size: 1.5rem;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            color: #00d4ff;
            transform: translateY(-3px);
        }

        .copyright {
            color: rgba(255, 255, 255, 0.4);
            font-size: 0.9rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 2rem;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }

            .mobile-menu {
                display: flex;
            }

            .hero h1 {
                font-size: 2.5rem;
            }

            .hero p {
                font-size: 1.1rem;
            }

            .hero-buttons {
                flex-direction: column;
                align-items: center;
            }

            .features h2 {
                font-size: 2rem;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Mouse cursor trail effect */
        .cursor-trail {
            position: fixed;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(0, 212, 255, 0.8), rgba(0, 212, 255, 0.2));
            pointer-events: none;
            z-index: 9999;
            transition: all 0.1s ease;
            animation: cursorPulse 1s ease-in-out infinite;
        }

        @keyframes cursorPulse {
            0%, 100% { transform: scale(1); opacity: 0.7; }
            50% { transform: scale(1.5); opacity: 0.3; }
        }

        /* Typing animation */
        .typing-text {
            overflow: hidden;
            border-right: 3px solid #00d4ff;
            white-space: nowrap;
            animation: typing 4s steps(40, end), blink-caret 0.75s step-end infinite;
        }

        @keyframes typing {
            from { width: 0 }
            to { width: 100% }
        }

        @keyframes blink-caret {
            from, to { border-color: transparent }
            50% { border-color: #00d4ff }
        }

        /* Scroll indicator */
        .scroll-indicator {
            position: fixed;
            top: 0;
            left: 0;
            width: 0%;
            height: 4px;
            background: linear-gradient(90deg, #00d4ff, #0099cc);
            z-index: 9999;
            transition: width 0.1s ease;
        }

        /* Wave animation for sections */
        .wave-animation {
            position: relative;
            overflow: hidden;
        }

        .wave-animation::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(0, 212, 255, 0.03), transparent);
            animation: waveMove 8s linear infinite;
            z-index: -1;
        }

        @keyframes waveMove {
            0% { transform: translateX(-100%) translateY(-100%) rotate(0deg); }
            100% { transform: translateX(100%) translateY(100%) rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="gradient-bg"></div>
    <div class="particles" id="particles">
        <div class="glow-orb"></div>
        <div class="glow-orb"></div>
        <div class="glow-orb"></div>
    </div>
    <div class="scroll-indicator" id="scrollIndicator"></div>

    <!-- Header -->
    <header>
        <nav>
<a href="#" class="logo" 
   style="text-decoration:none;">
    <img src="{{ asset('assets/images/rripthbc.png') }}" 
         alt="Logo RRI" 
         style="height:70px; width:50px; vertical-align:middle; margin-right:8px;">
    <span>SIAP BAPER RRI RANAI</span>
</a>


            <ul class="nav-links">
                <li><a href="#home">Beranda</a></li>
                <li><a href="#about">Tentang</a></li>
                <li>
    <a href="{{ route('login') }}" class="login-btn">Login</a>
</li>

            </ul>
            <div class="mobile-menu">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="hero-content">
            <h1>SIAP BAPER RRI</h1>
            <p>Sistem Informasi Pengajuan Barang Perlengkapan Radio Republik Indonesia yang modern, efisien, dan terintegrasi untuk mendukung operasional siaran berkualitas.</p>
            <div class="hero-buttons">
                <a href="{{ route('login') }}" class="btn-primary">Buat Pengajuan Barang</a>

                <a href="#about" class="btn-secondary">Pelajari Lebih Lanjut</a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features wave-animation" id="features">
        <h2 class="animate-on-scroll">Fitur Unggulan</h2>
        <div class="features-grid">
            <div class="feature-card animate-on-scroll">
                <div class="feature-icon">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <h3>Pengajuan Digital</h3>
                <p>Ajukan kebutuhan barang perlengkapan secara digital dengan proses yang mudah dan cepat. Tidak perlu lagi formulir kertas yang merepotkan.</p>
            </div>
            <div class="feature-card animate-on-scroll">
                <div class="feature-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3>Monitoring Real-time</h3>
                <p>Pantau status pengajuan Anda secara real-time dengan dashboard yang informatif dan notifikasi otomatis.</p>
            </div>
            <div class="feature-card animate-on-scroll">
                <div class="feature-icon">
                    <i class="fas fa-users-cog"></i>
                </div>
                <h3>Multi-level Approval</h3>
                <p>Sistem persetujuan bertingkat yang fleksibel sesuai dengan struktur organisasi RRI untuk memastikan akuntabilitas.</p>
            </div>
            <div class="feature-card animate-on-scroll">
                <div class="feature-icon">
                    <i class="fas fa-database"></i>
                </div>
                <h3>Inventaris Terintegrasi</h3>
                <p>Kelola inventaris barang perlengkapan dengan sistem yang terintegrasi dan laporan yang komprehensif.</p>
            </div>
            <div class="feature-card animate-on-scroll">
                <div class="feature-icon">
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <h3>Mobile Responsive</h3>
                <p>Akses sistem kapan saja dan di mana saja melalui perangkat mobile dengan tampilan yang responsif.</p>
            </div>
            <div class="feature-card animate-on-scroll">
                <div class="feature-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3>Keamanan Terjamin</h3>
                <p>Sistem keamanan berlapis dengan enkripsi data dan kontrol akses yang ketat untuk melindungi informasi sensitif.</p>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats" id="about">
        <div class="stats-container">
            <h2 class="animate-on-scroll">Mengapa Memilih SIAP BAPER RRI?</h2>
            <div class="stats-grid">
                <div class="stat-item animate-on-scroll">
                    <span class="stat-number" data-target="99">0</span>
                    <div class="stat-label">Efisiensi Proses</div>
                </div>
                <div class="stat-item animate-on-scroll">
                    <span class="stat-number" data-target="24">0</span>
                    <div class="stat-label">Akses 24/7</div>
                </div>
                <div class="stat-item animate-on-scroll">
                    <span class="stat-number" data-target="100">0</span>
                    <div class="stat-label">Digital Solution</div>
                </div>
                <div class="stat-item animate-on-scroll">
                    <span class="stat-number" data-target="50">0</span>
                    <div class="stat-label">Pengurangan Waktu</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="contact">
        <div class="footer-content">
            <div class="footer-logo">SIAP BAPER RRI</div>
            <p class="footer-text">Sistem Informasi Pengajuan Barang Perlengkapan Radio Republik Indonesia</p>
            <div class="social-links">
                <a href="#"><i class="fab fa-facebook"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-linkedin"></i></a>
            </div>
            <div class="copyright">
                <p>&copy; 2025 Radio Republik Indonesia. Semua hak dilindungi.</p>
            </div>
        </div>
    </footer>

    <script>
        // Create floating particles
        function createParticles() {
            const particlesContainer = document.getElementById('particles');
            for (let i = 0; i < 80; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.top = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 8 + 's';
                particle.style.animationDuration = (Math.random() * 5 + 5) + 's';
                particlesContainer.appendChild(particle);
            }
        }

        // Mouse cursor trail effect
        let mouseTrails = [];
        document.addEventListener('mousemove', (e) => {
            const trail = document.createElement('div');
            trail.className = 'cursor-trail';
            trail.style.left = e.clientX - 10 + 'px';
            trail.style.top = e.clientY - 10 + 'px';
            document.body.appendChild(trail);
            
            mouseTrails.push(trail);
            
            setTimeout(() => {
                trail.style.opacity = '0';
                trail.style.transform = 'scale(2)';
                setTimeout(() => {
                    if (trail.parentNode) {
                        trail.parentNode.removeChild(trail);
                    }
                    mouseTrails = mouseTrails.filter(t => t !== trail);
                }, 300);
            }, 100);
            
            // Limit number of trails
            if (mouseTrails.length > 10) {
                const oldTrail = mouseTrails.shift();
                if (oldTrail.parentNode) {
                    oldTrail.parentNode.removeChild(oldTrail);
                }
            }
        });

        // Scroll indicator
        window.addEventListener('scroll', () => {
            const header = document.querySelector('header');
            const scrollIndicator = document.getElementById('scrollIndicator');
            const windowHeight = window.innerHeight;
            const documentHeight = document.documentElement.scrollHeight - windowHeight;
            const scrolled = (window.scrollY / documentHeight) * 100;
            
            scrollIndicator.style.width = scrolled + '%';
            
            if (window.scrollY > 100) {
                header.style.background = 'rgba(10, 10, 10, 0.95)';
                header.style.transform = 'translateY(0)';
            } else {
                header.style.background = 'rgba(10, 10, 10, 0.8)';
            }
        });

        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Intersection Observer for scroll animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                        entry.target.classList.add('animated');
                    }, index * 100);
                }
            });
        }, observerOptions);

        // Observe all elements with animate-on-scroll class
        document.querySelectorAll('.animate-on-scroll').forEach(el => {
            observer.observe(el);
        });

        // Animate numbers in stats section
        function animateNumbers() {
            const numbers = document.querySelectorAll('.stat-number');
            numbers.forEach(number => {
                const target = parseInt(number.getAttribute('data-target'));
                const increment = target / 100;
                let current = 0;
                
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        current = target;
                        clearInterval(timer);
                    }
                    number.textContent = Math.floor(current) + (target === 99 || target === 100 || target === 50 ? '%' : target === 24 ? '/7' : '');
                }, 20);
            });
        }

        // Trigger number animation when stats section comes into view
        const statsObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateNumbers();
                    statsObserver.unobserve(entry.target);
                }
            });
        });

        const statsSection = document.querySelector('.stats');
        if (statsSection) {
            statsObserver.observe(statsSection);
        }

        // Header background on scroll
        window.addEventListener('scroll', () => {
            const header = document.querySelector('header');
            if (window.scrollY > 100) {
                header.style.background = 'rgba(10, 10, 10, 0.95)';
            } else {
                header.style.background = 'rgba(10, 10, 10, 0.8)';
            }
        });

        // Add parallax effect to hero section
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const parallax = document.querySelector('.hero-content');
            const speed = scrolled * 0.5;
            
            if (parallax) {
                parallax.style.transform = `translateY(${speed}px)`;
            }
        });

        // Add tilt effect to feature cards
        document.querySelectorAll('.feature-card').forEach(card => {
            card.addEventListener('mousemove', (e) => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                
                const centerX = rect.width / 2;
                const centerY = rect.height / 2;
                
                const rotateX = (y - centerY) / 10;
                const rotateY = (centerX - x) / 10;
                
                card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-15px)`;
            });
            
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) translateY(0)';
            });
        });

        // Mobile menu toggle
        const mobileMenu = document.querySelector('.mobile-menu');
        const navLinks = document.querySelector('.nav-links');

        mobileMenu.addEventListener('click', () => {
            navLinks.style.display = navLinks.style.display === 'flex' ? 'none' : 'flex';
        });

        // Initialize particles on load
        window.addEventListener('load', () => {
            createParticles();
        });

        // Add some interactive hover effects
        document.querySelectorAll('.feature-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-10px) scale(1.02)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });
    </script>
</body>
</html>