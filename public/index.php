<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Database\Connection;
use App\Models\Track;
use App\Repositories\TrackRepository;

$dbConnection = new Connection();
$db = $dbConnection->connect();
$trackRepo = new TrackRepository($db);

$tracks = $trackRepo->findAll();


$siteUrl = 'https://hlinkinn.com';

$structuredData = [
    '@context' => 'https://schema.org',
    '@type'    => 'MusicGroup',
    'name'     => 'hlinkinn',
    'url'      => $siteUrl,
    'sameAs'   => [
        'https://instagram.com/hlinkinn',
        'https://www.youtube.com/@hlinkin808',
        'https://www.beatstars.com/hlinkingpin',
    ],
];

?>

<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/howler/2.2.4/howler.min.js"></script>

    <!-- Základné SEO značky -->
    <title>hlinkinn — Music Producer &amp; Beatmaker</title>
    <meta name="description" content="Music producer and beatmaker hlinkinn. Listen to original tracks and beats, filter them by BPM, and get in touch for licensing or collaboration.">
    <link rel="canonical" href="<?= htmlspecialchars($siteUrl) ?>/">
    <link rel="icon" type="image/png" href="../assets/icons/listen_icon.png">

    <!-- Open Graph — náhľad pri zdieľaní na Facebooku, Instagrame, Discorde… -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="hlinkinn — Music Producer &amp; Beatmaker">
    <meta property="og:description" content="Listen to original tracks and beats by hlinkinn. Filter by BPM and reach out for licensing or collaboration.">
    <meta property="og:url" content="<?= htmlspecialchars($siteUrl) ?>/">
    <!-- Náhľadový obrázok musí byť absolútna, verejne dostupná URL (ideálne 1200×630). -->
    <meta property="og:image" content="<?= htmlspecialchars($siteUrl) ?>/assets/og-image.png">

    <!-- Twitter / X karta -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="hlinkinn — Music Producer &amp; Beatmaker">
    <meta name="twitter:description" content="Listen to original tracks and beats by hlinkinn. Filter by BPM and reach out for licensing or collaboration.">
    <meta name="twitter:image" content="<?= htmlspecialchars($siteUrl) ?>/assets/og-image.png">

    <!-- Štruktúrované dáta (JSON-LD) — zostavené z $structuredData (pozri vrch súboru) -->
    <script type="application/ld+json">
        <?= json_encode($structuredData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?>
    </script>

    <link rel="preconnect" href="https://api.fontshare.com">
    <link rel="stylesheet" href="css/output.css">
    <link rel="stylesheet" href="https://api.fontshare.com/v2/css?f[]=slussen@300,400,500,700&display=swap">
    <style>
        body,
        html {
            font-family: 'Slussen', sans-serif;
        }

        @font-face {
            font-family: 'OCR-A';
            src: url('../assets/fonts/OCR-a___.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        .ocr-a {
            font-family: 'OCR-A', monospace;
        }

        /* Hide the up/down spinner arrows on number inputs */
        .no-spinner::-webkit-inner-spin-button,
        .no-spinner::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .no-spinner {
            -moz-appearance: textfield;
            appearance: textfield;
        }

        /* Placeholder uses the page font (Slussen), not the mono of typed values */
        .no-spinner::placeholder {
            font-family: 'Slussen', sans-serif;
            font-weight: 300;
        }

        #main-nav {
            transition: transform 0.5s ease;
        }

        #main-nav.nav-hidden {
            transform: translateY(calc(-100% - 40px));
            pointer-events: none;
        }

        #menu-toggle {
            position: relative;
            isolation: isolate;
        }

        #menu-toggle::before {
            content: '';
            position: absolute;
            inset: -18px -28px;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            background: rgba(255, 255, 255, 0.03);
            border-radius: 14px;
            mask-image: radial-gradient(ellipse at center, black 25%, transparent 72%);
            -webkit-mask-image: radial-gradient(ellipse at center, black 25%, transparent 72%);
            z-index: -1;
            opacity: 1;
            transition: opacity 0.3s ease;
        }

        /* Pri skrytí navbaru úplne vyhasne aj blur za hamburgerom, aby nepresakoval */
        #main-nav.nav-hidden #menu-toggle::before {
            opacity: 0;
        }

        #icon-menu,
        #icon-close {
            transition: opacity 0.4s ease, transform 0.4s ease;
        }

        #icon-menu {
            opacity: 0.5;
            transform: rotate(0deg) scale(1);
        }

        #icon-menu.menu-active {
            opacity: 0;
            transform: rotate(90deg) scale(0.5);
        }

        #menu-toggle:hover #icon-menu:not(.menu-active) {
            opacity: 1;
        }

        #icon-close {
            position: absolute;
            opacity: 0;
            transform: rotate(-90deg) scale(0.5);
        }

        #icon-close.menu-active {
            opacity: 1;
            transform: rotate(0deg) scale(1);
        }

        #nav-links {
            position: fixed;
            top: 2.5rem;
            left: 5rem;
            height: 2.75rem;
            display: flex;
            align-items: center;
            gap: 1.3rem;
            z-index: 50;
            isolation: isolate;
            transition: transform 0.5s ease;
            pointer-events: none;
        }

        #nav-links a {
            font-size: 18px;
            opacity: 0;
            transition: color 0.3s ease, opacity 0.35s ease;
        }

        #nav-links.open a {
            opacity: 1;
        }

        #nav-links a:hover {
            color: rgba(255, 255, 255, 0.5);
        }

        #nav-links::before {
            content: '';
            position: absolute;
            inset: -50px -80px;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.28s ease;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            background: rgba(255, 255, 255, 0.03);
            border-radius: 20px;
            /* Two crossed linear masks: solid across the full width (all three links),
               fading softly only near the horizontal ends and the top/bottom edges. */
            mask-image: linear-gradient(to right, transparent 0%, black 18%, black 82%, transparent 100%),
                linear-gradient(to bottom, transparent 0%, black 30%, black 70%, transparent 100%);
            mask-composite: intersect;
            -webkit-mask-image: linear-gradient(to right, transparent 0%, black 18%, black 82%, transparent 100%),
                linear-gradient(to bottom, transparent 0%, black 30%, black 70%, transparent 100%);
            -webkit-mask-composite: source-in;
            z-index: -1;
        }

        #nav-links.open::before {
            opacity: 1;
        }

        #nav-links.open {
            pointer-events: auto;
        }

        #nav-links.nav-hidden {
            transform: translateY(calc(-100% - 40px));
        }

        /* Desktop — linky vycentrované v strede obrazovky (mobil ostáva pri ľavom okraji) */
        @media (min-width: 768px) {
            #nav-links {
                left: 50%;
                transform: translateX(-50%);
            }

            #nav-links.nav-hidden {
                transform: translateX(-50%) translateY(calc(-100% - 40px));
            }
        }

        @keyframes glowPulse {

            0%,
            100% {
                opacity: 0.3;
            }

            50% {
                opacity: 0.65;
            }
        }

        /* Glow pulzuje; animácia musí byť v CSS (nie inline), aby ju hover pauza vedela prebiť */
        .hero-glow {
            animation: glowPulse 3s ease-in-out infinite;
        }

        /* Pri hoveri na CTA slúchadlá sa pulzovanie glow zastaví */
        #hero-cta:hover .hero-glow {
            animation-play-state: paused;
        }
    </style>
</head>

<body class="bg-[#050505] overflow-x-hidden">

    <!-- Navbar -->
    <nav id="main-nav" class="fixed top-10 z-50" style="left: 1.5rem;">

        <!-- Hamburger ikona -->
        <button id="menu-toggle" class="group h-11 w-11 flex items-center justify-center">
            <!-- Wrapper — obe ikony naskladané na seba -->
            <div class="relative" style="width:22px;height:14px;">
                <svg id="icon-menu" width="22" height="10" viewBox="-1 0 18 8" fill="none" style="position:absolute;top:2px;left:-1px;">
                    <line x1="-1" y1="1" x2="17" y2="1" stroke="white" stroke-width="1.5" stroke-linecap="round" />
                    <line x1="-1" y1="7" x2="17" y2="7" stroke="white" stroke-width="1.5" stroke-linecap="round" />
                </svg>
                <svg id="icon-close" width="14" height="14" viewBox="0 0 14 14" fill="none" style="top:0;left:1px;">
                    <line x1="1" y1="1" x2="13" y2="13" stroke="white" stroke-width="1.5" stroke-linecap="round" />
                    <line x1="13" y1="1" x2="1" y2="13" stroke="white" stroke-width="1.5" stroke-linecap="round" />
                </svg>
            </div>
        </button>

    </nav>

    <!-- Nav linky — samostatný nav, aby position:fixed nespadlo pod transform rodiča -->
    <nav id="nav-links" aria-label="Section navigation">
        <a href="#tracks" class="font-thin leading-none text-white/90 whitespace-nowrap">tracks</a>
        <a href="#about" class="font-thin leading-none text-white/90 whitespace-nowrap">about</a>
        <a href="#contact" class="font-thin leading-none text-white/90 whitespace-nowrap">contact</a>
    </nav>

    <main>
        <section id="hero" class="relative" style="height:100vh;overflow:visible;">

            <!-- Obsah hero sekcie -->
            <div class="relative flex flex-col items-center justify-center px-6 sm:px-10" style="height:100%;padding-bottom:80px;">
                <!-- Meno + label ako skupina. translate-y posunie skupinu nižšie BEZ reflowu,
                     takže CTA ani waveform sa nehnú. Logo je absolútne (mimo toku), aby jeho
                     veľkosť netlačila CTA nadol. -->
                <div class="relative flex flex-col items-center translate-y-12 sm:translate-y-12">
                    <h1 class="text-[3.5rem] sm:text-[7rem] lg:text-[9rem] font-thin text-white leading-none tracking-tight mt-48 sm:mt-32">hlinkinn</h1>

                    <!-- Label pod menom — absolútne pozicované; mt-* určuje medzeru od textu hlinkinn -->
                    <img src="../assets/sky-studio-logo.png" alt="Sky Studio"
                        class="absolute top-full left-1/2 -translate-x-1/2 mt-8 w-64 sm:w-80 opacity-70 select-none" />
                </div>

                <!-- Slúchadlá ako CTA — preklik na sekciu tracks. Glow pulzuje, pri hoveri sa zastaví. -->
                <a id="hero-cta" href="#tracks" aria-label="Listen to the tracks"
                    class="group relative mt-[18rem] sm:mt-80 inline-block">
                    <div class="hero-glow pointer-events-none absolute z-0" style="width:290px;height:200px;top:50%;left:50%;transform:translate(-50%,-50%);background:radial-gradient(circle,#a83030 0%,#631f1e 35%,transparent 70%);filter:blur(50px);"></div>
                    <img src="../assets/icons/listen_icon.png" alt="Headphones"
                        class="relative z-20 w-8 sm:w-10 select-none transition-transform duration-300 group-hover:scale-[1.04]" />
                </a>
            </div>

            <!-- Vlnová animácia — na spodku hero sekcie -->
            <div class="absolute bottom-0 left-0 w-full z-10 pointer-events-none overflow-x-clip">
                <svg id="waveform-svg" viewBox="0 -115 1280 130" preserveAspectRatio="xMinYMid meet" fill="none" xmlns="http://www.w3.org/2000/svg" style="width:100%;display:block;">

                    <style>
                        #waveform-svg {
                            height: 450px;
                        }

                        @media (max-width: 767px) {
                            #waveform-svg {
                                height: 320px;
                                transform: translateY(170px) scale(2.0);
                                transform-origin: center bottom;
                            }
                        }
                    </style>

                    <!-- teal 1 — statická -->
                    <path opacity="0.8" d="M1280 78.5C1280 78.5 1071.33 78.5 934.01 78.5C702.238 78.5 706.44 127 629.424 127C552.409 127 537.151 55.5 436.886 55.5C336.621 55.5 198.547 55.5 198.547 55.5L0 55.5" stroke="#2a5a66" stroke-width="2" />

                    <!-- teal 2 — statická -->
                    <path opacity="0.8" d="M-1 23.8557C-1 23.8557 167.675 23 309.945 23C452.215 23 459.582 128 557.738 128C655.893 128 706.003 43.0005 851.395 43.0005C812.022 43.0005 1279 43 1279 43" stroke="#2a5a66" stroke-width="2" />

                    <!-- karmínová — animovaná JavaScriptom -->
                    <path id="waveform-2" d="M -1,61.3725 C 31.025,61.3725 31.025,34.09 63.05,34.09 C 255.2,34.09 255.2,87.59 447.35,87.59 C 639.5,87.59 639.5,83.17 831.65,83.17 C 1023.8,83.17 1023.8,30.6 1215.95,30.6 C 1247.975,30.6 1247.975,61.3725 1280,61.3725" stroke="url(#waveGradient)" stroke-width="2" />

                    <defs>
                        <linearGradient id="waveGradient" x1="-101.166" y1="51.3104" x2="1264.39" y2="51.3104" gradientUnits="userSpaceOnUse">
                            <stop offset="0.019" stop-color="#2a5a66" stop-opacity="0" />
                            <stop offset="0.183" stop-color="#2a5a66" stop-opacity="0" />
                            <stop offset="0.485" stop-color="#631f1e" />
                            <stop offset="0.587" stop-color="#7a2520" />
                            <stop offset="0.708" stop-color="#2a5a66" />
                            <stop offset="0.997" stop-color="#2a5a66" stop-opacity="0" />
                        </linearGradient>
                    </defs>

                </svg>
            </div>

            <script>
                const waveEl = document.getElementById('waveform-2');
                const leftStaticX = -1;
                const rightStaticX = 1280;
                const animationSpan = rightStaticX - leftStaticX;
                const animateStartX = leftStaticX + animationSpan * 0.05;
                const animateEndX = rightStaticX - animationSpan * 0.05;

                const waveCfg = {
                    frequency: 0.8,
                    amplitude: 36,
                    segments: 3,
                    speed: 0.006,
                    baseY: 61.3725
                };
                let progress = 0;

                function generateWavePoints(cfg, prog) {
                    const points = [];
                    const interval = (animateEndX - animateStartX) / cfg.segments;
                    points.push({
                        x: leftStaticX,
                        y: 0
                    });
                    for (let i = 0; i <= cfg.segments; i++) {
                        const x = animateStartX + i * interval;
                        const y = cfg.amplitude * Math.sin((i / cfg.segments) * Math.PI * 2 * cfg.frequency + prog);
                        points.push({
                            x,
                            y
                        });
                    }
                    points.push({
                        x: rightStaticX,
                        y: 0
                    });
                    return points;
                }

                function updateWavePath(el, points, baseY) {
                    let path = `M ${points[0].x},${baseY + points[0].y} `;
                    for (let i = 1; i < points.length; i++) {
                        const prev = points[i - 1];
                        const curr = points[i];
                        const cx = (prev.x + curr.x) / 2;
                        path += `C ${cx},${baseY + prev.y} ${cx},${baseY + curr.y} ${curr.x},${baseY + curr.y} `;
                    }
                    el.setAttribute('d', path);
                }

                (function loop() {
                    progress += waveCfg.speed;
                    const points = generateWavePoints(waveCfg, progress);
                    updateWavePath(waveEl, points, waveCfg.baseY);
                    requestAnimationFrame(loop);
                })();
            </script>

        </section>



        <section id="tracks" class="max-w-6xl mx-auto px-6 sm:px-10 mt-32 lg:mt-64">

            <h2 class="text-[2.75rem] font-thin text-white mb-14 sm:mb-10">tracks</h2>

            <!-- BPM filter — client-side, no reload (filters the already-rendered rows) -->
            <!-- Glassmorphism karta — rovnaký efekt ako contact formulár (len BPM + inputy) -->
            <div class="sm:w-fit sm:mx-auto rounded-2xl border border-white/[0.08] bg-white/[0.06] backdrop-blur-[40px] px-6 sm:px-10 py-4">
                <div class="flex flex-nowrap items-center justify-center gap-2.5">
                    <span class="text-[11px] font-bold uppercase tracking-[0.2em] text-white/35">BPM</span>
                    <input id="bpm-min" type="number" min="0" inputmode="numeric" placeholder="from"
                        class="no-spinner w-20 rounded-xl border border-white/[0.06] bg-white/[0.03] px-3 py-2.5 font-mono text-[14px] text-white/80 placeholder-white/15 outline-none transition-colors duration-300 focus:border-white/25" />
                    <span class="text-white/25">–</span>
                    <input id="bpm-max" type="number" min="0" inputmode="numeric" placeholder="to"
                        class="no-spinner w-20 rounded-xl border border-white/[0.06] bg-white/[0.03] px-3 py-2.5 font-mono text-[14px] text-white/80 placeholder-white/15 outline-none transition-colors duration-300 focus:border-white/25" />
                </div>
            </div>

            <!-- Reset — mimo glassmorphism boxu, vycentrovaný pod ním -->
            <div class="mb-10 sm:mb-20 mt-4 flex justify-center">
                <button id="bpm-reset" type="button"
                    class="rounded-xl border border-white/[0.06] px-3 py-2.5 text-[11px] font-bold uppercase tracking-[0.2em] text-white/35 transition-colors duration-300 hover:border-white/25 hover:text-white/70">
                    Reset
                </button>
            </div>

            <div class="flex flex-col gap-3">
                <?php foreach ($tracks as $track): ?>
                    <div class="track-row group flex items-center gap-4 sm:gap-5 rounded-2xl border border-white/[0.06] bg-white/[0.03] px-4 py-4 sm:px-6 sm:py-5 transition-all duration-300 hover:bg-white/[0.05]"
                        data-bpm="<?= (int)$track->bpm ?>"
                        data-src="../<?= htmlspecialchars($track->file_path) ?>"
                        data-title="<?= htmlspecialchars($track->title) ?>"
                        data-genre="<?= htmlspecialchars($track->genre) ?>">

                        <!-- Play ikona — príprava na budúci prehrávač -->
                        <div class=" flex h-11 w-11 shrink-0 items-center justify-center rounded-full border border-white/[0.12] bg-white/[0.1] transition-colors duration-300 group-hover:border-white/25 group-hover:bg-white/[0.16]">
                            <svg width="12" height="14" viewBox="-1 -1 13 15" style="transform: translateX(1.5px);" class="opacity-70 transition-opacity duration-300 group-hover:opacity-100">
                                <path d="M0 0.8L11 6.5L0 12.2V0.8Z" fill="white" stroke="white" stroke-width="1.4" stroke-linejoin="round" />
                            </svg>
                        </div>

                        <!-- Názov + žáner -->
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-[16px] font-medium text-white/90">
                                <?= htmlspecialchars($track->title) ?>
                            </p>
                            <p class="mt-1 text-[10px] font-bold uppercase tracking-[0.2em] text-white/35">
                                <?= htmlspecialchars($track->genre) ?>
                            </p>
                        </div>

                        <!-- BPM + dĺžka — na mobile pod sebou (BPM hore), na desktope vedľa seba -->
                        <div class="flex shrink-0 flex-col items-end gap-1 sm:flex-row sm:items-center sm:gap-5">

                            <!-- BPM -->
                            <span class="font-mono text-[13px] text-white/45 transition-colors duration-300 group-hover:text-white/70">
                                <?= htmlspecialchars($track->bpm) ?> BPM
                            </span>

                            <!-- Dĺžka — doplní JavaScript z audio metadát -->
                            <div class="flex w-14 items-center justify-end gap-1.5 text-white/40">
                                <svg class="h-3.5 w-3.5 text-white/20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10" />
                                    <polyline points="12 6 12 12 16 14" />
                                </svg>
                                <span class="track-duration font-mono text-[12px] tabular-nums" data-src="../<?= htmlspecialchars($track->file_path) ?>">--:--</span>
                            </div>
                        </div>

                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Shown when no track matches the selected range -->
            <p id="tracks-empty" class="mt-12 hidden text-center text-[15px] text-white/50">
                No tracks in this BPM range.
            </p>

            <script>
                // BPM filter — client-side. Shows/hides the already-rendered rows
                // based on the range from two number inputs. No reload, no DB query.
                (function() {
                    const minInput = document.getElementById('bpm-min');
                    const maxInput = document.getElementById('bpm-max');
                    const resetBtn = document.getElementById('bpm-reset');
                    const rows = document.querySelectorAll('.track-row');
                    const emptyMsg = document.getElementById('tracks-empty');

                    function applyFilter() {
                        // Empty field = no bound (NaN → 0 lower, Infinity upper).
                        const min = Number.isNaN(minInput.valueAsNumber) ? 0 : minInput.valueAsNumber;
                        const max = Number.isNaN(maxInput.valueAsNumber) ? Infinity : maxInput.valueAsNumber;

                        let visible = 0;
                        rows.forEach(row => {
                            const bpm = Number(row.dataset.bpm);
                            const inRange = bpm >= min && bpm <= max;
                            row.classList.toggle('hidden', !inRange);
                            if (inRange) visible++;
                        });

                        // Show the message only when rows exist but none passed the filter.
                        emptyMsg.classList.toggle('hidden', visible !== 0 || rows.length === 0);
                    }

                    minInput.addEventListener('input', applyFilter);
                    maxInput.addEventListener('input', applyFilter);
                    resetBtn.addEventListener('click', () => {
                        minInput.value = '';
                        maxInput.value = '';
                        applyFilter();
                    });
                })();

                // Dĺžka tracku — prehliadač načíta iba audio metadáta a doplní mm:ss.
                // Žiadny PHP parser ani knižnica: reálnu dĺžku reportuje samotný prehrávač.
                document.querySelectorAll('.track-duration').forEach(el => {
                    const audio = new Audio();
                    audio.preload = 'metadata';
                    audio.src = el.dataset.src;

                    audio.addEventListener('loadedmetadata', () => {
                        const total = Math.floor(audio.duration);
                        const min = Math.floor(total / 60);
                        const sec = String(total % 60).padStart(2, '0');
                        el.textContent = `${min}:${sec}`;
                    });

                    audio.addEventListener('error', () => {
                        el.textContent = '--:--';
                    });
                });
            </script>

        </section>



        <section id="about" class="max-w-6xl mx-auto px-6 sm:px-10 mt-32 lg:mt-72 mb-16">

            <h2 class="text-[2.75rem] font-thin text-white mb-20">about</h2>

            <!-- Riadok 1 — producer: fotka vľavo, text vpravo -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mb-20">

                <!-- Priestor pre fotku — portrét -->
                <div class="relative aspect-[4/5] w-full max-w-[280px] mx-auto overflow-hidden rounded-2xl border border-white/[0.06] bg-white/[0.03]">
                    <div class="absolute inset-0" style="background: linear-gradient(135deg, #1a0a0a 0%, #3d1010 40%, #0d1a2e 100%);"></div>
                    <div class="absolute inset-0 flex items-center justify-center text-white/25">
                        <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="5" width="18" height="14" rx="2" />
                            <circle cx="9" cy="10" r="2" />
                            <path d="M3 17l5-4 4 3 3-2 6 5" />
                        </svg>
                    </div>
                    <!-- Reálna fotka: <img src="../assets/about-producer.jpg" alt="" class="absolute inset-0 w-full h-full object-cover"> -->
                </div>

                <!-- Textový priestor -->
                <div class="space-y-6 text-[17px] font-thin leading-[1.9] text-white/55">
                    <p>Some text about the producer goes here — a short intro about who they are, the sound they craft and the artists they work with. Replace this copy with the real bio.</p>
                </div>

            </div>

            <!-- Riadok 2 — label: text vľavo, fotka vpravo (na mobile fotka prvá) -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

                <!-- Textový priestor -->
                <div class="order-2 lg:order-1 space-y-6 text-[17px] font-thin leading-[1.9] text-white/55">
                    <p>Some text about the label goes here — its story, roster and what it stands for in the scene. Replace this copy with the real description.</p>
                </div>

                <!-- Priestor pre fotku — landscape -->
                <div class="order-1 lg:order-2 relative aspect-[3/2] w-full max-w-[440px] mx-auto overflow-hidden rounded-2xl border border-white/[0.06] bg-white/[0.03]">
                    <div class="absolute inset-0" style="background: linear-gradient(135deg, #0d1a2e 0%, #16323a 45%, #1a0a0a 100%);"></div>
                    <div class="absolute inset-0 flex items-center justify-center text-white/25">
                        <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="5" width="18" height="14" rx="2" />
                            <circle cx="9" cy="10" r="2" />
                            <path d="M3 17l5-4 4 3 3-2 6 5" />
                        </svg>
                    </div>
                    <!-- Reálna fotka: <img src="../assets/about-label.jpg" alt="" class="absolute inset-0 w-full h-full object-cover"> -->
                </div>

            </div>

            <!-- Socials — horizontálne pod label časťou -->
            <div class="flex items-center justify-center gap-10 sm:gap-16 lg:gap-24 mt-16 lg:mt-28">
                <a href="https://instagram.com/hlinkinn" target="_blank" aria-label="Instagram" class="group relative h-14 w-14">
                    <img src="../assets/icons/ig_icon_base.png" alt="Instagram" class="absolute inset-0 h-full w-full object-contain opacity-60 group-hover:opacity-0 transition-opacity duration-300">
                    <img src="../assets/icons/ig_icon_hover.png" alt="" aria-hidden="true" class="absolute inset-0 h-full w-full object-contain opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                </a>
                <a href="https://open.spotify.com/" target="_blank" aria-label="Spotify" class="group h-14 w-14">
                    <span class="block h-full w-full bg-white/60 group-hover:bg-[#1DB954] transition-colors duration-300"
                        style="-webkit-mask: url('../assets/icons/spotify_icon_base.png') center/contain no-repeat; mask: url('../assets/icons/spotify_icon_base.png') center/contain no-repeat;"></span>
                </a>
                <a href="https://www.youtube.com/@hlinkin808" target="_blank" aria-label="YouTube" class="group h-14 w-14">
                    <span class="block h-full w-full bg-white/60 group-hover:bg-[#db3030] transition-colors duration-300"
                        style="-webkit-mask: url('../assets/icons/yt_icon_base.png') center/contain no-repeat; mask: url('../assets/icons/yt_icon_base.png') center/contain no-repeat;"></span>
                </a>
                <a href="https://www.beatstars.com/hlinkingpin" target="_blank" aria-label="Beatstars" class="group relative h-14 w-14">
                    <img src="../assets/icons/beatstars_icon_base.png" alt="Beatstars" class="absolute inset-0 h-full w-full object-contain opacity-60 group-hover:opacity-0 transition-opacity duration-300">
                    <img src="../assets/icons/beatstars_icon_hovered.png" alt="" aria-hidden="true" class="absolute inset-0 h-full w-full object-contain opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                </a>
            </div>

        </section>



        <div class="relative mt-40 lg:mt-80 z-20 overflow-x-clip">

            <!-- Farebné boby na pozadí -->
            <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; pointer-events: none; overflow: visible;">
                <div style="position: absolute; top: -60px; left: -40px; width: 600px; height: 600px; border-radius: 50%; background-color: #631f1e; opacity: 0.5; filter: blur(110px);"></div>
                <div style="position: absolute; bottom: -80px; right: -30px; width: 480px; height: 480px; border-radius: 50%; background-color: #2a5a66; opacity: 0.4; filter: blur(100px);"></div>
                <div style="position: absolute; top: 30%; right: 12%; width: 360px; height: 360px; border-radius: 50%; background-color: #1d4a55; opacity: 0.4; filter: blur(85px);"></div>
            </div>

            <section id="contact" class="max-w-2xl mx-auto px-4 sm:px-6 py-16 relative z-10">
                <div class="rounded-2xl border border-white/[0.08] bg-white/[0.06] backdrop-blur-[40px] px-6 sm:px-10 pt-7 pb-10">

                    <h2 class="text-[2.75rem] font-thin text-white mb-8">contact</h2>

                    <form id="contact-form" novalidate>

                        <!-- Name + Email -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            <div class="group">
                                <label for="contact-name" class="block text-[10px] font-bold uppercase tracking-[0.25em] text-white/35 mb-2 transition-colors duration-200 group-focus-within:text-white/70">Name</label>
                                <input type="text" name="name" id="contact-name" placeholder="Your name" required
                                    class="w-full rounded-xl border border-white/[0.06] bg-white/[0.03] px-5 py-4 text-[15px] font-thin text-white/80 placeholder-white/20 focus:outline-none focus:border-white/20 transition-colors">
                            </div>
                            <div class="group">
                                <label for="contact-email" class="block text-[10px] font-bold uppercase tracking-[0.25em] text-white/35 mb-2 transition-colors duration-200 group-focus-within:text-white/70">Email</label>
                                <input type="email" name="email" id="contact-email" placeholder="your@email.com" required
                                    class="w-full rounded-xl border border-white/[0.06] bg-white/[0.03] px-5 py-4 text-[15px] font-thin text-white/80 placeholder-white/20 focus:outline-none focus:border-white/20 transition-colors">
                            </div>
                        </div>

                        <!-- Subject -->
                        <div class="mb-4 group">
                            <label for="contact-subject" class="block text-[10px] font-bold uppercase tracking-[0.25em] text-white/35 mb-2 transition-colors duration-200 group-focus-within:text-white/70">Subject</label>
                            <div class="relative">
                                <select name="subject" id="contact-subject" required
                                    class="w-full appearance-none rounded-xl border border-white/[0.06] bg-white/[0.03] pl-12 pr-5 py-4 text-[15px] font-thin text-white/80 focus:outline-none focus:border-white/20 transition-colors cursor-pointer">
                                    <option value="" class="bg-[#111]"></option>
                                    <option value="License" class="bg-[#111]">License</option>
                                    <option value="Collaboration" class="bg-[#111]">Collaboration</option>
                                    <option value="Other" class="bg-[#111]">Other</option>
                                </select>
                                <svg class="absolute left-5 top-1/2 -translate-y-1/2 pointer-events-none text-white/35" width="14" height="8" viewBox="0 0 14 8" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round">
                                    <line x1="0" y1="0" x2="7" y2="7" />
                                    <line x1="7" y1="7" x2="14" y2="0" />
                                </svg>
                            </div>
                        </div>

                        <!-- Track selector — visible only for License -->
                        <div id="track-selector" class="hidden mb-4">
                            <label class="block text-[10px] font-bold uppercase tracking-[0.25em] text-white/35 mb-3">Choose tracks</label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                <?php foreach ($tracks as $track): ?>
                                    <label class="flex items-center gap-3 cursor-pointer rounded-xl border border-white/[0.06] bg-white/[0.03] px-5 py-4 hover:bg-white/[0.06] transition-colors has-[:checked]:border-white/20 has-[:checked]:bg-white/[0.08]">
                                        <input type="checkbox" name="tracks[]" value="<?= htmlspecialchars($track->title) ?>" class="sr-only">
                                        <span class="text-[15px] font-thin text-white/80 truncate"><?= htmlspecialchars($track->title) ?></span>
                                        <span class="ml-auto shrink-0 text-[12px] font-thin text-white/30"><?= htmlspecialchars($track->bpm) ?> BPM</span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Message -->
                        <div class="mb-6 group">
                            <label for="contact-message" class="block text-[10px] font-bold uppercase tracking-[0.25em] text-white/35 mb-2 transition-colors duration-200 group-focus-within:text-white/70">Message</label>
                            <textarea name="message" id="contact-message" rows="5" placeholder="Your message..." required
                                class="w-full rounded-xl border border-white/[0.06] bg-white/[0.03] px-5 py-4 text-[15px] font-thin text-white/80 placeholder-white/20 focus:outline-none focus:border-white/20 transition-colors resize-none"></textarea>
                        </div>

                        <!-- Submit -->
                        <div class="flex justify-end">
                            <button type="submit" id="contact-submit"
                                class="text-[15px] font-thin text-white/60 border border-white/[0.12] rounded-xl px-6 py-4 hover:text-white/90 hover:border-white/25 transition-colors disabled:opacity-40 disabled:cursor-not-allowed">
                                Submit
                            </button>
                        </div>

                    </form>

                </div>
            </section>

        </div>
    </main>

    <!-- Footer wrapper — obrázok pozadia presahuje za rohy footer elementu -->
    <div class="relative w-full mt-[30px] pt-56 sm:pt-[23rem] pb-32 overflow-hidden">

        <!-- Pozadie — celá šírka wrapperu -->
        <img src="../assets/footer-background.png" alt="" class="absolute top-0 left-1/2 -translate-x-1/2 w-[120%] h-[120%] object-cover z-0">

        <!-- Tmavý overlay -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[120%] h-[120%] bg-black/50 z-[5]"></div>

        <!-- Fade — vrchná hrana -->
        <div class="absolute top-0 left-0 w-full h-48 z-[6]" style="background: linear-gradient(to bottom, #050505 0%, transparent 100%);"></div>

        <!-- Footer element — matné sklo nad obrázkom -->
        <footer class="relative z-10 mx-4 sm:mx-8 lg:mx-32">

            <div class="rounded-2xl border border-white/[0.08] bg-white/[0.06] backdrop-blur-[40px] px-6 sm:px-10 pt-7 pb-6">

                <!-- Navigačné odkazy -->
                <nav class="flex flex-col items-start mb-20 mt-10 leading-tight">
                    <a href="#tracks" class="text-[2.75rem] font-thin text-white hover:text-white/55 transition-colors duration-300">tracks</a>
                    <a href="#about" class="text-[2.75rem] font-thin text-white hover:text-white/55 transition-colors duration-300">about</a>
                    <a href="#contact" class="inline-flex items-center gap-3 text-[2.75rem] font-thin text-white hover:text-white/55 transition-colors duration-300">contact <svg xmlns="http://www.w3.org/2000/svg" width="32" height="45" viewBox="0 0 24 30" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="mb-1">
                            <line x1="5" y1="20" x2="15.4" y2="8.6" />
                            <polyline points="7 7 17 7 17 17" />
                        </svg></a>
                </nav>

                <!-- Spodná lišta -->
                <div class="mt-7 pt-4 flex flex-col gap-5 sm:flex-row sm:justify-between sm:items-center">

                    <!-- Vľavo: socials -->
                    <div class="flex flex-wrap items-center justify-center gap-x-5 gap-y-2 text-[10px] font-bold uppercase tracking-[0.25em] text-white/35">
                        <a href="https://instagram.com/hlinkinn" target="_blank" class="hover:text-white transition-colors duration-300">Instagram</a>
                        <a href="mailto:matohlavac1@gmail.com" class="hover:text-white text-transition-colors duration-300">Email</a>
                        <a href="https://www.youtube.com/@hlinkin808" target="_blank" class="hover:text-white transition-colors duration-300">Youtube</a>
                        <a href="https://open.spotify.com/" target="_blank" class="hover:text-white transition-colors duration-300">Spotify</a>
                        <a href="https://www.beatstars.com/hlinkingpin" target="_blank" class="hover:text-white transition-colors duration-300">Beatstars</a>
                    </div>

                    <!-- Vpravo: skrytý admin — len na desktope (computer), na mobile/tablete schovaný -->
                    <div class="hidden lg:flex items-center gap-3 text-[10px] font-bold uppercase tracking-[0.25em] text-white/25">
                        <a href="login.php" class="opacity-60 hover:opacity-100 transition-opacity duration-300">Admin</a>
                    </div>

                </div>

            </div>

        </footer>

        <!-- Spodný text nad obrázkom -->
        <p class="ocr-a absolute bottom-28 left-0 right-0 z-10 flex justify-center items-center gap-x-16 text-[10px] font-bold uppercase tracking-[0.25em] text-white/30">
            <span>© 2026. All rights reserved.</span>
            <span>Made by: Matej Hlaváč</span>
        </p>

    </div>





    <!-- Sticky audio player — plávajúci zaoblený box, vycentrovaný, vždy navrchu -->
    <div id="player-bar"
        class="fixed bottom-4 left-1/2 -translate-x-1/2 translate-y-[200%] z-[9999] w-[92%] sm:w-1/2
           rounded-2xl border border-white/[0.08] bg-white/[0.06] backdrop-blur-[40px] shadow-2xl
           transition-transform duration-500">
        <div class="px-4 sm:px-6 py-4 flex items-center gap-4 sm:gap-6">

            <!-- Play / pause — JS prepína .hidden medzi oboma ikonami -->
            <button id="player-toggle" aria-label="Play/Pause"
                class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full border border-white/[0.12] bg-white/[0.1] hover:bg-white/[0.16] transition-colors">

                <!-- Play ikona -->
                <svg id="player-icon-play" width="12" height="14" viewBox="-1 -1 13 15" style="transform: translateX(1.5px);" class="opacity-80">
                    <path d="M0 0.8L11 6.5L0 12.2V0.8Z" fill="white" stroke="white" stroke-width="1.4" stroke-linejoin="round" />
                </svg>

                <!-- Pause ikona — skrytá kým track nehrá -->
                <svg id="player-icon-pause" width="12" height="14" viewBox="0 0 12 14" class="hidden opacity-80">
                    <rect x="1.5" y="1" width="3" height="12" rx="1" fill="white" />
                    <rect x="7.5" y="1" width="3" height="12" rx="1" fill="white" />
                </svg>
            </button>

            <!-- Názov tracku -->
            <div class="min-w-0 w-24 sm:w-32 shrink-0">
                <p id="player-title" class="truncate text-[15px] font-medium text-white/90">—</p>
            </div>

            <!-- Aktuálny čas — vľavo od slideru -->
            <span id="player-current" class="shrink-0 font-mono text-[12px] tabular-nums text-white/45">0:00</span>

            <!-- Seek slider -->
            <input id="player-seek" type="range" min="0" max="100" value="0"
                class="flex-1 cursor-pointer accent-white/80">

            <!-- Celková dĺžka — vpravo od slideru -->
            <span id="player-duration" class="shrink-0 font-mono text-[12px] tabular-nums text-white/45">0:00</span>

            <!-- Zavrieť -->
            <button id="player-close" aria-label="Close player"
                class="shrink-0 text-white/35 hover:text-white/80 transition-colors">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

        </div>
    </div>













    <!-- TODO: overlay menu redesign -->

    <!-- Feedback toast -->
    <div id="contact-feedback" class="opacity-0 fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[100] text-[15px] font-thin border border-white/[0.12] rounded-xl px-8 py-4 pointer-events-none backdrop-blur-[40px] bg-white/[0.06] transition-opacity duration-500"></div>

    <script>
        // Podmienečný track selector — zobrazí sa len pri predmete "Licencia"
        const subjectEl = document.getElementById('contact-subject');
        const trackSelector = document.getElementById('track-selector');

        function syncTrackSelector() {
            trackSelector.classList.toggle('hidden', subjectEl.value !== 'License');
        }

        subjectEl.addEventListener('change', syncTrackSelector);

        // Inicializácia pri načítaní — rieši obnovu stavu formulára Chromom
        syncTrackSelector();

        // Async odoslanie kontaktného formulára
        document.getElementById('contact-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = document.getElementById('contact-submit');
            const feedback = document.getElementById('contact-feedback');

            btn.disabled = true;
            btn.textContent = 'Sending...';

            const formData = new FormData(e.target);

            function showFeedback(message, success) {
                feedback.textContent = message;
                feedback.classList.remove('text-green-400', 'text-red-400', 'opacity-0');
                feedback.classList.add(success ? 'text-green-400' : 'text-red-400', 'opacity-100');
                setTimeout(() => {
                    feedback.classList.remove('opacity-100');
                    feedback.classList.add('opacity-0');
                }, 1500);
            }

            try {
                const res = await fetch('api/send_contact.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await res.json();

                showFeedback(data.message, data.success);

                if (data.success) {
                    e.target.reset();
                    trackSelector.classList.add('hidden');
                }
            } catch {
                showFeedback('Network error. Please try again.', false);
            }

            btn.disabled = false;
            btn.textContent = 'Submit';
        });

        // Hamburger toggle — otvorí/zatvorí nav linky
        const menuToggle = document.getElementById('menu-toggle');
        const navLinks = document.getElementById('nav-links');
        const iconMenu = document.getElementById('icon-menu');
        const iconClose = document.getElementById('icon-close');

        menuToggle.addEventListener('click', () => {
            const isOpen = navLinks.classList.toggle('open');
            iconMenu.classList.toggle('menu-active', isOpen);
            iconClose.classList.toggle('menu-active', isOpen);
        });

        // Klik na link NEcháva menu otvorené — počas plynulého scrollu z kliknutia
        // navyše dočasne potlačíme automatické skrytie navbaru pri scrollovaní.
        let suppressScrollHide = false;
        let suppressTimer = null;
        navLinks.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                suppressScrollHide = true;
                clearTimeout(suppressTimer);
                suppressTimer = setTimeout(() => {
                    suppressScrollHide = false;
                }, 1000);
            });
        });

        // Skryje/zobrazí navbar pri scrollovaní
        const mainNav = document.getElementById('main-nav');
        let lastScrollY = window.scrollY;

        window.addEventListener('scroll', () => {
            const currentScrollY = window.scrollY;
            if (!suppressScrollHide) {
                if (currentScrollY > lastScrollY && currentScrollY > 80) {
                    mainNav.classList.add('nav-hidden');
                    navLinks.classList.add('nav-hidden');
                } else {
                    mainNav.classList.remove('nav-hidden');
                    navLinks.classList.remove('nav-hidden');
                }
            }
            lastScrollY = currentScrollY;
        });
    </script>














    <!-- sticky audio player using Howler.js -->
    <script>
        (function() {

            // state
            let currentHowl = null;
            let currentRow = null;

            const bar = document.getElementById('player-bar');
            const toggleBtn = document.getElementById('player-toggle');
            const iconPlay = document.getElementById('player-icon-play');
            const iconPause = document.getElementById('player-icon-pause');
            const titleEl = document.getElementById('player-title');

            const seek = document.getElementById('player-seek');
            const currentEl = document.getElementById('player-current');
            const durationEl = document.getElementById('player-duration');
            const closeBtn = document.getElementById('player-close');






            // auxiliary function - seconds to -> "m:ss"
            function formatTime(seconds) {
                const m = Math.floor(seconds / 60);
                const s = String(Math.floor(seconds % 60)).padStart(2, '0');
                return `${m}:${s}`;
            }







            let isSeeking = false;

            function progressLoop() {
                if (currentHowl && currentHowl.playing() && !isSeeking) {
                    const pos = currentHowl.seek();
                    const dur = currentHowl.duration();
                    currentEl.textContent = formatTime(pos);
                    seek.value = (pos / dur) * 100;
                }
                requestAnimationFrame(progressLoop);
            }

            seek.addEventListener('input', () => {
                isSeeking = true;
                if (!currentHowl) return;
                const dur = currentHowl.duration();
                currentEl.textContent = formatTime((seek.value / 100) * dur);
            })


            seek.addEventListener('change', () => {
                if (currentHowl) {
                    const dur = currentHowl.duration();
                    currentHowl.seek((seek.value / 100) * dur);
                }
                isSeeking = false;
            })







            closeBtn.addEventListener('click', () => {
                if (currentHowl) {
                    currentHowl.stop();
                    currentHowl.unload();
                }
                currentHowl = null;
                currentRow = null;
                showPlayingUI(false);
                seek.value = 0;
                currentEl.textContent = '0:00';
                bar.classList.add('translate-y-[200%]'); // skry bar
            });



            // function to display pause or play icon
            function showPlayingUI(isPlaying) {
                iconPlay.classList.toggle('hidden', isPlaying);
                iconPause.classList.toggle('hidden', !isPlaying);
            }






            function playTrack(row) {
                if ((row === currentRow) && (currentHowl)) {
                    togglePlayPause();
                    return;
                }

                if (currentHowl) {
                    currentHowl.unload();
                }

                currentRow = row;

                currentHowl = new Howl({
                    src: [row.dataset.src],
                    html5: true,
                    onplay: () => showPlayingUI(true),
                    onpause: () => showPlayingUI(false),
                    onload: () => durationEl.textContent = formatTime(currentHowl.duration()),
                    onend: () => {
                        showPlayingUI(false);
                        seek.value = 0;
                        currentEl.textContent = '0:00';
                    }
                });

                currentHowl.play();

                titleEl.textContent = row.dataset.title;
                bar.classList.remove('translate-y-[200%]');
            }




            function togglePlayPause() {
                if (!currentHowl) return;
                if (currentHowl.playing()) {
                    currentHowl.pause();
                } else {
                    currentHowl.play();
                }
            }






            document.querySelectorAll('.track-row').forEach(row => {
                row.addEventListener('click', () => playTrack(row));
            });

            toggleBtn.addEventListener('click', togglePlayPause);

            requestAnimationFrame(progressLoop);

        })();
    </script>


</body>

</html>