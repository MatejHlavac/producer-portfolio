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

?>

<!DOCTYPE html>
<html lang="sk" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Producer Portfolio</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[#050505]">

    <nav class="fixed top-0 left-0 w-full z-50 px-10 py-5 flex items-center justify-between backdrop-blur-[40px] bg-white/[0.03] border-b border-white/[0.06]">

        <!-- Logo -->
        <a href="#hero" class="text-[12px] font-bold text-white/40 uppercase tracking-[0.25em] hover:text-white/90 transition-colors duration-300">Producer Portfolio</a>

        <!-- Nav Links -->
        <div class="flex items-center gap-8">
            <a href="#hero" class="text-[12px] font-bold text-white/40 uppercase tracking-[0.25em] hover:text-white/90 transition-colors duration-300">Producer Portfolio</a>
            <a href="#tracks" class="text-[12px] font-bold text-white/40 uppercase tracking-[0.25em] hover:text-white/90 transition-colors duration-300">Tracks</a>
            <a href="#about" class="text-[12px] font-bold text-white/40 uppercase tracking-[0.25em] hover:text-white/90 transition-colors duration-300">About</a>
            <a href="#contact" class="text-[12px] font-bold text-white/40 uppercase tracking-[0.25em] hover:text-white/90 transition-colors duration-300">Contact</a>
        </div>

    </nav>

    <main>
        <section id="hero"></section>
        <section id="tracks"></section>
        <section id="about"></section>
        <section id="contact"></section>
    </main>

</body>

</html>

<script>
    const sections = ['tracks', 'about', 'contact'];
    const links = document.querySelectorAll('nav a');

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                links.forEach(link => {
                    const active = link.getAttribute('href') === `#${entry.target.id}`;
                    link.classList.toggle('text-white/90', active);
                    link.classList.toggle('text-white/40', !active);
                    link.classList.toggle('hover:text-white/90', !active);
                });
            }
        });
    }, {
        threshold: 0.5
    });

    sections.forEach(id => {
        const el = document.getElementById(id);
        if (el) observer.observe(el);
    });
</script>