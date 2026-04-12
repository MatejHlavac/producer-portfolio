<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

require_once "../vendor/autoload.php";

use App\Database\Connection;
use App\Repositories\TrackRepository;
use App\Models\Track;

$db = (new Connection())->connect();
$trackRepo = new TrackRepository($db);

$tracks = $trackRepo->findAll();

?>
<script src="https://cdn.tailwindcss.com"></script>
<div class="relative flex items-center justify-center min-h-screen bg-[#050505] overflow-hidden p-6 font-sans">


    <!-- delete track confirmation modal -->
    <div id="custom-confirm" class="fixed hidden inset-0 z-50 flex items-center justify-center p-4">

        <div class="relative w-full max-w-sm bg-white/[0.04] border border-white/[0.05] rounded-[2.5rem] p-10 shadow-2xl backdrop-blur-[30px] overflow-hidden">

            <div class="relative z-10">
                <h3 class="text-xl font-medium text-white/90 tracking-tight text-center mb-10">
                    Do you really want to delete this track?
                </h3>

                <div class="flex gap-3">

                    <button id="ok-btn" class="w-full px-6 py-4 bg-emerald-500/[0.03] hover:bg-emerald-500/[0.08] border border-emerald-500/10 hover:border-emerald-500/30 rounded-2xl text-sm font-medium text-emerald-400/70 hover:text-emerald-400 transition-all duration-300">
                        OK
                    </button>

                    <button id="cancel-btn" class="w-full px-6 py-4 bg-red-500/[0.03] hover:bg-red-500/[0.08] border border-red-500/10 hover:border-red-500/30 rounded-2xl text-sm font-medium text-red-400/60 hover:text-red-400 transition-all duration-300">
                        Cancel
                    </button>

                </div>
            </div>
        </div>
    </div>


    <!-- uploaded tracks list -->
    <div class="absolute -top-40 -left-20 w-[600px] h-[600px] bg-blue-700/20 rounded-full blur-[160px] opacity-70"></div>
    <div class="absolute bottom-[-10%] left-[15%] w-[500px] h-[500px] bg-emerald-700/15 rounded-full blur-[140px]"></div>
    <div class="absolute top-[10%] right-[-10%] w-[500px] h-[500px] bg-purple-600/20 rounded-full blur-[90px] opacity-60"></div>

    <div class="relative w-full max-w-6xl bg-white/[0.03] border border-white/[0.08] rounded-[2.5rem] p-8 shadow-2xl backdrop-blur-[40px]">

        <div class="mb-8 relative">
            <h2 class="text-2xl font-medium text-white/95 tracking-tight">Uploaded tracks</h2>
            <p class="text-[11px] font-bold text-white/40 tracking-[0.25em] uppercase mt-1">List of available tracks</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-separate border-spacing-y-3">
                <thead>
                    <tr class="text-[11px] font-bold text-white/30 tracking-[0.25em] uppercase">
                        <th class="px-6 py-2">ID</th>
                        <th class="px-6 py-2">Title</th>
                        <th class="px-6 py-2">Genre</th>
                        <th class="px-6 py-2">BPM</th>
                        <th class="px-6 py-2">File path</th>
                        <th class="px-6 py-2 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    <?php foreach ($tracks as $track): ?>
                        <tr class="group transition-all duration-300">
                            <td class="px-6 py-4 bg-white/[0.02] group-hover:bg-white/[0.05] border-y border-l border-white/[0.06] rounded-l-2xl text-white/50 transition-all duration-300">
                                <?= htmlspecialchars($track->id) ?>
                            </td>

                            <td class="px-6 py-4 bg-white/[0.02] group-hover:bg-white/[0.05] border-y border-white/[0.06] font-medium text-white/90 transition-all duration-300">
                                <div class="flex items-center gap-3">
                                    <?= htmlspecialchars($track->title) ?>
                                </div>
                            </td>

                            <td class="px-6 py-4 bg-white/[0.02] group-hover:bg-white/[0.05] border-y border-white/[0.06] font-medium text-white/90 transition-all duration-300">
                                <div class="flex items-center gap-3">
                                    <?= htmlspecialchars($track->genre) ?>
                                </div>
                            </td>

                            <td class="px-6 py-4 bg-white/[0.02] group-hover:bg-white/[0.05] border-y border-white/[0.06] font-mono text-[12px] text-white/50 transition-all duration-300">
                                <span class="group-hover:text-white transition-colors duration-300">
                                    <?= htmlspecialchars($track->bpm) ?>
                                </span>
                            </td>

                            <td class="px-6 py-4 bg-white/[0.02] group-hover:bg-white/[0.05] border-y border-white/[0.06] font-mono text-[12px] text-white/50 transition-all duration-300">
                                <span class="group-hover:text-white transition-colors duration-300">
                                    <?= htmlspecialchars($track->file_path) ?>
                                </span>
                            </td>

                            <td class="px-6 py-4 bg-white/[0.02] group-hover:bg-white/[0.05] border-y border-r border-white/[0.06] rounded-r-2xl text-right transition-all duration-300">
                                <div class="flex items-center justify-end gap-5">
                                    <button class="text-[10px] font-bold text-white/30 uppercase tracking-widest hover:text-white transition-colors duration-300">
                                        Edit
                                    </button>

                                    <button data-id="<?= htmlspecialchars($track->id) ?>" data-name="<?= htmlspecialchars($track->title) ?>" class="open-modal-btn group/del relative flex items-center justify-center w-7 h-7 rounded-full border border-red-500/40 bg-red-950/20 shadow-[0_0_10px_rgba(239,68,68,0.2)] transition-all duration-300 hover:border-red-500 hover:bg-red-500 hover:shadow-[0_0_15px_rgba(239,68,68,0.5)] focus:outline-none" title="Remove">
                                        <svg
                                            class="w-3.5 h-3.5 text-red-400 transition-colors duration-300 group-hover/del:text-white"
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke-width="2.5"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>


        </div>
    </div>
</div>



<script>
    let pendingId = null;

    const modal = document.getElementById('custom-confirm');
    const okBtn = document.getElementById('ok-btn');
    const cancelBtn = document.getElementById('cancel-btn');

    document.querySelectorAll('.open-modal-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            pendingId = btn.getAttribute('data-id');
            modal.classList.remove('hidden');
        });
    });

    cancelBtn.addEventListener('click', () => {
        modal.classList.add('hidden');
        pendingId = null;
    });


    okBtn.addEventListener('click', () => {
        if (pendingId) {

            fetch('api/delete_track.php?id=' + pendingId)
                .then(response => response.json())
                .then(data => {
                    if (data.success === true) {
                        location.reload();
                    } else {
                        alert("Error: " + data.message);
                    }
                })
                .catch(error => {
                    console.error("Communication error: ", error);
                    alert("Couldn't connect to the server.");
                });

            modal.classList.add('hidden');
        }
    });
</script>