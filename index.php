<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Manajemen Blog (CMS)</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #2e3a47;
            --success: #3bb54a;
            --info: #3498db;
            --danger: #e74c3c;
            --bg: #f4f6f9;
            --sidebar: #ffffff;
            --text-dark: #333;
            --text-muted: #888;
            --border: #e0e6ed;
            --radius-md: 8px;
            --radius-sm: 4px;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
        body { background-color: var(--bg); color: var(--text-dark); display: flex; flex-direction: column; height: 100vh; overflow: hidden; }

        header {
            background-color: var(--primary); color: white; padding: 15px 25px;
            display: flex; align-items: center; gap: 15px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); z-index: 10;
        }
        header .icon { font-size: 1.5rem; background: rgba(255,255,255,0.1); padding: 8px; border-radius: var(--radius-sm); }
        header h1 { font-size: 1.2rem; font-weight: 600; margin:0;}
        header p { font-size: 0.8rem; color: #aaa; margin:0;}

        .wrapper { display: flex; flex: 1; overflow: hidden; }

        aside { width: 250px; background: var(--sidebar); border-right: 1px solid var(--border); padding: 20px 0; overflow-y: auto; }
        .menu-title { font-size: 0.75rem; text-transform: uppercase; color: var(--text-muted); font-weight: 600; letter-spacing: 1px; padding: 0 20px 10px; }
        nav { display: flex; flex-direction: column; }
        nav a {
            padding: 12px 20px; text-decoration: none; color: var(--text-dark); font-size: 0.9rem;
            display: flex; align-items: center; gap: 10px; border-left: 3px solid transparent; transition: all 0.3s ease;
        }
        nav a:hover { background: #f8f9fa; }
        nav a.active { background: #eef8ef; color: var(--success); border-left-color: var(--success); font-weight: 500; }
        nav a i { width: 20px; text-align: center; }

        main { flex: 1; padding: 30px; overflow-y: auto; }
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .page-header h2 { font-size: 1.3rem; font-weight: 600; }
        .btn { 
            padding: 8px 15px; border: none; border-radius: var(--radius-sm); font-size: 0.85rem; font-weight: 500;
            cursor: pointer; transition: all 0.2s ease; display: inline-flex; align-items: center; gap: 6px;
            color: #fff; text-decoration: none;
        }
        .btn-success { background: var(--success); }
        .btn-success:hover { background: #32993f; }
        .btn-info { background: var(--info); }
        .btn-info:hover { background: #2980b9; }
        .btn-danger { background: var(--danger); }
        .btn-danger:hover { background: #c0392b; }
        .btn-secondary { background: #bdc3c7; color: #333; }
        .btn-secondary:hover { background: #95a5a6; }
        .btn-sm { padding: 5px 10px; font-size: 0.75rem; }

        .card { background: white; border-radius: var(--radius-md); box-shadow: 0 4px 6px rgba(0,0,0,0.03); overflow: hidden; border: 1px solid var(--border); }
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th { background: #f8f9fa; padding: 15px; font-weight: 600; font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; border-bottom: 2px solid var(--border); }
        td { padding: 15px; font-size: 0.9rem; border-bottom: 1px solid var(--border); vertical-align: middle; }
        tr:last-child td { border-bottom: none; }
        tr:hover { background: #fbfbfc; }
        
        .avatar { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; background: #eee; }
        .thumb { width: 60px; height: 40px; border-radius: var(--radius-sm); object-fit: cover; background: #eee; }

        .badge { background: #e0f2fe; color: #0284c7; padding: 4px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: 500; }

        .modal-overlay { 
            position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); 
            display: none; align-items: center; justify-content: center; z-index: 1000; opacity: 0; transition: opacity 0.3s ease;
        }
        .modal-overlay.show { display: flex; opacity: 1; }
        .modal { background: white; width: 500px; max-width: 90%; border-radius: var(--radius-md); box-shadow: 0 10px 30px rgba(0,0,0,0.2); transform: translateY(-20px); transition: transform 0.3s ease; }
        .modal-overlay.show .modal { transform: translateY(0); }
        .modal-header { padding: 20px; border-bottom: 1px solid var(--border); font-size: 1.1rem; font-weight: 600; display: flex; justify-content: space-between; align-items: center; }
        .modal-body { padding: 20px; max-height: 70vh; overflow-y: auto;}
        .modal-footer { padding: 15px 20px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; gap: 10px; background: #f8f9fa; border-radius: 0 0 var(--radius-md) var(--radius-md); }
        
        .modal-confirm .modal { width: 400px; text-align: center; }
        .modal-confirm .modal-body { padding: 30px 20px; }
        .icon-trash { font-size: 3rem; color: var(--danger); background: #fceceb; width: 80px; height: 80px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 20px; }
        .modal-confirm p { color: var(--text-muted); font-size: 0.9rem; margin-top: 5px; }

        .form-group { margin-bottom: 15px; }
        .form-row { display: flex; gap: 15px; }
        .form-row .form-group { flex: 1; }
        label { display: block; font-size: 0.85rem; font-weight: 500; margin-bottom: 8px; color: var(--text-dark); }
        input[type="text"], input[type="password"], select, textarea {
            width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: var(--radius-sm); font-size: 0.9rem; outline: none; transition: border 0.3s;
        }
        input:focus, select:focus, textarea:focus { border-color: var(--success); box-shadow: 0 0 0 3px rgba(59,181,74,0.1); }
        textarea { resize: vertical; min-height: 100px; }
        .file-input { border: 1px solid #ccc; padding: 5px; border-radius: var(--radius-sm); width: 100%; background: #fafafa; font-size: 0.85rem;}

        .toast { position: fixed; bottom: 20px; right: 20px; background: #333; color: white; padding: 12px 20px; border-radius: var(--radius-sm); box-shadow: 0 4px 12px rgba(0,0,0,0.15); transform: translateY(100px); opacity: 0; transition: all 0.3s ease; z-index: 2000; }
        .toast.show { transform: translateY(0); opacity: 1; }
        .toast.error { background: var(--danger); }
        .toast.success { background: var(--success); }

        section { display: none; animation: fadeIn 0.4s ease; }
        section.active { display: block; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body>

    <header>
        <div class="icon"><i class="fa-solid fa-table-columns"></i></div>
        <div>
            <h1>Sistem Manajemen Blog (CMS)</h1>
            <p>Blog Keren</p>
        </div>
    </header>

    <div class="wrapper">
        <aside>
            <div class="menu-title">Menu Utama</div>
            <nav id="navMenu">
                <a href="#" data-target="sec-penulis" class="active"><i class="fa-regular fa-user"></i> Kelola Penulis</a>
                <a href="#" data-target="sec-artikel"><i class="fa-regular fa-file-lines"></i> Kelola Artikel</a>
                <a href="#" data-target="sec-kategori"><i class="fa-regular fa-folder-open"></i> Kelola Kategori</a>
            </nav>
        </aside>

        <main>
            <section id="sec-penulis" class="active">
                <div class="page-header">
                    <h2>Data Penulis</h2>
                    <button class="btn btn-success" onclick="openModalPenulis()"><i class="fa-solid fa-plus"></i> Tambah Penulis</button>
                </div>
                <div class="card">
                    <table>
                        <thead>
                            <tr>
                                <th>Foto</th>
                                <th>Nama</th>
                                <th>Username</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="table-penulis">
                            <tr><td colspan="4" style="text-align:center;">Memuat data...</td></tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <section id="sec-artikel">
                <div class="page-header">
                    <h2>Data Artikel</h2>
                    <button class="btn btn-success" onclick="openModalArtikel()"><i class="fa-solid fa-plus"></i> Tambah Artikel</button>
                </div>
                <div class="card">
                    <table>
                        <thead>
                            <tr>
                                <th>Gambar</th>
                                <th>Judul</th>
                                <th>Kategori</th>
                                <th>Penulis</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="table-artikel">
                            <tr><td colspan="6" style="text-align:center;">Memuat data...</td></tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <section id="sec-kategori">
                <div class="page-header">
                    <h2>Data Kategori Artikel</h2>
                    <button class="btn btn-success" onclick="openModalKategori()"><i class="fa-solid fa-plus"></i> Tambah Kategori</button>
                </div>
                <div class="card">
                    <table>
                        <thead>
                            <tr>
                                <th>Nama Kategori</th>
                                <th>Keterangan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="table-kategori">
                            <tr><td colspan="3" style="text-align:center;">Memuat data...</td></tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>


    <div class="modal-overlay" id="modal-penulis">
        <div class="modal">
            <div class="modal-header">
                <span id="title-penulis">Tambah Penulis</span>
            </div>
            <form id="form-penulis" onsubmit="submitPenulis(event)">
                <div class="modal-body">
                    <input type="hidden" name="id" id="penulis_id">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Nama Depan</label>
                            <input type="text" name="nama_depan" id="penulis_nama_depan" required>
                        </div>
                        <div class="form-group">
                            <label>Nama Belakang</label>
                            <input type="text" name="nama_belakang" id="penulis_nama_belakang" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="user_name" id="penulis_user_name" required>
                    </div>
                    <div class="form-group">
                        <label id="lbl-penulis-password">Password</label>
                        <input type="password" name="password" id="penulis_password">
                    </div>
                    <div class="form-group">
                        <label id="lbl-penulis-foto">Foto Profil</label>
                        <input type="file" name="foto" id="penulis_foto" class="file-input" accept=".jpg,.png,.jpeg,.gif">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('modal-penulis')">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal-overlay" id="modal-kategori">
        <div class="modal">
            <div class="modal-header">
                <span id="title-kategori">Tambah Kategori</span>
            </div>
            <form id="form-kategori" onsubmit="submitKategori(event)">
                <div class="modal-body">
                    <input type="hidden" name="id" id="kategori_id">
                    <div class="form-group">
                        <label>Nama Kategori</label>
                        <input type="text" name="nama_kategori" id="kategori_nama_kategori" required>
                    </div>
                    <div class="form-group">
                        <label>Keterangan</label>
                        <textarea name="keterangan" id="kategori_keterangan"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('modal-kategori')">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal-overlay" id="modal-artikel">
        <div class="modal">
            <div class="modal-header">
                <span id="title-artikel">Tambah Artikel</span>
            </div>
            <form id="form-artikel" onsubmit="submitArtikel(event)">
                <div class="modal-body">
                    <input type="hidden" name="id" id="artikel_id">
                    <div class="form-group">
                        <label>Judul</label>
                        <input type="text" name="judul" id="artikel_judul" placeholder="Judul artikel..." required>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Penulis</label>
                            <select name="id_penulis" id="artikel_id_penulis" required></select>
                        </div>
                        <div class="form-group">
                            <label>Kategori</label>
                            <select name="id_kategori" id="artikel_id_kategori" required></select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Isi Artikel</label>
                        <textarea name="isi" id="artikel_isi" placeholder="Tulis isi artikel di sini..." required></textarea>
                    </div>
                    <div class="form-group">
                        <label id="lbl-artikel-gambar">Gambar</label>
                        <input type="file" name="gambar" id="artikel_gambar" class="file-input" accept=".jpg,.png,.jpeg,.gif">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('modal-artikel')">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal-overlay modal-confirm" id="modal-hapus">
        <div class="modal">
            <div class="modal-body">
                <div class="icon-trash"><i class="fa-regular fa-trash-can"></i></div>
                <h3 style="margin-bottom: 5px;">Hapus data ini?</h3>
                <p>Data yang dihapus tidak dapat dikembalikan.</p>
                <div style="margin-top: 25px; display:flex; justify-content:center; gap:10px;">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('modal-hapus')">Batal</button>
                    <button type="button" class="btn btn-danger" id="btn-confirm-delete">Ya, Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <div id="toast" class="toast">Notifikasi!</div>

    <script>
        const navLinks = document.querySelectorAll('#navMenu a');
        const sections = document.querySelectorAll('main section');
        
        navLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                navLinks.forEach(l => l.classList.remove('active'));
                sections.forEach(s => s.classList.remove('active'));
                
                link.classList.add('active');
                const target = document.getElementById(link.dataset.target);
                target.classList.add('active');
                
                if(link.dataset.target === 'sec-penulis') loadPenulis();
                if(link.dataset.target === 'sec-kategori') loadKategori();
                if(link.dataset.target === 'sec-artikel') loadArtikel();
            });
        });

        function showToast(msg, type='success') {
            const toast = document.getElementById('toast');
            toast.textContent = msg;
            toast.className = 'toast show ' + type;
            setTimeout(() => { toast.classList.remove('show'); }, 3000);
        }
        
        function openModal(id) { document.getElementById(id).classList.add('show'); }
        function closeModal(id) { document.getElementById(id).classList.remove('show'); }

        async function loadPenulis() {
            try {
                const res = await fetch('ambil_penulis.php');
                const json = await res.json();
                const tbody = document.getElementById('table-penulis');
                tbody.innerHTML = '';
                if(json.data.length === 0) tbody.innerHTML = '<tr><td colspan="4" style="text-align:center">Belum ada data</td></tr>';
                json.data.forEach(p => {
                    tbody.innerHTML += `
                        <tr>
                            <td><img src="uploads_penulis/${p.foto}" class="avatar" alt="Foto"></td>
                            <td>${p.nama_depan} ${p.nama_belakang}</td>
                            <td><span class="badge">${p.user_name}</span></td>
                            <td>
                                <button class="btn btn-sm btn-info" onclick="editPenulis(${p.id})">Edit</button>
                                <button class="btn btn-sm btn-danger" onclick="hapusData('penulis', ${p.id})">Hapus</button>
                            </td>
                        </tr>
                    `;
                });
            } catch(e) { console.error(e); }
        }

        function openModalPenulis() {
            document.getElementById('form-penulis').reset();
            document.getElementById('penulis_id').value = '';
            document.getElementById('title-penulis').innerText = 'Tambah Penulis';
            document.getElementById('penulis_password').required = true;
            document.getElementById('lbl-penulis-password').innerText = 'Password';
            document.getElementById('lbl-penulis-foto').innerText = 'Foto Profil';
            openModal('modal-penulis');
        }

        async function editPenulis(id) {
            const res = await fetch(`ambil_satu_penulis.php?id=${id}`);
            const json = await res.json();
            if(json.status === 'success') {
                const d = json.data;
                document.getElementById('form-penulis').reset();
                document.getElementById('penulis_id').value = d.id;
                document.getElementById('penulis_nama_depan').value = d.nama_depan;
                document.getElementById('penulis_nama_belakang').value = d.nama_belakang;
                document.getElementById('penulis_user_name').value = d.user_name;
                document.getElementById('title-penulis').innerText = 'Edit Penulis';
                document.getElementById('penulis_password').required = false;
                document.getElementById('lbl-penulis-password').innerText = 'Password Baru (kosongkan jika tidak diganti)';
                document.getElementById('lbl-penulis-foto').innerText = 'Foto Profil (kosongkan jika tidak diganti)';
                openModal('modal-penulis');
            }
        }

        async function submitPenulis(e) {
            e.preventDefault();
            const form = document.getElementById('form-penulis');
            const data = new FormData(form);
            const id = document.getElementById('penulis_id').value;
            const url = id ? 'update_penulis.php' : 'simpan_penulis.php';
            
            const res = await fetch(url, { method: 'POST', body: data });
            const json = await res.json();
            showToast(json.message, json.status);
            if(json.status === 'success') { closeModal('modal-penulis'); loadPenulis(); }
        }

        async function loadKategori() {
            try {
                const res = await fetch('ambil_kategori.php');
                const json = await res.json();
                const tbody = document.getElementById('table-kategori');
                tbody.innerHTML = '';
                if(json.data.length === 0) tbody.innerHTML = '<tr><td colspan="3" style="text-align:center">Belum ada data</td></tr>';
                json.data.forEach(k => {
                    tbody.innerHTML += `
                        <tr>
                            <td><span class="badge">${k.nama_kategori}</span></td>
                            <td>${k.keterangan || '-'}</td>
                            <td>
                                <button class="btn btn-sm btn-info" onclick="editKategori(${k.id})">Edit</button>
                                <button class="btn btn-sm btn-danger" onclick="hapusData('kategori', ${k.id})">Hapus</button>
                            </td>
                        </tr>
                    `;
                });
            } catch(e) { console.error(e); }
        }

        function openModalKategori() {
            document.getElementById('form-kategori').reset();
            document.getElementById('kategori_id').value = '';
            document.getElementById('title-kategori').innerText = 'Tambah Kategori';
            openModal('modal-kategori');
        }

        async function editKategori(id) {
            const res = await fetch(`ambil_satu_kategori.php?id=${id}`);
            const json = await res.json();
            if(json.status === 'success') {
                document.getElementById('kategori_id').value = json.data.id;
                document.getElementById('kategori_nama_kategori').value = json.data.nama_kategori;
                document.getElementById('kategori_keterangan').value = json.data.keterangan;
                document.getElementById('title-kategori').innerText = 'Edit Kategori';
                openModal('modal-kategori');
            }
        }

        async function submitKategori(e) {
            e.preventDefault();
            const form = document.getElementById('form-kategori');
            const data = new FormData(form);
            const id = document.getElementById('kategori_id').value;
            const url = id ? 'update_kategori.php' : 'simpan_kategori.php';
            
            const res = await fetch(url, { method: 'POST', body: data });
            const json = await res.json();
            showToast(json.message, json.status);
            if(json.status === 'success') { closeModal('modal-kategori'); loadKategori(); }
        }

        async function loadDropdowns() {
            const resK = await fetch('ambil_kategori.php');
            const jsonK = await resK.json();
            let optK = '<option value="">Pilih Kategori</option>';
            if(jsonK.data) jsonK.data.forEach(k => optK += `<option value="${k.id}">${k.nama_kategori}</option>`);
            document.getElementById('artikel_id_kategori').innerHTML = optK;

            const resP = await fetch('ambil_penulis.php');
            const jsonP = await resP.json();
            let optP = '<option value="">Pilih Penulis</option>';
            if(jsonP.data) jsonP.data.forEach(p => optP += `<option value="${p.id}">${p.nama_depan} ${p.nama_belakang}</option>`);
            document.getElementById('artikel_id_penulis').innerHTML = optP;
        }

        async function loadArtikel() {
            try {
                const res = await fetch('ambil_artikel.php');
                const json = await res.json();
                const tbody = document.getElementById('table-artikel');
                tbody.innerHTML = '';
                if(json.data.length === 0) tbody.innerHTML = '<tr><td colspan="6" style="text-align:center">Belum ada data</td></tr>';
                json.data.forEach(a => {
                    tbody.innerHTML += `
                        <tr>
                            <td><img src="uploads_artikel/${a.gambar}" class="thumb" alt="Thumbnail"></td>
                            <td>${a.judul}</td>
                            <td><span class="badge">${a.nama_kategori}</span></td>
                            <td>${a.penulis}</td>
                            <td style="font-size: 0.8rem; color: #666;">${a.hari_tanggal}</td>
                            <td>
                                <button class="btn btn-sm btn-info" onclick="editArtikel(${a.id})">Edit</button>
                                <button class="btn btn-sm btn-danger" onclick="hapusData('artikel', ${a.id})">Hapus</button>
                            </td>
                        </tr>
                    `;
                });
            } catch(e) { console.error(e); }
        }

        async function openModalArtikel() {
            await loadDropdowns();
            document.getElementById('form-artikel').reset();
            document.getElementById('artikel_id').value = '';
            document.getElementById('title-artikel').innerText = 'Tambah Artikel';
            document.getElementById('artikel_gambar').required = true;
            document.getElementById('lbl-artikel-gambar').innerText = 'Gambar';
            openModal('modal-artikel');
        }

        async function editArtikel(id) {
            await loadDropdowns();
            const res = await fetch(`ambil_satu_artikel.php?id=${id}`);
            const json = await res.json();
            if(json.status === 'success') {
                const d = json.data;
                document.getElementById('artikel_id').value = d.id;
                document.getElementById('artikel_judul').value = d.judul;
                document.getElementById('artikel_id_penulis').value = d.id_penulis;
                document.getElementById('artikel_id_kategori').value = d.id_kategori;
                document.getElementById('artikel_isi').value = d.isi;
                document.getElementById('title-artikel').innerText = 'Edit Artikel';
                document.getElementById('artikel_gambar').required = false;
                document.getElementById('lbl-artikel-gambar').innerText = 'Gambar (kosongkan jika tidak diganti)';
                openModal('modal-artikel');
            }
        }

        async function submitArtikel(e) {
            e.preventDefault();
            const form = document.getElementById('form-artikel');
            const data = new FormData(form);
            const id = document.getElementById('artikel_id').value;
            const url = id ? 'update_artikel.php' : 'simpan_artikel.php';
            
            const res = await fetch(url, { method: 'POST', body: data });
            const json = await res.json();
            showToast(json.message, json.status);
            if(json.status === 'success') { closeModal('modal-artikel'); loadArtikel(); }
        }

        let deleteType = ''; let deleteId = 0;
        function hapusData(type, id) {
            deleteType = type; deleteId = id;
            openModal('modal-hapus');
        }
        document.getElementById('btn-confirm-delete').addEventListener('click', async () => {
            const data = new FormData(); data.append('id', deleteId);
            const res = await fetch(`hapus_${deleteType}.php`, { method:'POST', body:data });
            const json = await res.json();
            showToast(json.message, json.status);
            closeModal('modal-hapus');
            if(json.status === 'success'){
                if(deleteType === 'penulis') loadPenulis();
                if(deleteType === 'kategori') loadKategori();
                if(deleteType === 'artikel') loadArtikel();
            }
        });

        loadPenulis();
    </script>
</body>
</html>
