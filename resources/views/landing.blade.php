<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SILAPSO – Lapor Fasilitas Umum Desa Soso</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:wght@500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --ungu: #6b4eff;
            --ungu-soft: #ece9ff;
            --ungu-dark: #31215f;
            --abu: #6b7280;
            --putih: #ffffff;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: "Inter", sans-serif;
            background: linear-gradient(180deg, #fafaff, #f4f2ff);
            color: #1f2937;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* HEADER */
        header {
            max-width: 1200px;
            margin: auto;
            padding: 28px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            opacity: 0;
            transform: translateY(-10px);
            animation: fadeDown 0.8s ease forwards;
        }

        header h1 {
            font-family: "Playfair Display", serif;
            font-size: 28px;
            letter-spacing: 1px;
            cursor: default;
        }

        header h1 span {
            color: var(--ungu);
        }

        header p {
            font-size: 14px;
            color: var(--abu);
            position: relative;
        }

        header p::after {
            content: "";
            position: absolute;
            left: 0;
            bottom: -4px;
            width: 0;
            height: 2px;
            background: var(--ungu);
            transition: width 0.3s ease;
        }

        header p:hover::after {
            width: 100%;
        }

        /* HERO */
        .hero {
            max-width: 900px;
            margin: auto;
            padding: 100px 24px 70px;
            text-align: center;
            opacity: 0;
            transform: translateY(20px);
            animation: fadeUp 1s ease forwards;
            animation-delay: 0.2s;
        }

        .hero h2 {
            font-family: "Playfair Display", serif;
            font-size: 48px;
            line-height: 1.25;
            margin-bottom: 24px;
        }

        .hero h2 span {
            color: var(--ungu);
            position: relative;
        }

        .hero h2 span::after {
            content: "";
            position: absolute;
            left: 0;
            bottom: -6px;
            width: 100%;
            height: 6px;
            background: var(--ungu-soft);
            z-index: -1;
            border-radius: 4px;
        }

        .hero p {
            font-size: 18px;
            color: var(--abu);
            max-width: 640px;
            margin: 0 auto 44px;
            line-height: 1.7;
        }

        /* BUTTON */
        .hero button {
            position: relative;
            background: var(--ungu);
            color: var(--putih);
            border: none;
            padding: 16px 44px;
            font-size: 16px;
            border-radius: 999px;
            cursor: pointer;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 15px 30px rgba(107, 78, 255, 0.25);
        }

        .hero button::after {
            content: "→";
            margin-left: 10px;
            transition: transform 0.3s ease;
            display: inline-block;
        }

        .hero button:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(107, 78, 255, 0.35);
        }

        .hero button:hover::after {
            transform: translateX(4px);
        }

        /* INFO */
        .info {
            max-width: 900px;
            margin: auto;
            padding: 0 24px 80px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 24px;
        }

        .info-card {
            background: var(--putih);
            padding: 32px;
            border-radius: 20px;
            box-shadow: 0 18px 36px rgba(0,0,0,0.06);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            opacity: 0;
            transform: translateY(20px);
        }

        .info-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 25px 50px rgba(0,0,0,0.08);
        }

        .info-card h3 {
            font-size: 18px;
            margin-bottom: 10px;
            color: var(--ungu-dark);
        }

        .info-card p {
            font-size: 14px;
            color: var(--abu);
            line-height: 1.6;
        }

        footer {
            text-align: center;
            padding: 24px;
            font-size: 14px;
            color: #9ca3af;
        }

        /* ANIMATION */
        @keyframes fadeUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeDown {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <header>
        <h1><span>SIL</span>APSO</h1>
        <p>Sistem Lapor Fasilitas Desa Soso</p>
    </header>

    <section class="hero">
        <h2>Laporkan Fasilitas Umum<br /><span>Desa Soso</span> dengan Mudah</h2>
        <p>Platform resmi Desa Soso untuk membantu masyarakat menyampaikan laporan kerusakan fasilitas umum secara cepat, aman, dan transparan.</p>
        <button onclick="goToLapor()">Buat Laporan</button>
    </section>

    <section class="info">
        <div class="info-card">
            <h3>Mudah</h3>
            <p>Antarmuka sederhana yang dapat digunakan semua kalangan.</p>
        </div>
        <div class="info-card">
            <h3>Cepat</h3>
            <p>Laporan langsung diteruskan ke perangkat desa terkait.</p>
        </div>
        <div class="info-card">
            <h3>Terpercaya</h3>
            <p>Setiap laporan tercatat dan dapat dipantau secara digital.</p>
        </div>
    </section>

    <footer>© 2026 SILAPSO · Desa Soso</footer>

    <script>
        // reveal info cards on load (subtle, no scroll hijack)
        const cards = document.querySelectorAll('.info-card');
        cards.forEach((card, i) => {
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 400 + i * 120);
        });

        function goToLapor() {
            @auth
                window.location.href = "{{ route('reports.create') }}";
            @else
                window.location.href = "{{ route('login') }}";
            @endauth
        }
    </script>
</body>
</html>
