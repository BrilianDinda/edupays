<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Edupays') }}</title>
        <style>
            :root {
                color-scheme: light;
                --bg: #f4efe6;
                --panel: rgba(255, 255, 255, 0.82);
                --panel-border: rgba(61, 44, 24, 0.12);
                --text: #1f1a17;
                --muted: #66584c;
                --accent: #b45f06;
                --accent-strong: #7a3f00;
                --shadow: 0 28px 80px rgba(89, 55, 18, 0.18);
            }

            * {
                box-sizing: border-box;
            }

            body {
                margin: 0;
                min-height: 100vh;
                font-family: Georgia, 'Times New Roman', serif;
                color: var(--text);
                background:
                    radial-gradient(circle at top left, rgba(255, 214, 153, 0.85), transparent 34%),
                    radial-gradient(circle at 85% 15%, rgba(234, 177, 104, 0.5), transparent 22%),
                    linear-gradient(135deg, #f8f1e8 0%, #efe5d4 45%, #e8dcc8 100%);
                display: grid;
                place-items: center;
                padding: 24px;
            }

            .shell {
                width: min(1080px, 100%);
                display: grid;
                grid-template-columns: 1.1fr 0.9fr;
                gap: 24px;
                align-items: stretch;
            }

            .hero,
            .card {
                background: var(--panel);
                border: 1px solid var(--panel-border);
                box-shadow: var(--shadow);
                backdrop-filter: blur(14px);
                border-radius: 28px;
            }

            .hero {
                padding: clamp(28px, 4vw, 48px);
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                min-height: 560px;
            }

            .eyebrow {
                display: inline-flex;
                align-items: center;
                gap: 10px;
                font-size: 0.88rem;
                letter-spacing: 0.16em;
                text-transform: uppercase;
                color: var(--accent-strong);
                margin-bottom: 18px;
            }

            .eyebrow::before {
                content: '';
                width: 38px;
                height: 2px;
                background: linear-gradient(90deg, var(--accent), transparent);
                border-radius: 999px;
            }

            h1 {
                margin: 0;
                font-size: clamp(2.6rem, 6vw, 5.2rem);
                line-height: 0.95;
                letter-spacing: -0.05em;
                max-width: 9ch;
            }

            .lead {
                margin: 20px 0 0;
                max-width: 58ch;
                font-size: clamp(1rem, 1.8vw, 1.15rem);
                line-height: 1.8;
                color: var(--muted);
            }

            .actions {
                display: flex;
                flex-wrap: wrap;
                gap: 12px;
                margin-top: 28px;
            }

            .button {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                min-height: 48px;
                padding: 0 20px;
                border-radius: 999px;
                text-decoration: none;
                font-weight: 700;
                letter-spacing: 0.01em;
                transition: transform 160ms ease, box-shadow 160ms ease, background 160ms ease;
            }

            .button:hover {
                transform: translateY(-1px);
            }

            .button-primary {
                color: white;
                background: linear-gradient(135deg, var(--accent), var(--accent-strong));
                box-shadow: 0 12px 24px rgba(122, 63, 0, 0.24);
            }

            .button-secondary {
                color: var(--text);
                background: rgba(255, 255, 255, 0.68);
                border: 1px solid rgba(61, 44, 24, 0.12);
            }

            .stats {
                display: grid;
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 14px;
                margin-top: 28px;
            }

            .stat {
                padding: 16px;
                border-radius: 20px;
                background: rgba(255, 255, 255, 0.68);
                border: 1px solid rgba(61, 44, 24, 0.08);
            }

            .stat strong {
                display: block;
                font-size: 1.4rem;
                margin-bottom: 6px;
            }

            .card {
                padding: clamp(24px, 3vw, 34px);
                display: grid;
                gap: 18px;
                align-content: start;
            }

            .card h2 {
                margin: 0;
                font-size: 1.2rem;
                letter-spacing: -0.02em;
            }

            .card p {
                margin: 0;
                color: var(--muted);
                line-height: 1.7;
            }

            .list {
                display: grid;
                gap: 12px;
                margin: 0;
                padding: 0;
                list-style: none;
            }

            .list li {
                padding: 14px 16px;
                border-radius: 18px;
                background: rgba(255, 255, 255, 0.72);
                border: 1px solid rgba(61, 44, 24, 0.08);
                line-height: 1.55;
            }

            .footer {
                margin-top: auto;
                padding-top: 20px;
                color: var(--muted);
                font-size: 0.95rem;
            }

            @media (max-width: 900px) {
                .shell {
                    grid-template-columns: 1fr;
                }

                .hero {
                    min-height: auto;
                }
            }

            @media (max-width: 640px) {
                body {
                    padding: 14px;
                }

                .stats {
                    grid-template-columns: 1fr;
                }

                h1 {
                    max-width: none;
                }
            }
        </style>
    </head>
    <body>
        <main class="shell">
            <section class="hero">
                <div>
                    <div class="eyebrow">Edupays</div>
                    <h1>Siap dipakai di hosting.</h1>
                    <p class="lead">
                        Aplikasi ini sudah disiapkan untuk deployment ke Domainesia dengan konfigurasi production,
                        database MySQL, dan halaman depan yang tidak bergantung pada Vite atau Tailwind build.
                    </p>
                    <div class="actions">
                        @if (Route::has('login'))
                            <a class="button button-primary" href="{{ route('login') }}">Masuk</a>
                        @endif
                        @if (Route::has('register'))
                            <a class="button button-secondary" href="{{ route('register') }}">Daftar</a>
                        @endif
                    </div>
                </div>

                <div class="stats">
                    <div class="stat">
                        <strong>PHP 8.2+</strong>
                        <span>Kompatibel untuk hosting modern.</span>
                    </div>
                    <div class="stat">
                        <strong>MySQL</strong>
                        <span>Siap pakai untuk database production.</span>
                    </div>
                    <div class="stat">
                        <strong>Shared Hosting</strong>
                        <span>Aman untuk `public_html` atau document root.</span>
                    </div>
                </div>

                <div class="footer">
                    Pastikan `APP_URL`, kredensial database, dan permission storage sudah benar sebelum online.
                </div>
            </section>

            <aside class="card">
                <h2>Langkah deploy singkat</h2>
                <p>
                    Jalankan build asset di lokal jika masih dibutuhkan, upload project, lalu aktifkan cache Laravel di server.
                </p>
                <ul class="list">
                    <li>Isi `.env` production dengan domain dan database Domainesia.</li>
                    <li>Jalankan `composer install --no-dev` atau upload folder `vendor`.</li>
                    <li>Jalankan `php artisan migrate --force` dan `php artisan storage:link`.</li>
                    <li>Set permission untuk `storage` dan `bootstrap/cache`.</li>
                    <li>Jika memakai `public_html`, pastikan path `index.php` mengarah ke folder project.</li>
                </ul>
            </aside>
        </main>
    </body>
</html>
