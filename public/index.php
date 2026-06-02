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
    </style>
</head>

<body class="bg-[#050505]">

    <nav class="fixed top-0 left-0 w-full z-50 px-10 py-5 flex items-center justify-between backdrop-blur-[40px] bg-white/[0.03] border-b border-white/[0.06]">

        <!-- Logo -->
        <a href="#hero" class="text-[12px] font-bold text-white/40 uppercase tracking-[0.25em] hover:text-white/90 transition-colors duration-300">Producer Portfolio</a>

        <!-- Nav Links -->
        <div id="main-navigation" class="flex items-center gap-8">
            <a href="#tracks" class="text-[12px] font-bold text-white/40 uppercase tracking-[0.25em] hover:text-white/90 transition-colors duration-300">Tracks</a>
            <a href="#about" class="text-[12px] font-bold text-white/40 uppercase tracking-[0.25em] hover:text-white/90 transition-colors duration-300">About</a>
            <a href="#contact" class="text-[12px] font-bold text-white/40 uppercase tracking-[0.25em] hover:text-white/90 transition-colors duration-300">Contact</a>
        </div>

    </nav>

    <main>
        <section id="hero">

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



        <section id="contact">

        </section>
    </main>

    <!-- Footer wrapper — obrázok pozadia presahuje za rohy footer elementu -->
    <div class="relative w-full mt-16 pt-[23rem] pb-32 overflow-hidden">

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













    <script>

    </script>

</body>

</html>