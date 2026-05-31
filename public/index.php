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

    <footer class="w-full mt-32 px-6 flex flex-col gap-4">

        <div class="grid grid-cols-3 items-center py-14">

            <!-- logo vlavo -->
            <div class="flex justify-center ml-32">
                <div class="w-12 h-12 rounded-xl border border-white/[0.06] bg-white/[0.02] flex items-center justify-center">
                    <span class="text-[9px] font-bold text-white/15 uppercase tracking-widest">Logo</span>
                </div>
            </div>

            <!-- stred -->
            <div class="flex flex-col items-center gap-5">
                <div class="flex items-center gap-6">
                    <a href="https://instagram.com/hlinkinn" target="_blank" class="text-white/25 hover:text-white/80 transition-colors duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <rect x="2" y="2" width="20" height="20" rx="5" ry="5" />
                            <circle cx="12" cy="12" r="4" />
                            <circle cx="17.5" cy="6.5" r="0.5" fill="currentColor" stroke="none" />
                        </svg>
                    </a>
                    <a href="mailto:you@example.com" class="text-white/25 hover:text-white/80 transition-colors duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <rect x="2" y="4" width="20" height="16" rx="2" />
                            <polyline points="2,4 12,13 22,4" />
                        </svg>
                    </a>
                </div>

                <div class="w-32 h-px bg-white/10"></div>

                <nav class="flex items-center gap-8">
                    <a href="#tracks" class="text-[12px] font-bold text-white/30 uppercase tracking-[0.25em] hover:text-white/80 transition-colors duration-300">Tracks</a>
                    <a href="#about" class="text-[12px] font-bold text-white/30 uppercase tracking-[0.25em] hover:text-white/80 transition-colors duration-300">About</a>
                    <a href="#contact" class="text-[12px] font-bold text-white/30 uppercase tracking-[0.25em] hover:text-white/80 transition-colors duration-300">Contact</a>
                </nav>
            </div>

            <!-- logo vpravo -->
            <div class="flex justify-center mr-32">
                <div class="w-12 h-12 rounded-xl border border-white/[0.06] bg-white/[0.02] flex items-center justify-center">
                    <span class="text-[9px] font-bold text-white/15 uppercase tracking-widest">Logo</span>
                </div>
            </div>

        </div>

        <div class="grid grid-cols-3 items-center border-t border-white/[0.04] py-4">
            <p class="text-[10px] font-bold text-white/20 uppercase tracking-[0.25em]">© 2026</p>

            <p class="text-[10px] text-white/15 text-center">
                Made by: <a href="mailto:matohlavac1@gmail.com" class="hover:text-white/40 transition-colors duration-300">Matej Hlaváč</a>
            </p>

            <a href="login.php" class="text-[10px] font-bold text-white/20 uppercase tracking-[0.25em] hover:text-white/80 transition-colors duration-300 justify-self-end">Admin</a>
        </div>

    </footer>













    <script>

    </script>

</body>

</html>