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

?>

<!DOCTYPE html>
<html lang="sk" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Producer Portfolio</title>
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

        #main-nav {
            transition: transform 0.5s ease;
        }

        #main-nav.nav-hidden {
            transform: translateY(calc(-100% - 60px));
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
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            align-items: center;
            gap: 1.6rem;
            opacity: 0;
            z-index: 50;
            isolation: isolate;
            transition: opacity 0.35s ease, transform 0.5s ease;
            pointer-events: none;
        }

        #nav-links a {
            font-size: 21px;
            transition: color 0.3s ease;
        }

        #nav-links a:hover {
            color: rgba(255, 255, 255, 0.5);
        }

        #nav-links::before {
            content: '';
            position: absolute;
            inset: -50px -80px;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            background: rgba(255, 255, 255, 0.03);
            border-radius: 20px;
            mask-image: radial-gradient(ellipse at center, black 25%, transparent 72%);
            -webkit-mask-image: radial-gradient(ellipse at center, black 25%, transparent 72%);
            z-index: -1;
        }

        #nav-links.open {
            opacity: 1;
            pointer-events: auto;
        }

        #nav-links.nav-hidden {
            transform: translateX(-50%) translateY(calc(-100% - 60px));
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
    </style>
</head>

<body class="bg-[#050505] overflow-x-hidden">

    <!-- Navbar -->
    <nav id="main-nav" class="fixed top-10 z-50" style="left: calc(10rem + 50px);">

        <!-- Hamburger ikona -->
        <button id="menu-toggle" class="group p-2 flex items-center justify-center">
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
            <div class="relative z-10 flex flex-col items-center justify-center px-10" style="height:100%;padding-bottom:80px;">
                <h1 class="text-[5rem] sm:text-[7rem] lg:text-[9rem] font-thin text-white leading-none tracking-tight mb-8 mt-32">hlinkinn</h1>
                <p class="text-[11px] font-bold uppercase tracking-[0.25em] text-white/30">Beats &nbsp;•&nbsp; Productions &nbsp;•&nbsp; Collaborations</p>

                <!-- Audio waveform ikona s pulzujúcou glow -->
                <div class="relative mt-32">
                    <div class="absolute" style="width:360px;height:250px;top:50%;left:50%;transform:translate(-50%,-50%);background:radial-gradient(circle,#a83030 0%,#631f1e 35%,transparent 70%);filter:blur(50px);animation:glowPulse 3s ease-in-out infinite;"></div>
                    <svg class="relative text-white/80" width="126" height="91" viewBox="0 0 126 91" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <line x1="5" y1="42.3333" x2="5" y2="48.6666" stroke="currentColor" stroke-width="10" stroke-linecap="round" />
                        <line x1="23.9854" y1="32.2222" x2="23.9854" y2="58.7778" stroke="currentColor" stroke-width="10" stroke-linecap="round" />
                        <line x1="42.9712" y1="38.4443" x2="42.9712" y2="52.5554" stroke="currentColor" stroke-width="10" stroke-linecap="round" />
                        <line x1="61.957" y1="20.5557" x2="61.957" y2="70.4446" stroke="currentColor" stroke-width="10" stroke-linecap="round" />
                        <line x1="80.9424" y1="27.5557" x2="80.9424" y2="63.4446" stroke="currentColor" stroke-width="10" stroke-linecap="round" />
                        <line x1="99.9282" y1="36.1111" x2="99.9282" y2="54.8889" stroke="currentColor" stroke-width="10" stroke-linecap="round" />
                        <line x1="118.914" y1="42.3333" x2="118.914" y2="48.6666" stroke="currentColor" stroke-width="10" stroke-linecap="round" />
                    </svg>
                </div>
            </div>

            <!-- Vlnová animácia — na spodku hero sekcie -->
            <div class="absolute bottom-0 left-0 w-full z-10 pointer-events-none">
                <svg id="waveform-svg" viewBox="0 -115 1280 130" preserveAspectRatio="xMinYMid meet" fill="none" xmlns="http://www.w3.org/2000/svg" style="width:100%;display:block;">

                    <style>
                        #waveform-svg {
                            height: 450px;
                        }

                        @media (max-width: 767px) {
                            #waveform-svg {
                                height: 240px;
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



        <section id="tracks" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 max-w-6xl mx-auto px-10">
            <?php foreach ($tracks as $track): ?>
                <div class="group overflow-hidden rounded-2xl border border-white/[0.06] bg-white/[0.03] transition-all duration-300 hover:bg-white/[0.05]">

                    <div class="h-32 w-full" style="background: linear-gradient(135deg, #1a0a0a 0%, #3d1010 40%, #0d1a2e 100%);"></div>

                    <div class="px-4 py-4">
                        <p class="mb-1 truncate text-[15px] font-medium text-white/90">
                            <?= htmlspecialchars($track->title) ?>
                        </p>
                        <p class="mb-4 text-[10px] font-bold uppercase tracking-[0.2em] text-white/35">
                            <?= htmlspecialchars($track->genre) ?>
                        </p>
                        <div class="flex items-center gap-2">
                            <svg class="h-3.5 w-3.5 text-white/20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" />
                                <polyline points="12 6 12 12 16 14" />
                            </svg>
                            <span class="font-mono text-[11px] text-white/30 transition-colors duration-300 group-hover:text-white/60">
                                <?= htmlspecialchars($track->bpm) ?> BPM
                            </span>
                        </div>
                    </div>

                </div>
            <?php endforeach; ?>
        </section>



        <section id="about">

        </section>



        <div class="relative mt-16 z-20">

            <!-- Farebné boby na pozadí -->
            <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; pointer-events: none; overflow: visible;">
                <div style="position: absolute; top: -60px; left: -40px; width: 600px; height: 600px; border-radius: 50%; background-color: #631f1e; opacity: 0.5; filter: blur(110px);"></div>
                <div style="position: absolute; bottom: -80px; right: -30px; width: 480px; height: 480px; border-radius: 50%; background-color: #2a5a66; opacity: 0.4; filter: blur(100px);"></div>
                <div style="position: absolute; top: 30%; right: 12%; width: 360px; height: 360px; border-radius: 50%; background-color: #1d4a55; opacity: 0.4; filter: blur(85px);"></div>
            </div>

            <section id="contact" class="max-w-2xl mx-auto py-16 relative z-10">
                <div class="rounded-2xl border border-white/[0.08] bg-white/[0.06] backdrop-blur-[40px] px-10 pt-7 pb-10">

                    <p class="text-[2.75rem] font-thin text-white mb-8">contact</p>

                    <form id="contact-form" novalidate>

                        <!-- Name + Email -->
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div class="group">
                                <label class="block text-[10px] font-bold uppercase tracking-[0.25em] text-white/35 mb-2 transition-colors duration-200 group-focus-within:text-white/70">Name</label>
                                <input type="text" name="name" placeholder="Your name" required
                                    class="w-full rounded-xl border border-white/[0.06] bg-white/[0.03] px-5 py-4 text-[15px] font-thin text-white/80 placeholder-white/20 focus:outline-none focus:border-white/20 transition-colors">
                            </div>
                            <div class="group">
                                <label class="block text-[10px] font-bold uppercase tracking-[0.25em] text-white/35 mb-2 transition-colors duration-200 group-focus-within:text-white/70">Email</label>
                                <input type="email" name="email" placeholder="your@email.com" required
                                    class="w-full rounded-xl border border-white/[0.06] bg-white/[0.03] px-5 py-4 text-[15px] font-thin text-white/80 placeholder-white/20 focus:outline-none focus:border-white/20 transition-colors">
                            </div>
                        </div>

                        <!-- Subject -->
                        <div class="mb-4 group">
                            <label class="block text-[10px] font-bold uppercase tracking-[0.25em] text-white/35 mb-2 transition-colors duration-200 group-focus-within:text-white/70">Subject</label>
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
                            <div class="grid grid-cols-2 gap-2">
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
                            <label class="block text-[10px] font-bold uppercase tracking-[0.25em] text-white/35 mb-2 transition-colors duration-200 group-focus-within:text-white/70">Message</label>
                            <textarea name="message" rows="5" placeholder="Your message..." required
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
    <div class="relative w-full mt-[30px] pt-[23rem] pb-32 overflow-hidden">

        <!-- Pozadie — celá šírka wrapperu -->
        <img src="../assets/footer-background.png" alt="" class="absolute top-0 left-1/2 -translate-x-1/2 w-[120%] h-[120%] object-cover z-0">

        <!-- Tmavý overlay -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[120%] h-[120%] bg-black/50 z-[5]"></div>

        <!-- Fade — vrchná hrana -->
        <div class="absolute top-0 left-0 w-full h-48 z-[6]" style="background: linear-gradient(to bottom, #050505 0%, transparent 100%);"></div>

        <!-- Footer element — matné sklo nad obrázkom -->
        <footer class="relative z-10 mx-32">

            <div class="rounded-2xl border border-white/[0.08] bg-white/[0.06] backdrop-blur-[40px] px-10 pt-7 pb-6">

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
                <div class="mt-7 pt-4 flex justify-between items-center">

                    <!-- Vľavo: Instagram • Email -->
                    <div class="flex items-center gap-3 text-[10px] font-bold uppercase tracking-[0.25em] text-white/35">
                        <a href="https://instagram.com/hlinkinn" target="_blank" class="hover:text-white transition-colors duration-300">Instagram</a>
                        <span class="text-white/15">•</span>
                        <a href="mailto:matohlavac1@gmail.com" class="hover:text-white text-transition-colors duration-300">Email</a>
                        <span class="text-white/15">•</span>
                        <a href="https://www.youtube.com/@hlinkin808" target="_blank" class="hover:text-white transition-colors duration-300">Youtube</a>
                        <span class="text-white/15">•</span>
                        <a href="https://www.beatstars.com/hlinkingpin" target="_blank" class="hover:text-white transition-colors duration-300">Beatstars</a>
                    </div>

                    <!-- Vpravo: skrytý admin -->
                    <div class="flex items-center gap-3 text-[10px] font-bold uppercase tracking-[0.25em] text-white/25">
                        <a href="login.php" class="opacity-60 hover:opacity-100 transition-opacity duration-300">Admin</a>
                    </div>

                </div>

            </div>

        </footer>

        <!-- Spodný text nad obrázkom -->
        <p class="ocr-a absolute bottom-8 left-0 right-0 z-10 text-center text-[10px] font-bold uppercase tracking-[0.25em] text-white/30">Made by: Matej Hlaváč &nbsp;•&nbsp; © 2026. All rights reserved.</p>

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

        // Klik na link zatvorí menu
        navLinks.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                navLinks.classList.remove('open');
                iconMenu.classList.remove('menu-active');
                iconClose.classList.remove('menu-active');
            });
        });

        // Skryje/zobrazí navbar pri scrollovaní
        const mainNav = document.getElementById('main-nav');
        let lastScrollY = window.scrollY;

        function closeMenu() {
            navLinks.classList.remove('open');
            iconMenu.classList.remove('menu-active');
            iconClose.classList.remove('menu-active');
        }

        window.addEventListener('scroll', () => {
            const currentScrollY = window.scrollY;
            if (currentScrollY > lastScrollY && currentScrollY > 80) {
                mainNav.classList.add('nav-hidden');
                navLinks.classList.add('nav-hidden');
            } else {
                mainNav.classList.remove('nav-hidden');
                navLinks.classList.remove('nav-hidden');
            }
            lastScrollY = currentScrollY;
        });
    </script>


</body>

</html>