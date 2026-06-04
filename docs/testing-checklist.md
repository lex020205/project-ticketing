<!-- Modul 15 - Testing, Quality Check, dan Demo Data -->
<!-- Ringkas: checklist pengujian fitur utama. -->
# Testing Checklist

## 1. Auth dan Role
- [ ] Admin login masuk dashboard Admin
- [ ] SPV login masuk dashboard SPV
- [ ] Teknisi login masuk dashboard Teknisi
- [ ] User tidak bisa akses dashboard role lain
- [ ] Logout berjalan
- [ ] User nonaktif tidak bisa login

## 2. Admin Keluhan
- [ ] Admin bisa tambah keluhan
- [ ] Kategori aktif muncul
- [ ] Kategori nonaktif tidak muncul
- [ ] Admin bisa validasi keluhan
- [ ] Admin bisa tandai tidak valid

## 3. Admin Ticket
- [ ] Admin bisa buat ticket dari keluhan valid
- [ ] Ticket tanpa teknisi menjadi menunggu_penugasan
- [ ] Ticket dengan teknisi menjadi ditugaskan
- [ ] Admin bisa assign teknisi
- [ ] Admin bisa ubah prioritas

## 4. Teknisi
- [ ] Teknisi hanya melihat ticket miliknya
- [ ] Teknisi bisa mulai kerjakan
- [ ] Teknisi bisa tambah progress
- [ ] Teknisi bisa upload lampiran
- [ ] Teknisi bisa ajukan eskalasi
- [ ] Teknisi bisa tandai selesai

## 5. SPV Eskalasi
- [ ] SPV bisa melihat eskalasi menunggu
- [ ] SPV bisa setujui eskalasi
- [ ] SPV bisa tolak eskalasi
- [ ] SPV bisa assign ulang teknisi saat eskalasi

## 6. Verifikasi
- [ ] Admin bisa menerima ticket selesai
- [ ] Admin bisa mengembalikan ticket ke teknisi
- [ ] SPV bisa menerima ticket selesai
- [ ] SPV bisa mengembalikan ticket ke teknisi

## 7. Monitoring dan Laporan
- [ ] SPV bisa filter ticket
- [ ] Admin bisa filter ticket
- [ ] Laporan Admin tampil
- [ ] Laporan SPV tampil
- [ ] Dashboard statistik berubah sesuai data

## 8. User Management
- [ ] SPV bisa tambah user
- [ ] SPV bisa edit user
- [ ] SPV bisa nonaktifkan user lain
- [ ] SPV tidak bisa nonaktifkan diri sendiri
- [ ] SPV bisa reset password user lain

## 9. Kategori Masalah
- [ ] SPV bisa tambah kategori
- [ ] SPV tidak bisa membuat kategori double
- [ ] SPV bisa edit kategori
- [ ] SPV bisa aktif/nonaktif kategori
- [ ] Kategori nonaktif tidak muncul di form keluhan/ticket baru
