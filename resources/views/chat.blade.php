<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Chat Admin - ASY-PAY</title>

    <!-- Tailwind CDN (untuk styling cepat) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        /* sedikit custom agar rapi di berbagai layout */
        html,body { height:100%; }
        body { font-family: Inter, ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial; }
        /* scroll area gaya */
        .chat-scroll { scrollbar-width: thin; scrollbar-color: rgba(0,0,0,0.12) transparent; }
        .chat-scroll::-webkit-scrollbar { height: 10px; width: 10px; }
        .chat-scroll::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.12); border-radius: 10px; }
        .bubble { word-break: break-word; white-space: pre-wrap; }
        /* small responsive */
        @media (max-width:640px) {
            .max-w-md { max-width: 100% !important; }
        }
    </style>
</head>
<body class="bg-green-50 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-2xl bg-white rounded-3xl shadow-xl flex flex-col p-4">
        <header class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo.jpg') }}" alt="Asy-Pay" class="w-50 h-10 rounded-md object-cover shadow-sm bg-white">
                <div>
                    <h1 class="text-2xl font-extrabold text-green-700">Chat Admin — ASY-PAY</h1>
                    <p class="text-sm text-gray-500">Tanya soal SPP, pembayaran, riwayat, atau bantuan lain.</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <!-- tombol quick actions kecil di header -->
                <button id="clearBtn" class="px-3 py-2 bg-red-50 text-red-600 rounded-md hover:bg-red-100">Bersihkan</button>
            </div>
        </header>

        <!-- Area chat -->
        <div id="reply" class="flex-1 overflow-y-auto mb-4 p-4 space-y-4 bg-green-50 rounded-2xl border border-green-200 chat-scroll" style="height: 420px;">
            <p class="text-gray-500 text-center mt-10">Belum ada pesan — gunakan tombol cepat atau tulis pesan untuk memulai.</p>
        </div>

        <!-- Quick question buttons (banyak & relevan untuk ASY-PAY) -->
        <div class="mb-4 grid gap-2 grid-cols-2 sm:grid-cols-3 lg:grid-cols-4">
            <!-- Core payments -->
            <button type="button" class="quick-btn px-3 py-2 bg-white text-green-800 border border-green-200 rounded-full text-sm hover:bg-green-50 transition">Berapa biaya SPP per bulan?</button>
            <button type="button" class="quick-btn px-3 py-2 bg-white text-green-800 border border-green-200 rounded-full text-sm hover:bg-green-50 transition">Metode pembayaran apa saja?</button>
            <button type="button" class="quick-btn px-3 py-2 bg-white text-green-800 border border-green-200 rounded-full text-sm hover:bg-green-50 transition">Bagaimana cara konfirmasi transfer?</button>
            <button type="button" class="quick-btn px-3 py-2 bg-white text-green-800 border border-green-200 rounded-full text-sm hover:bg-green-50 transition">Apa itu Midtrans?</button>

            <!-- Account & profile -->
            <button class="quick-btn px-3 py-2 bg-white text-green-800 border border-green-200 rounded-full text-sm">Bagaimana ubah profil?</button>
            <button class="quick-btn px-3 py-2 bg-white text-green-800 border border-green-200 rounded-full text-sm">Ganti password</button>
            <button class="quick-btn px-3 py-2 bg-white text-green-800 border border-green-200 rounded-full text-sm">Logout / keluar</button>

            <!-- Transactions & receipts -->
            <button class="quick-btn px-3 py-2 bg-white text-green-800 border border-green-200 rounded-full text-sm">Cetak kwitansi / struk</button>
            <button class="quick-btn px-3 py-2 bg-white text-green-800 border border-green-200 rounded-full text-sm">Lihat riwayat pembayaran</button>
            <button class="quick-btn px-3 py-2 bg-white text-green-800 border border-green-200 rounded-full text-sm">Generate token pembayaran</button>

            <!-- School info -->
            <button class="quick-btn px-3 py-2 bg-white text-green-800 border border-green-200 rounded-full text-sm">Daftar kelas & tahun ajaran</button>
            <button class="quick-btn px-3 py-2 bg-white text-green-800 border border-green-200 rounded-full text-sm">Ada denda keterlambatan?</button>

            <!-- Admin contact -->
            <button class="quick-btn px-3 py-2 bg-white text-green-800 border border-green-200 rounded-full text-sm">Kontak admin / WA</button>
            <button class="quick-btn px-3 py-2 bg-white text-green-800 border border-green-200 rounded-full text-sm">Jam operasional kantor</button>

            <!-- Extra -->
            <button class="quick-btn px-3 py-2 bg-white text-green-800 border border-green-200 rounded-full text-sm">Cara bayar tunai di kasir</button>
            <button class="quick-btn px-3 py-2 bg-white text-green-800 border border-green-200 rounded-full text-sm">Refund / komplain pembayaran</button>

            <button class="quick-btn px-3 py-2 bg-white text-green-800 border border-green-200 rounded-full text-sm">Diskon / beasiswa</button>
            <button class="quick-btn px-3 py-2 bg-white text-green-800 border border-green-200 rounded-full text-sm">Apa saja kategori pembayaran?</button>
        </div>

        <!-- Form input pesan -->
        <form id="chatForm" class="flex gap-2 items-center" onsubmit="return false;">
            @csrf
            <input type="text" id="message" name="message" placeholder="Tulis pesan... (mis. 'Bagaimana cara bayar SPP?')" required
                class="flex-1 px-4 py-3 border border-green-300 rounded-full focus:outline-none focus:ring-2 focus:ring-green-500 bg-white">
            <button id="sendBtn" type="button"
                class="px-5 py-3 bg-green-600 text-white rounded-full hover:bg-green-700 transition font-semibold">Kirim</button>
        </form>
    </div>

    <!-- axios optional; tidak perlu kalau tidak akan call API -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
    (function () {
        document.addEventListener('DOMContentLoaded', function () {
            // elemen
            const replyDiv = document.getElementById('reply');
            const sendBtn = document.getElementById('sendBtn');
            const messageInput = document.getElementById('message');
            const quickBtns = document.querySelectorAll('.quick-btn');
            const clearBtn = document.getElementById('clearBtn');

            // escape simple
            function escapeHtml(str) {
                if (!str) return '';
                return String(str)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#39;');
            }

            // tambahkan pesan ke UI
            function addMessage(sender, htmlContent) {
                // wrapper baris
                const row = document.createElement('div');
                row.className = 'flex ' + (sender === 'user' ? 'justify-end' : 'justify-start');

                const bubble = document.createElement('div');
                bubble.className = 'bubble px-4 py-2 rounded-2xl max-w-xl break-words';

                if (sender === 'user') {
                    bubble.classList.add('bg-green-600', 'text-white', 'rounded-br-none', 'shadow');
                } else if (sender === 'system') {
                    bubble.classList.add('bg-gray-100', 'text-gray-800', 'rounded-bl-none');
                } else {
                    // admin/bot
                    bubble.classList.add('bg-white', 'text-gray-800', 'rounded-bl-none', 'shadow-sm', 'border');
                }

                // kita percayakan htmlContent sudah aman (escape atau kontrol)
                bubble.innerHTML = htmlContent;

                row.appendChild(bubble);
                replyDiv.appendChild(row);
                // scroll
                replyDiv.scrollTop = replyDiv.scrollHeight;
            }

            // hapus placeholder
            function removePlaceholderIfAny() {
                const p = replyDiv.querySelector('p');
                if (p && p.textContent && p.textContent.toLowerCase().includes('belum ada pesan')) {
                    p.remove();
                }
            }

            // Bot reply logic (banyak keyword relevan untuk ASY-PAY)
            function botReply(rawMessage) {
                if (!rawMessage) return "Admin tidak menerima pesan kosong.";
                const message = rawMessage.toLowerCase().trim();

                // exact / priority rules
                if (message === 'halo' || message === 'hai' || message.includes('assalam')) {
                    return "Halo! 👋 Ada yang bisa admin bantu seputar <strong>SPP</strong>, <strong>pembayaran</strong>, <strong>riwayat</strong>, atau bantuan lainnya?";
                }

                // SPP & biaya
                if (message.includes('spp') || message.includes('biaya spp') || message.includes('harga spp')) {
                    return "Untuk SPP saat ini: <strong>Rp 500.000</strong> per bulan. Untuk informasi lebih detail (mis. diskon/angsuran) silakan sebutkan nama siswa atau kelas.";
                }

                // pembayaran list
                if (message.includes('pembayaran apa') || message.includes('ada pembayaran') || message.includes('jenis pembayaran')) {
                    return "Pembayaran yang tersedia: <ul><li>SPP</li><li>Seragam</li><li>Buku</li><li>Kegiatan</li><li>Donasi / Lainnya</li></ul>";
                }

                // metode bayar
                if (message.includes('metode') || message.includes('cara bayar') || message.includes('lewat')) {
                    return "Kamu bisa membayar melalui: <ul><li>Transfer Bank (virtual account)</li><li>E-wallet: OVO, DANA, GoPay</li><li>Midtrans (untuk pembayaran online)</li><li>Bayar tunai di kasir sekolah</li></ul>";
                }

                // konfirmasi transfer
                if (message.includes('konfirmasi') || message.includes('konfirmasi transfer') || message.includes('upload bukti')) {
                    return "Untuk konfirmasi transfer: upload bukti transfer pada halaman detail pembayaran atau hubungi kasir dengan menyertakan <strong>invoice/nomor transaksi</strong> dan bukti pengiriman.";
                }

                // midtrans / token / generate token
                if (message.includes('midtrans') || message.includes('token') || message.includes('generate token')) {
                    return "Midtrans adalah payment gateway. Untuk transaksi via Midtrans, klik tombol 'Bayar' pada halaman tagihan lalu ikuti instruksi. Jika butuh token/va, gunakan fitur 'Generate Token' di halaman pembayaran.";
                }

                // invoice / kwitansi
                if (message.includes('kwitansi') || message.includes('struk') || message.includes('cetak') || message.includes('cetak kwitansi')) {
                    return "Kamu bisa mencetak kwitansi dari halaman Riwayat -> pilih transaksi -> klik 'Cetak / Download PDF'. Jika tidak muncul, pastikan transaksi sudah berstatus 'Lunas'.";
                }

                // riwayat
                if (message.includes('riwayat') || message.includes('history') || message.includes('transaksi')) {
                    return "Untuk melihat riwayat pembayaran: buka menu <strong>Riwayat</strong> (atau klik profil -> Riwayat). Kamu bisa filter berdasarkan periode, kelas, atau jenis pembayaran.";
                }

                // refund / komplain
                if (message.includes('refund') || message.includes('komplain') || message.includes('protes')) {
                    return "Untuk refund atau komplain: hubungi admin via WhatsApp (+62 812-XXX-XXXX) atau gunakan fitur 'Laporkan' pada halaman transaksi. Sertakan bukti pembayaran dan alasan refund.";
                }

                // denda / keterlambatan
                if (message.includes('denda') || message.includes('keterlambatan') || message.includes('telat')) {
                    return "Keterlambatan bisa dikenakan denda sesuai kebijakan sekolah. Silakan cek ketentuan pada <strong>Peraturan Sekolah</strong> atau tanyakan ke bagian kasir untuk jumlah tepatnya.";
                }

                // kasir / bayar tunai
                if (message.includes('kasir') || message.includes('tunai') || message.includes('bayar tunai')) {
                    return "Jika ingin bayar tunai, datang ke <strong>Kasir Sekolah</strong> pada jam operasional. Serahkan invoice atau sebutkan nama & NIS untuk memproses pembayaran.";
                }

                // profil / ubah profil / pass
                if (message.includes('profil') || message.includes('ubah profil') || message.includes('ganti profile') || message.includes('password') || message.includes('ganti password')) {
                    return "Untuk ubah profil atau password: buka menu Profil -> Edit. Untuk keamanan, ganti password minimal 8 karakter. Jika lupa password, gunakan fitur 'Lupa Password' pada halaman login.";
                }

                // kelas / tahun ajaran
                if (message.includes('kelas') || message.includes('tahun ajaran') || message.includes('tahun')) {
                    return "Informasi kelas dan tahun ajaran dapat dilihat di Profil Siswa atau di halaman manajemen siswa (Admin). Sebutkan kelas/tahun yang ingin dicek untuk detail.";
                }

                // kontak admin
                if (message.includes('kontak admin') || message.includes('hubungi admin') || message.includes('whatsapp') || message.includes('wa')) {
                    return "Kontak Admin: <strong>Whatsapp</strong> +62 857-999-25405 (Senin–Jumat 08:00–16:00). Untuk masalah mendesak, tuliskan 'URGENT' di pesanmu.";
                }

                // kategori pembayaran
                if (message.includes('kategori')) {
                    return "Kategori pembayaran membantu mengelompokkan tagihan (mis. SPP, Seragam, Buku). Untuk menambah/ubah kategori, gunakan menu <strong>Kategori</strong> (Admin).";
                }

                // tambah siswa / manajemen siswa
                if (message.includes('tambah siswa') || message.includes('daftar siswa') || message.includes('ubah siswa')) {
                    return "Untuk menambah siswa: Admin -> Menu Siswa -> Tambah Siswa. Pastikan mengisi NIS, nama, kelas, dan tahun ajaran. Jika kamu bukan admin, minta bantuan admin sekolah.";
                }

                // laporan export
                if (message.includes('laporan') || message.includes('export excel') || message.includes('export pdf')) {
                    return "Laporan dapat di-export dari menu Laporan. Pilih rentang tanggal lalu klik 'Export Excel' atau 'Export PDF'.";
                }

                // refund specifics
                if (message.includes('refund') || message.includes('kembalikan') || message.includes('dikembalikan')) {
                    return "Proses refund butuh verifikasi. Hubungi admin dengan bukti pembayaran dan alasan refund. Estimasi proses 3–7 hari kerja.";
                }

                // fallback: balasan generik tapi aman dan sopan
                const fallbackTemplates = [
                    'Terima kasih, pesan kamu: "<em>' + escapeHtml(rawMessage) + '</em>" sudah kami terima. Admin akan menindaklanjuti.',
                    'Pesan: "<em>' + escapeHtml(rawMessage) + '</em>" tercatat. Mohon tunggu konfirmasi dari admin.',
                    'Kami menerima pesanmu: "<em>' + escapeHtml(rawMessage) + '</em>". Nanti admin akan menghubungi kembali.'
                ];
                return fallbackTemplates[Math.floor(Math.random() * fallbackTemplates.length)];
            }

            // submit message
            function submitMessage() {
                const text = messageInput.value.trim();
                if (!text) return;

                removePlaceholderIfAny();
                // tampilkan user message (escaped)
                addMessage('user', escapeHtml(text));

                // show typing / system bubble
                addMessage('system', '<em>Sedang mengetik...</em>');

                // simulate typing delay
                setTimeout(() => {
                    // remove last system typing (search from end)
                    for (let i = replyDiv.children.length - 1; i >= 0; i--) {
                        const row = replyDiv.children[i];
                        if (row && row.querySelector && row.innerText.toLowerCase().includes('sedang mengetik')) {
                            row.remove();
                            break;
                        }
                    }

                    // get answer
                    const answer = botReply(text);

                    // admin message (allow safe HTML from botReply)
                    addMessage('bot', answer);

                }, 700 + Math.random() * 600); // randomize delay a bit

                messageInput.value = '';
                messageInput.focus();
            }

            // events
            sendBtn.addEventListener('click', (e) => {
                e.preventDefault();
                submitMessage();
            });

            messageInput.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    submitMessage();
                }
            });

            // quick buttons
            quickBtns.forEach(btn => {
                btn.addEventListener('click', function () {
                    // set value and submit
                    const txt = (this.textContent || this.innerText || '').trim();
                    messageInput.value = txt;
                    submitMessage();
                });
            });

            // clear conversation
            clearBtn?.addEventListener('click', function () {
                replyDiv.innerHTML = '<p class="text-gray-500 text-center mt-10">Belum ada pesan — gunakan tombol cepat atau tulis pesan untuk memulai.</p>';
            });

            // helper: if incoming error, show safe bubble
            function showError(msg) {
                addMessage('system', '<strong style="color:#b91c1c">Error:</strong> ' + escapeHtml(msg));
            }

            // init: optional greeting
            (function initGreeting() {
                // only show if empty
                const first = document.createElement('div');
                first.className = 'flex justify-start';
                const bubble = document.createElement('div');
                bubble.className = 'bubble px-4 py-2 rounded-2xl max-w-xl break-words bg-white text-gray-800 rounded-bl-none shadow-sm border';
                bubble.innerHTML = 'Halo! 👋 Saya asisten admin ASY-PAY. Coba pilih pertanyaan cepat atau ketik pertanyaanmu (mis. "Berapa biaya SPP?").';
                first.appendChild(bubble);
                replyDiv.appendChild(first);
            })();

        }); // DOMContentLoaded
    })();
    </script>
</body>
</html>