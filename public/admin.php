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

    <div class="absolute inset-0 z-0 pointer-events-none opacity-30"
        style="background-image: url('../assets/background-inspo-2.jpg'); background-size: cover; background-position: center; filter: grayscale(100%) blur(3px);">
    </div>


    <div id="add-modal" class="fixed hidden inset-0 z-50 flex items-center justify-center p-4">
        <div class="relative w-full max-w-2xl bg-white/[0.04] border border-white/[0.05] rounded-[2.5rem] p-10 shadow-2xl backdrop-blur-[30px] overflow-hidden">

            <div class="text-center mb-10">
                <h3 class="text-white/90 font-medium text-2xl mb-2">Upload New Entry</h3>
                <p class="text-white/40 text-[11px] font-bold uppercase tracking-widest">Fill in the details to upload a new track</p>
            </div>

            <form id="add-track-form" enctype="multipart/form-data" class="space-y-6">

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[10px] font-bold text-white/20 uppercase tracking-[0.2em] mb-2 ml-1">Track Title</label>
                        <input type="text" name="title" required
                            class="w-full bg-white/[0.03] border border-white/[0.08] rounded-2xl px-5 py-4 text-white text-sm placeholder:text-white/20 focus:outline-none focus:border-emerald-500/30 transition-all"
                            placeholder="Song name...">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-white/20 uppercase tracking-[0.2em] mb-2 ml-1">Genre</label>
                        <input type="text" name="genre" required
                            class="w-full bg-white/[0.03] border border-white/[0.08] rounded-2xl px-5 py-4 text-white text-sm placeholder:text-white/20 focus:outline-none focus:border-emerald-500/30 transition-all"
                            placeholder="e.g. House">
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-6">
                    <div class="col-span-1">
                        <label class="block text-[10px] font-bold text-white/20 uppercase tracking-[0.2em] mb-2 ml-1">BPM</label>
                        <input type="number" name="bpm" required
                            class="w-full bg-white/[0.03] border border-white/[0.08] rounded-2xl px-5 py-4 text-white text-sm placeholder:text-white/20 focus:outline-none focus:border-emerald-500/30 transition-all"
                            placeholder="120">
                    </div>

                    <div class="col-span-2">
                        <label class="block text-[10px] font-bold text-white/20 uppercase tracking-[0.2em] mb-2 ml-1">Audio File (MP3)</label>
                        <div class="relative group">
                            <input type="file" name="audio" accept="audio/mpeg" required
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                onchange="document.getElementById('add-file-name-display').innerText = this.files[0].name">
                            <div class="w-full bg-white/[0.02] border border-white/[0.1] border-dashed rounded-2xl px-5 py-4 text-white/30 group-hover:border-emerald-500/50 transition-all flex items-center justify-between">
                                <span id="add-file-name-display" class="text-sm truncate mr-2">Select file...</span>
                                <svg class="w-4 h-4 opacity-30 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 4v16m8-8H4" stroke-width="2" stroke-linecap="round" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 pt-6">
                    <button type="submit"
                        class="w-full px-6 py-4 bg-emerald-500/[0.03] hover:bg-emerald-500/[0.08] border border-emerald-500/10 hover:border-emerald-500/30 rounded-2xl text-sm font-medium text-emerald-400/70 hover:text-emerald-400 transition-all duration-300">
                        Upload
                    </button>
                    <button type="button" id="close-add-modal"
                        class="w-full px-6 py-4 bg-red-500/[0.03] hover:bg-red-500/[0.08] border border-red-500/10 hover:border-red-500/30 rounded-2xl text-sm font-medium text-red-400/60 hover:text-red-400 transition-all duration-300">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- edit track form -->
    <div id="edit-modal" class="fixed hidden inset-0 z-50 flex items-center justify-center p-4">
        <div class="relative w-full max-w-md bg-white/[0.04] border border-white/[0.05] rounded-[2.5rem] p-10 shadow-2xl backdrop-blur-[30px] overflow-hidden">

            <!-- title -->
            <div class="text-center mb-10">
                <h3 class="text-white/90 font-medium text-2xl mb-2">Edit Track</h3>
                <p class="text-white/40 text-[11px] font-bold uppercase tracking-widest">Update track details</p>
            </div>



            <form id="edit-track-form" enctype="multipart/form-data" class="space-y-5">
                <input type="hidden" id="edit-track-id" name="id">


                <!-- track title input -->
                <div>
                    <label class="block text-[10px] font-bold text-white/20 uppercase tracking-[0.2em] mb-2 ml-1">Track Title</label>
                    <input type="text" id="edit-track-title" name="title" required
                        class="w-full bg-white/[0.03] border border-white/[0.08] rounded-2xl px-5 py-4 text-white text-sm placeholder:text-white/20 focus:outline-none focus:border-emerald-500/30 transition-all"
                        placeholder="Enter title...">
                </div>


                <!-- track genre and bpm input -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-white/20 uppercase tracking-[0.2em] mb-2 ml-1">Genre</label>
                        <input type="text" id="edit-track-genre" name="genre" required
                            class="w-full bg-white/[0.03] border border-white/[0.08] rounded-2xl px-5 py-4 text-white text-sm placeholder:text-white/20 focus:outline-none focus:border-emerald-500/30 transition-all"
                            placeholder="e.g. Techno">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-white/20 uppercase tracking-[0.2em] mb-2 ml-1">BPM</label>
                        <input type="number" id="edit-track-bpm" name="bpm" required
                            class="w-full bg-white/[0.03] border border-white/[0.08] rounded-2xl px-5 py-4 text-white text-sm placeholder:text-white/20 focus:outline-none focus:border-emerald-500/30 transition-all"
                            placeholder="128">
                    </div>
                </div>


                <!-- track file input -->
                <div>
                    <label class="block text-[10px] font-bold text-white/20 uppercase tracking-[0.2em] mb-2 ml-1">Replace Audio (Optional)</label>
                    <div class="relative group">
                        <input type="file" id="edit-track-file" name="audio" accept="audio/mpeg"
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                        <div class="w-full bg-white/[0.02] border border-white/[0.1] border-dashed rounded-2xl px-5 py-4 text-white/30 group-hover:border-emerald-500/50 transition-all flex items-center justify-between">
                            <span id="file-name-display" class="text-sm">Choose new MP3 file...</span>
                            <svg class="w-4 h-4 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M12 4v16m8-8H4" stroke-width="2" stroke-linecap="round" />
                            </svg>
                        </div>
                    </div>
                </div>


                <!-- buttons -->
                <div class="grid grid-cols-2 gap-3 pt-6">
                    <button type="submit"
                        class="w-full px-6 py-4 bg-emerald-500/[0.03] hover:bg-emerald-500/[0.08] border border-emerald-500/10 hover:border-emerald-500/30 rounded-2xl text-sm font-medium text-emerald-400/70 hover:text-emerald-400 transition-all duration-300">
                        Save
                    </button>
                    <button type="button" id="close-edit-modal"
                        class="w-full px-6 py-4 bg-red-500/[0.03] hover:bg-red-500/[0.08] border border-red-500/10 hover:border-red-500/30 rounded-2xl text-sm font-medium text-red-400/60 hover:text-red-400 transition-all duration-300">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>



    <!-- delete track confirmation modal -->
    <div id="custom-confirm" class="fixed hidden inset-0 z-50 flex items-center justify-center p-4">

        <div class="relative w-full max-w-sm bg-white/[0.04] border border-white/[0.05] rounded-[2.5rem] p-10 shadow-2xl backdrop-blur-[30px] overflow-hidden">

            <div class="relative z-10">
                <h3 class="text-[13px] font-bold text-white/30 uppercase tracking-[0.2em] text-center mb-6">
                    Confirm Deletion
                </h3>

                <div class="bg-white/[0.03] border border-white/[0.05] rounded-2xl p-4 mb-10 text-center">
                    <p class="text-xs text-white/40 uppercase tracking-widest mb-1">Track to remove</p>
                    <span id="modal-track-name" class="text-white/90 font-medium text-lg"></span>
                </div>
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
    <!--<div class="absolute -top-40 -left-20 w-[600px] h-[600px] bg-blue-700/20 rounded-full blur-[160px] opacity-70"></div>
    <div class="absolute bottom-[-10%] left-[15%] w-[500px] h-[500px] bg-emerald-700/15 rounded-full blur-[140px]"></div>
    <div class="absolute top-[10%] right-[-10%] w-[500px] h-[500px] bg-purple-600/20 rounded-full blur-[90px] opacity-60"></div>
    -->
    <div class="relative w-full max-w-6xl bg-white/[0.03] border border-white/[0.08] rounded-[2.5rem] p-8 shadow-2xl backdrop-blur-[20px]">


        <div class="flex">
            <div class="mb-8 ml-3 relative">
                <h2 class="text-2xl font-medium text-white/95 tracking-tight">Uploaded tracks</h2>
                <p class="text-[11px] font-bold text-white/40 tracking-[0.25em] uppercase mt-1">List of available tracks</p>
            </div>


            <div class="flex justify-between ml-auto mr-4 items-center mb-6">
                <button id="open-add-modal-btn"
                    class="group p-2 text-gray-400 hover:text-emerald-400 transition-all duration-300 focus:outline-none"
                    title="Add New Track">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-7 h-7 transform group-hover: transition-transform duration-300"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.5"
                        stroke-linecap="round"
                        stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                </button>
            </div>

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
                        <tr id="row-<?= $track->id ?>" class="group transition-all duration-300">
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

                                    <!-- edit button -->
                                    <button
                                        data-id="<?= $track->id ?>"
                                        data-title="<?= htmlspecialchars($track->title) ?>"
                                        data-genre="<?= htmlspecialchars($track->genre) ?>"
                                        data-bpm="<?= $track->bpm ?>"
                                        class="open-edit-modal-btn text-[10px] font-bold text-white/30 uppercase tracking-widest hover:text-white transition-colors duration-300">
                                        Edit
                                    </button>

                                    <!-- delete button -->
                                    <button
                                        data-id="<?= htmlspecialchars($track->id) ?>"
                                        data-name="<?= htmlspecialchars($track->title) ?>"
                                        class="open-modal-btn group/del relative flex items-center 
                                        justify-center w-7 h-7 rounded-full border border-red-500/40 bg-red-950/20 shadow-[0_0_10px_rgba(239,68,68,0.2)] transition-all duration-300 hover:border-red-500 
                                        hover:bg-red-500 hover:shadow-[0_0_15px_rgba(239,68,68,0.5)] focus:outline-none" title="Remove">
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
    //     DELETE MODAL     //


    let pendingId = null;
    let trackName = null;

    const modal = document.getElementById('custom-confirm');
    const okBtn = document.getElementById('ok-btn');
    const cancelBtn = document.getElementById('cancel-btn');


    document.querySelectorAll('.open-modal-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            pendingId = btn.getAttribute('data-id');
            trackName = btn.getAttribute('data-name');

            document.getElementById('modal-track-name').innerText = '"' + pendingId + " " + trackName + '"';
            modal.classList.remove('hidden');
        });
    });


    cancelBtn.addEventListener('click', () => {
        modal.classList.add('hidden');
        pendingId = null;
        trackName = null;
    });

    okBtn.addEventListener('click', () => {
        if (pendingId) {
            fetch('api/delete_track.php?id=' + pendingId)
                .then(response => response.json())
                .then(data => {
                    if (data.success === true) {
                        const row = document.getElementById(`row-${pendingId}`);
                        if (row) {
                            row.remove();
                        }
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



    //     EDIT MODAL     //


    const editModal = document.getElementById('edit-modal');
    const closeEditBtn = document.getElementById('close-edit-modal');


    document.querySelectorAll('.open-edit-modal-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.getAttribute('data-id');
            const title = btn.getAttribute('data-title');
            const genre = btn.getAttribute('data-genre');
            const bpm = btn.getAttribute('data-bpm');

            document.getElementById('edit-track-id').value = id;
            document.getElementById('edit-track-title').value = title;
            document.getElementById('edit-track-genre').value = genre;
            document.getElementById('edit-track-bpm').value = bpm;

            document.getElementById('file-name-display').innerText = "Keep current or upload new...";

            editModal.classList.remove('hidden');

        });

    });


    closeEditBtn.addEventListener('click', () => {
        editModal.classList.add('hidden');
    });

    // function to update file input placeholder to name of the inserted file 
    document.getElementById('edit-track-file').addEventListener('change', function() {
        const fileName = this.files[0] ? this.files[0].name : "Choose new MP3 file...";
        document.getElementById('file-name-display').innerText = fileName;
    });


    const editForm = document.getElementById('edit-track-form');

    editForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        const title = formData.get('title').trim();
        const genre = formData.get('genre').trim();
        const bpm = formData.get('bpm');

        if (title === "" || genre === "" || !bpm || bpm <= 0) {
            return;
        }

        fetch('api/update_track.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const id = formData.get('id');
                    const row = document.getElementById(`row-${id}`);

                    if (row) {
                        row.querySelector('td:nth-child(2)').innerText = formData.get('title');
                        row.querySelector('td:nth-child(3)').innerText = formData.get('genre');
                        row.querySelector('td:nth-child(4) span').innerText = formData.get('bpm');

                        const editBtn = row.querySelector('.open-edit-modal-btn');
                        editBtn.setAttribute('data-title', formData.get('title'));
                        editBtn.setAttribute('data-genre', formData.get('genre'));
                        editBtn.setAttribute('data-bpm', formData.get('bpm'));
                    }
                    editModal.classList.add('hidden');
                } else {
                    console.log("Error: " + data.message);
                }
            })
            .catch(error => console.error("Error: ", error));
    });
</script>