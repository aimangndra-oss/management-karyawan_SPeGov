<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.scss', 'resources/js/app.js'])

        <style>
            * { box-sizing: border-box; }

            .page-wrapper {
                min-height: 100vh;
                display: flex;
                flex-direction: column;
            }

            /* ===== TOP BAR ===== */
            .top-bar {
                position: relative;
                z-index: 20;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 0.75rem;
                padding: 0.85rem 1.5rem;
                background: rgba(255, 255, 255, 0.85);
                backdrop-filter: blur(10px);
                border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            }
            .top-bar img {
                height: 28px;
                width: 28px;
                object-fit: contain;
            }
            .top-bar-text {
                font-size: 0.8rem;
                font-weight: 600;
                letter-spacing: 0.03em;
                color: #1e3a8a;
                text-transform: uppercase;
            }
            .top-bar-divider {
                width: 1px;
                height: 16px;
                background: #cbd5e1;
            }
            .top-bar-sub {
                font-size: 0.75rem;
                color: #64748b;
            }

            /* ===== SPLIT WRAPPER ===== */
            .split-wrapper {
                position: relative;
                flex: 1;
                display: flex;
                flex-direction: column;
                overflow: hidden;
            }
            @media (min-width: 1024px) {
                .split-wrapper { flex-direction: row; }
            }

            .panel-left,
            .panel-right {
                position: relative;
                width: 100%;
                min-height: 42vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 2.5rem 1.5rem;
                overflow: hidden;
            }
            @media (min-width: 1024px) {
                .panel-left {
                    width: 42%;
                    min-height: auto;
                    padding: 3rem 2rem;
                }
                .panel-right {
                    width: 58%;
                    min-height: auto;
                    padding: 3rem 2rem;
                }
            }

            .panel-left {
                background: linear-gradient(160deg, #ffffff 0%, #f5f8fc 45%, #eef3fa 75%, #e6edf9 100%);
            }

            /* ===== PANEL KANAN ===== */
            .panel-right {
                background: linear-gradient(120deg, #0b2a6b, #123f9e, #1a56d6, #22d3ee, #123f9e, #0b2a6b);
                background-size: 300% 300%;
                animation: bgFlowBlue 12s ease-in-out infinite;
            }

            @keyframes bgFlowBlue {
                0% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
                100% { background-position: 0% 50%; }
            }

            /* Logo penuh, blur berat, opacity berdenyut mengikuti animasi background */
            .logo-hidden {
                position: absolute;
                top: 50%;
                left: 50%;
                width: 85%;
                max-width: 620px;
                transform: translate(-50%, -50%);
                filter: brightness(0) invert(1) blur(48px);
                pointer-events: none;
                z-index: 1;
                animation: logoPulse 12s ease-in-out infinite;
            }

            @keyframes logoPulse {
                0%   { opacity: 0.10; }
                25%  { opacity: 0.32; }
                50%  { opacity: 0.14; }
                75%  { opacity: 0.30; }
                100% { opacity: 0.10; }
            }

            /* Dot grid di atas layer biru */
            .panel-right::before {
                content: "";
                position: absolute;
                inset: 0;
                z-index: 3;
                background-image: radial-gradient(rgba(255, 255, 255, 0.16) 1px, transparent 1px);
                background-size: 26px 26px;
                mask-image: radial-gradient(ellipse 80% 60% at 50% 40%, black 40%, transparent 100%);
                -webkit-mask-image: radial-gradient(ellipse 80% 60% at 50% 40%, black 40%, transparent 100%);
                pointer-events: none;
            }

            .blob {
                position: absolute;
                z-index: 4;
                border-radius: 9999px;
                filter: blur(60px);
                pointer-events: none;
                animation: blobFloat 12s ease-in-out infinite;
            }
            .blob-1 { right: 20%; top: 10%; width: 9rem; height: 9rem; background: rgba(103, 232, 249, 0.2); animation-delay: 0s; }
            .blob-2 { left: 15%; bottom: 15%; width: 11rem; height: 11rem; background: rgba(56, 189, 248, 0.18); animation-delay: 3s; }
            .blob-3 { left: 35%; top: 30%; width: 12rem; height: 12rem; background: rgba(99, 102, 241, 0.16); animation-delay: 6s; }

            @keyframes blobFloat {
                0%, 100% { transform: translate(0, 0); }
                33% { transform: translate(10px, -18px); }
                66% { transform: translate(-12px, 14px); }
            }

            /* Form card: DIPERBESAR, lebih besar dari kotak kiri */
            .form-card {
                position: relative;
                z-index: 10;
                width: 100%;
                max-width: 34rem; /* lebih besar dari max-w-md bawaan (28rem) */
            }
            .form-card-inner {
                padding: 2.75rem 3rem;
            }

            /* ===== KIRI: kotak logo blur ===== */
            .logo-blur-box {
                position: relative;
                z-index: 10;
                width: 100%;
                max-width: 26rem; /* dibuat sedikit lebih kecil dari form card kanan */
                aspect-ratio: 1 / 1;
                border-radius: 2.5rem;
                border: 1px solid #e2e8f0;
                background: rgba(255, 255, 255, 0.6);
                box-shadow: 0 20px 40px -10px rgba(100, 116, 139, 0.25);
                overflow: hidden;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            @media (min-width: 1024px) {
                .logo-blur-box { height: 460px; aspect-ratio: auto; }
            }

            .logo-blur-bg {
                position: absolute;
                width: 80%;
                max-width: none;
                opacity: 0.3;
                filter: blur(30px);
                pointer-events: none;
                user-select: none;
                animation: logoGlowPulse 10s ease-in-out infinite;
            }

            @keyframes logoGlowPulse {
                0%, 100% { transform: scale(1) rotate(0deg); opacity: 0.24; }
                50% { transform: scale(1.08) rotate(3deg); opacity: 0.38; }
            }

            .badge-float { animation: badgeFloat 4s ease-in-out infinite; }
            @keyframes badgeFloat {
                0%, 100% { transform: translateY(0); }
                50% { transform: translateY(-10px); }
            }

            .ping-slow { animation: pingSlow 2.5s cubic-bezier(0, 0, 0.2, 1) infinite; }
            @keyframes pingSlow {
                0% { transform: scale(1); opacity: 0.6; }
                100% { transform: scale(1.8); opacity: 0; }
            }

            .fade-in-up { animation: fadeInUp 0.7s ease-out both; }
            @keyframes fadeInUp {
                from { opacity: 0; transform: translateY(24px); }
                to { opacity: 1; transform: translateY(0); }
            }

            .accent-bar {
                background: linear-gradient(90deg, #1d4ed8 0%, #22d3ee 50%, #1d4ed8 100%);
            }

            /* ===== FOOTER BAR ===== */
            .footer-bar {
                position: relative;
                z-index: 20;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                gap: 0.6rem;
                padding: 1rem 1.5rem 1.15rem;
                background: rgba(255, 255, 255, 0.85);
                backdrop-filter: blur(10px);
                border-top: 1px solid rgba(226, 232, 240, 0.8);
            }
            .footer-icons {
                display: flex;
                align-items: center;
                gap: 1.75rem;
                flex-wrap: wrap;
                justify-content: center;
            }
            .footer-icon {
                display: flex;
                align-items: center;
                gap: 0.4rem;
                font-size: 0.72rem;
                color: #64748b;
                font-weight: 500;
            }
            .footer-icon svg {
                width: 15px;
                height: 15px;
                color: #2563eb;
                flex-shrink: 0;
            }
            .footer-copyright {
                font-size: 0.72rem;
                color: #94a3b8;
                text-align: center;
            }
            .footer-divider {
                width: 100%;
                max-width: 220px;
                height: 1px;
                background: linear-gradient(90deg, transparent, #cbd5e1, transparent);
                margin: 0.1rem 0;
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="page-wrapper">

            <!-- ===================== TOP BAR ===================== -->
            <div class="top-bar">
                <img src="/images/logo.png" alt="Logo">
                <span class="top-bar-text">Dinas Komunikasi Dan Informatika</span>
                <span class="top-bar-divider"></span>
                <span class="top-bar-sub"> Bidang Statistika Persandian &amp; E-Government</span>
            </div>

            <div class="split-wrapper">

                <!-- ===================== KIRI: BOX LOGO BLUR ===================== -->
                <div class="panel-left">
                    <div class="logo-blur-box">
                        <img src="/images/logo.png" alt="" aria-hidden="true" class="logo-blur-bg">

                        <div class="relative z-10 flex flex-col items-center text-center px-8">
                            <div class="relative mb-6 badge-float">
                                <span class="absolute inset-0 rounded-full bg-blue-400/30 ping-slow"></span>
                                <div class="relative flex h-20 w-20 items-center justify-center rounded-full bg-white shadow-xl shadow-slate-400/40 ring-4 ring-white/60 p-3">
                                    <img src="/images/logo.png" alt="Logo" class="h-full w-full object-contain">
                                </div>
                            </div>

                            <span class="inline-block px-3 py-1 mb-3 text-xs font-semibold tracking-wider uppercase text-blue-00 bg-blue-100 rounded-full border border-blue-200">
                                Bidang Statistika Persandian Dan E-Government
                            </span>
                            <h1 class="text-xl sm:text-2xl font-bold text-slate-800 tracking-tight">
                                Sistem Management
                            </h1>
                            <h1 class="text-xl sm:text-2xl font-bold text-slate-800 tracking-tight">
                                Dinas Komunikasi Dan Informatika
                            </h1>
                            <h1 class="text-xl sm:text-2xl font-bold text-slate-800 tracking-tight">
                                Bidang Statistika Persandian Dan E-Government
                            </h1>

                            <div class="relative mt-6 badge-float" style="animation-delay: 2s;">
                                <span class="absolute inset-0 rounded-full bg-emerald-400/30 ping-slow"></span>
                                <div class="relative flex h-14 w-14 items-center justify-center rounded-full bg-white shadow-lg shadow-slate-400/40 ring-4 ring-white/60">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-emerald-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 2l8 4v6c0 5-3.5 8.5-8 10-4.5-1.5-8-5-8-10V6l8-4z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ===================== KANAN: BIRU + LOGO BLUR PENUH + FORM BESAR ===================== -->
                <div class="panel-right">
                    <img src="/images/logo.png" alt="" aria-hidden="true" class="logo-hidden">

                    <div class="blob blob-1"></div>
                    <div class="blob blob-2"></div>
                    <div class="blob blob-3"></div>

                    <div class="form-card bg-white/95 shadow-2xl shadow-slate-950/30 backdrop-blur-xl rounded-3xl border border-white/40 overflow-hidden fade-in-up">
                        <div class="h-1.5 accent-bar"></div>
                        <div class="form-card-inner">
                            {{ $slot }}
                        </div>
                    </div>
                </div>


            </div>

            <!-- ===================== FOOTER BAR ===================== -->
            <div class="footer-bar">
                <div class="footer-divider"></div>
                <p class="footer-copyright">
                    &copy; {{ date('Y') }} Pemerintah Kabupaten Cirebon &mdash; Dinas Komunikasi dan Informatika &mdash; Bidang Statistika Persandian dan E-Government.
                </p>
            </div>
        </div>
    </body>
</html>