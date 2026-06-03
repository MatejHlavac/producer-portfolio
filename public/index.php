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

<body class="bg-[#050505] overflow-x-hidden">

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
                            <label class="block text-[10px] font-bold uppercase tracking-[0.25em] text-white/35 mb-3">Which tracks are you interested in?</label>
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
    </script>

</body>

</html>