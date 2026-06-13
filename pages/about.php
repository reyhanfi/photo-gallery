<?php
$page_title = 'About Me';
require_once '../includes/header.php';
?>

<!-- PAGE HEADER -->
<div class="bg-dark text-white py-4">
    <div class="container">
        <h1 class="fw-bold mb-1">
            <i class="bi bi-person-circle me-2"></i>About Me
        </h1>
        <p class="text-muted mb-0">Kenalan lebih dekat dengan pembuat website ini</p>
    </div>
</div>

<!-- MAIN CONTENT -->
<div class="container py-5" style="min-height: calc(100vh - 116px)">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 p-md-5">

                    <!-- Foto profil + nama -->
                    <div class="text-center mb-4">
                        <div class="rounded-circle bg-dark d-inline-flex align-items-center
                                    justify-content-center mb-3"
                             style="width:120px;height:120px">
                            <i class="bi bi-person-fill text-white" style="font-size:4rem"></i>
                        </div>
                        <h2 class="fw-bold mb-1">Reyhan Abelard Fikri</h2>
                        <p class="text-muted mb-0">Mahasiswa TRPL — Universitas Gadjah Mada</p>
                    </div>

                    <hr>

                    <!-- Biodata -->
                    <h5 class="fw-bold mb-3">
                        <i class="bi bi-person-vcard me-2 text-primary"></i>Biodata
                    </h5>
                    <div class="table-responsive mb-4">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td width="40%" class="text-muted fw-semibold">Nama Lengkap</td>
                                    <td>: Reyhan Abelard Fikri</td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-semibold">NIM</td>
                                    <td>: 25/562000/SV/26713</td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-semibold">Program Studi</td>
                                    <td>: Teknologi Rekayasa Perangkat Lunak</td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-semibold">Universitas</td>
                                    <td>: Universitas Gadjah Mada</td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-semibold">Angkatan</td>
                                    <td>: 2025</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <hr>

                    <!-- Tentang project -->
                    <h5 class="fw-bold mb-3">
                        <i class="bi bi-info-circle me-2 text-primary"></i>Tentang Project Ini
                    </h5>
                    <p class="text-muted mb-4">
                        <strong>Arey's Catalog</strong> adalah ruang digital untuk menyimpan
                        momen-momen yang layak dikenang. Dibangun sebagai proyek UAS
                        Praktikum Pemrograman Web 1 dan Praktikum Basis Data, website ini
                        lahir dari keyakinan sederhana bahwa setiap foto menyimpan cerita —
                        dan cerita itu layak untuk diorganisir, dijaga, dan dilihat kembali.
                        Lebih dari sekadar galeri, Arey's Catalog adalah arsip visual dari
                        perjalanan yang <em>moments worth keeping</em>.
                    </p>

                    <hr>

                    <!-- Teknologi -->
                    <h5 class="fw-bold mb-3">
                        <i class="bi bi-tools me-2 text-primary"></i>Teknologi yang Digunakan
                    </h5>
                    <div class="row g-2 mb-4">
                        <?php
                        $tech = [
                            ['icon' => 'bi-filetype-php', 'name' => 'PHP Native',  'color' => '#7b7fb5'],
                            ['icon' => 'bi-database',     'name' => 'MySQL',        'color' => '#00758f'],
                            ['icon' => 'bi-bootstrap',    'name' => 'Bootstrap 5',  'color' => '#7952b3'],
                            ['icon' => 'bi-braces',       'name' => 'JavaScript',   'color' => '#f7df1e'],
                            ['icon' => 'bi-server',       'name' => 'XAMPP',        'color' => '#fb7a24'],
                            ['icon' => 'bi-git',          'name' => 'Git & GitHub', 'color' => '#f05032'],
                        ];
                        foreach ($tech as $t): ?>
                        <div class="col-6 col-md-4">
                            <div class="d-flex align-items-center gap-2 p-2 rounded border">
                                <i class="<?= $t['icon'] ?>"
                                   style="font-size:1.4rem;color:<?= $t['color'] ?>"></i>
                                <span class="small fw-semibold"><?= $t['name'] ?></span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <hr>

                    <!-- Kontak -->
                    <h5 class="fw-bold mb-3">
                        <i class="bi bi-envelope me-2 text-primary"></i>Kontak
                    </h5>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="mailto:reyhanaf110@gmail.com"
                           class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-envelope me-2"></i>reyhanaf110@gmail.com
                        </a>
                        <a href="https://github.com/reyhanfi"
                           target="_blank"
                           class="btn btn-outline-dark btn-sm">
                            <i class="bi bi-github me-2"></i>github.com/reyhanfi
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>