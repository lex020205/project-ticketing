# Pemetaan Modul ke File (Ringkas)

## Modul 1 - Auth, Role Access, dan Dashboard Awal
Ringkas: autentikasi user, pembatasan role, dan pengalihan dashboard awal.
Contoh alur: user login -> role dicek -> diarahkan ke dashboard sesuai role.
- Routes/Controllers/Middleware: [routes/web.php](routes/web.php), [app/Http/Controllers/DashboardController.php](app/Http/Controllers/DashboardController.php), [app/Http/Controllers/Auth/LoginController.php](app/Http/Controllers/Auth/LoginController.php), [app/Http/Controllers/Auth/RegisterController.php](app/Http/Controllers/Auth/RegisterController.php), [app/Http/Middleware/CheckRole.php](app/Http/Middleware/CheckRole.php)
- Views/Layout: [resources/views/auth/login.blade.php](resources/views/auth/login.blade.php), [resources/views/auth/register.blade.php](resources/views/auth/register.blade.php), [resources/views/layouts/app.blade.php](resources/views/layouts/app.blade.php)
- Models/Migrations: [app/Models/User.php](app/Models/User.php), [app/Models/Role.php](app/Models/Role.php), [database/migrations/0001_01_01_000000_create_users_table.php](database/migrations/0001_01_01_000000_create_users_table.php), [database/migrations/2026_05_15_143512_create_roles_table.php](database/migrations/2026_05_15_143512_create_roles_table.php), [database/migrations/2026_05_15_143603_add_role_id_to_users_table.php](database/migrations/2026_05_15_143603_add_role_id_to_users_table.php)

## Modul 2 - Admin Keluhan
Ringkas: input, update, dan validasi keluhan sebagai dasar ticket.
Contoh alur: admin buat keluhan -> validasi/ubah status -> keluhan siap dibuat ticket.
- Controller/View: [app/Http/Controllers/Admin/KeluhanController.php](app/Http/Controllers/Admin/KeluhanController.php), [resources/views/admin/keluhan/index.blade.php](resources/views/admin/keluhan/index.blade.php), [resources/views/admin/keluhan/create.blade.php](resources/views/admin/keluhan/create.blade.php), [resources/views/admin/keluhan/edit.blade.php](resources/views/admin/keluhan/edit.blade.php), [resources/views/admin/keluhan/show.blade.php](resources/views/admin/keluhan/show.blade.php), [resources/views/admin/keluhan/partials/form.blade.php](resources/views/admin/keluhan/partials/form.blade.php)
- Model/Migration: [app/Models/Keluhan.php](app/Models/Keluhan.php), [database/migrations/2026_05_15_143650_create_keluhans_table.php](database/migrations/2026_05_15_143650_create_keluhans_table.php)

## Modul 3 - Admin Buat Ticket dari Keluhan
Ringkas: membuat ticket baru dari keluhan yang valid.
Contoh alur: pilih keluhan valid -> isi prioritas/teknisi -> ticket terbentuk.
- Controller/View: [app/Http/Controllers/Admin/TicketController.php](app/Http/Controllers/Admin/TicketController.php), [resources/views/admin/tickets/create.blade.php](resources/views/admin/tickets/create.blade.php)
- Model/Migration: [app/Models/Ticket.php](app/Models/Ticket.php), [database/migrations/2026_05_15_143753_create_tickets_table.php](database/migrations/2026_05_15_143753_create_tickets_table.php)

## Modul 4 - Teknisi Ticket Saya dan Progress Pengerjaan
Ringkas: teknisi melihat ticket miliknya dan mengisi progress kerja.
Contoh alur: teknisi buka ticket -> mulai kerja -> tambah progress/status.
- Controller/View: [app/Http/Controllers/Teknisi/TicketController.php](app/Http/Controllers/Teknisi/TicketController.php), [resources/views/teknisi/tickets/index.blade.php](resources/views/teknisi/tickets/index.blade.php), [resources/views/teknisi/tickets/show.blade.php](resources/views/teknisi/tickets/show.blade.php)
- Model/Migration: [app/Models/TicketProgress.php](app/Models/TicketProgress.php), [database/migrations/2026_05_15_143819_create_ticket_progress_table.php](database/migrations/2026_05_15_143819_create_ticket_progress_table.php)

## Modul 5 - Teknisi Upload Bukti Pengerjaan
Ringkas: teknisi mengunggah lampiran hasil pekerjaan.
Contoh alur: teknisi upload foto/PDF bukti -> lampiran tersimpan.
- Controller/View: [app/Http/Controllers/Teknisi/TicketController.php](app/Http/Controllers/Teknisi/TicketController.php), [resources/views/teknisi/tickets/show.blade.php](resources/views/teknisi/tickets/show.blade.php)
- Model/Migration: [app/Models/TicketLampiran.php](app/Models/TicketLampiran.php), [database/migrations/2026_05_15_143839_create_ticket_lampiran_table.php](database/migrations/2026_05_15_143839_create_ticket_lampiran_table.php)

## Modul 6 - Teknisi Ajukan Eskalasi
Ringkas: teknisi mengajukan eskalasi saat butuh bantuan/alat/keputusan.
Contoh alur: teknisi ajukan eskalasi -> status ticket eskalasi.
- Controller/View: [app/Http/Controllers/Teknisi/TicketController.php](app/Http/Controllers/Teknisi/TicketController.php), [resources/views/teknisi/tickets/show.blade.php](resources/views/teknisi/tickets/show.blade.php)
- Model/Migration: [app/Models/TicketEskalasi.php](app/Models/TicketEskalasi.php), [database/migrations/2026_05_15_143945_create_ticket_eskalasi_table.php](database/migrations/2026_05_15_143945_create_ticket_eskalasi_table.php)

## Modul 7 - SPV Keputusan Eskalasi
Ringkas: SPV meninjau dan memutuskan eskalasi.
Contoh alur: SPV review eskalasi -> putuskan status/assign ulang.
- Controller/View: [app/Http/Controllers/Spv/EskalasiController.php](app/Http/Controllers/Spv/EskalasiController.php), [resources/views/spv/eskalasi/index.blade.php](resources/views/spv/eskalasi/index.blade.php), [resources/views/spv/eskalasi/show.blade.php](resources/views/spv/eskalasi/show.blade.php)
- Model: [app/Models/TicketEskalasi.php](app/Models/TicketEskalasi.php)

## Modul 8 - Admin/SPV Verifikasi Ticket Selesai
Ringkas: verifikasi hasil kerja dan keputusan akhir ticket.
Contoh alur: verifikator cek bukti -> terima/tolak -> status diperbarui.
- Controllers: [app/Http/Controllers/Admin/VerifikasiController.php](app/Http/Controllers/Admin/VerifikasiController.php), [app/Http/Controllers/Spv/VerifikasiController.php](app/Http/Controllers/Spv/VerifikasiController.php)
- Views: [resources/views/admin/verifikasi/index.blade.php](resources/views/admin/verifikasi/index.blade.php), [resources/views/admin/verifikasi/show.blade.php](resources/views/admin/verifikasi/show.blade.php), [resources/views/spv/verifikasi/index.blade.php](resources/views/spv/verifikasi/index.blade.php), [resources/views/spv/verifikasi/show.blade.php](resources/views/spv/verifikasi/show.blade.php)
- Model/Migration: [app/Models/TicketVerifikasi.php](app/Models/TicketVerifikasi.php), [database/migrations/2026_05_15_144007_create_ticket_verifikasi_table.php](database/migrations/2026_05_15_144007_create_ticket_verifikasi_table.php)

## Modul 9 - SPV Monitoring Semua Ticket
Ringkas: monitoring semua ticket oleh SPV termasuk prioritas dan assign ulang.
Contoh alur: SPV filter ticket -> ubah prioritas/assign teknisi.
- Controller/View: [app/Http/Controllers/Spv/TicketController.php](app/Http/Controllers/Spv/TicketController.php), [resources/views/spv/tickets/index.blade.php](resources/views/spv/tickets/index.blade.php), [resources/views/spv/tickets/show.blade.php](resources/views/spv/tickets/show.blade.php)

## Modul 10 - Admin Monitoring Ticket dan Assign Teknisi
Ringkas: monitoring ticket sisi admin.
Contoh alur: admin pantau ticket -> lihat detail dan statusnya.
- Controller/View: [app/Http/Controllers/Admin/TicketController.php](app/Http/Controllers/Admin/TicketController.php), [resources/views/admin/tickets/index.blade.php](resources/views/admin/tickets/index.blade.php), [resources/views/admin/tickets/show.blade.php](resources/views/admin/tickets/show.blade.php)

## Modul 11 - Laporan Admin dan SPV
Ringkas: laporan dan rekap ticket berdasarkan filter.
Contoh alur: pilih filter tanggal/status -> lihat rekap dan daftar ticket.
- Controller/View: [app/Http/Controllers/Admin/LaporanController.php](app/Http/Controllers/Admin/LaporanController.php), [app/Http/Controllers/Spv/LaporanController.php](app/Http/Controllers/Spv/LaporanController.php), [resources/views/admin/laporan/index.blade.php](resources/views/admin/laporan/index.blade.php), [resources/views/spv/laporan/index.blade.php](resources/views/spv/laporan/index.blade.php)

## Modul 12 - SPV User Management
Ringkas: kelola user, role, status, dan reset password.
Contoh alur: SPV tambah user -> atur role/status -> reset password bila perlu.
- Controller/View: [app/Http/Controllers/Spv/UserController.php](app/Http/Controllers/Spv/UserController.php), [resources/views/spv/users/index.blade.php](resources/views/spv/users/index.blade.php), [resources/views/spv/users/create.blade.php](resources/views/spv/users/create.blade.php), [resources/views/spv/users/edit.blade.php](resources/views/spv/users/edit.blade.php), [resources/views/spv/users/show.blade.php](resources/views/spv/users/show.blade.php)
- Model: [app/Models/User.php](app/Models/User.php)

## Modul 13 - SPV Kategori Masalah
Ringkas: kelola kategori masalah dan status aktif/nonaktif.
Contoh alur: SPV tambah kategori -> aktif/nonaktif -> dipakai di keluhan.
- Controller/View: [app/Http/Controllers/Spv/KategoriMasalahController.php](app/Http/Controllers/Spv/KategoriMasalahController.php), [resources/views/spv/kategori/index.blade.php](resources/views/spv/kategori/index.blade.php), [resources/views/spv/kategori/create.blade.php](resources/views/spv/kategori/create.blade.php), [resources/views/spv/kategori/edit.blade.php](resources/views/spv/kategori/edit.blade.php), [resources/views/spv/kategori/show.blade.php](resources/views/spv/kategori/show.blade.php)
- Model/Migration: [app/Models/KategoriMasalah.php](app/Models/KategoriMasalah.php), [database/migrations/2026_05_15_143632_create_kategori_masalah_table.php](database/migrations/2026_05_15_143632_create_kategori_masalah_table.php)

## Modul 14 - Finalisasi Dashboard Statistik per Role
Ringkas: dashboard ringkasan statistik per role dan styling global.
Contoh alur: user masuk dashboard role -> lihat ringkasan statistik.
- Controllers/Dashboards: [app/Http/Controllers/Admin/DashboardController.php](app/Http/Controllers/Admin/DashboardController.php), [app/Http/Controllers/Spv/DashboardController.php](app/Http/Controllers/Spv/DashboardController.php), [app/Http/Controllers/Teknisi/DashboardController.php](app/Http/Controllers/Teknisi/DashboardController.php), [resources/views/admin/dashboard.blade.php](resources/views/admin/dashboard.blade.php), [resources/views/spv/dashboard.blade.php](resources/views/spv/dashboard.blade.php), [resources/views/teknisi/dashboard.blade.php](resources/views/teknisi/dashboard.blade.php)
- Assets: [resources/css/app.css](resources/css/app.css), [resources/js/app.js](resources/js/app.js)

## Modul 15 - Testing, Quality Check, dan Demo Data
Ringkas: seeding demo data dan checklist pengujian.
Contoh alur: jalankan seeder -> gunakan checklist untuk QA.
- Seeders: [database/seeders/DatabaseSeeder.php](database/seeders/DatabaseSeeder.php), [database/seeders/RoleSeeder.php](database/seeders/RoleSeeder.php), [database/seeders/KategoriMasalahSeeder.php](database/seeders/KategoriMasalahSeeder.php), [database/seeders/UserSeeder.php](database/seeders/UserSeeder.php), [database/seeders/DemoTicketSeeder.php](database/seeders/DemoTicketSeeder.php)
- Docs/System: [docs/testing-checklist.md](docs/testing-checklist.md), [database/migrations/0001_01_01_000001_create_cache_table.php](database/migrations/0001_01_01_000001_create_cache_table.php), [database/migrations/0001_01_01_000002_create_jobs_table.php](database/migrations/0001_01_01_000002_create_jobs_table.php)
