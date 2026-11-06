---
name: "Teduh Rasa"
version: "1.0.0"
colors:
  canvas: "#F6EFE3"        # Latar belakang utama sisi formulir (Beige hangat)
  primary: "#5A1B24"       # Warna tombol utama / CTA & panel merek (Maroon)
  surface: "#FBF7EF"       # Latar kolom input / komponen kontainer (Beige terang)
  text: "#3B0F16"          # Warna teks utama (Maroon gelap)
  accent: "#E9DBC3"        # Warna aksen: teks & garis di atas maroon (Sand)
  muted: "#7D6A62"         # Teks sekunder, placeholder, label pendukung
  line: "#DACBB3"          # Border input dan pemisah halus
  error: "#9B2226"         # Pesan galat validasi
  dark:
    canvas: "#260D12"
    surface: "#321219"
    text: "#F1E6D4"
    line: "#4A2A2F"
    primary: "#E9DBC3"     # Tombol terbalik: latar sand, teks maroon gelap
typography:
  headline:
    fontFamily: "Cormorant Garamond"
    fontWeight: "600"
    fontSize: "44px"       # 38px di layar ponsel
    lineHeight: "1.05"
  body:
    fontFamily: "DM Sans"
    fontWeight: "400"
    fontSize: "16px"
    lineHeight: "1.5"
  label:
    fontFamily: "DM Sans"
    fontWeight: "500"
    fontSize: "14px"
shapes:
  borderRadius:
    sm: "5px"              # Checkbox
    md: "8px"              # Tombol teks kecil (tampilkan/sembunyikan)
    lg: "10px"             # Input dan tombol utama
spacing:
  base: "8px"
  fieldGap: "20px"
  cardMaxWidth: "380px"
  panelPadding: "48px 56px"
---

# Design System: Teduh Rasa

## 1. Visual Theme & Atmosphere
Desain ini mengusung tema minimalis yang tenang, hangat, dan elegan untuk aplikasi restoran berbasis web. Nama "Teduh Rasa" diterjemahkan menjadi suasana tempat bernaung: panel maroon pekat sebagai "peneduh", dipadukan dengan beige lembut yang terasa seperti kertas menu dan meja kayu terang. Ruang kosong dibiarkan lega agar tampilan tetap bersih, sementara kesan menawan datang dari satu elemen khas saja, yaitu lengkungan garis tipis bertingkat yang melambangkan kanopi atau pintu masuk.

## 2. Color Palette & Roles
* **Canvas (`#F6EFE3`):** Latar belakang sisi formulir dan halaman konten. Pengganti putih murni agar nyaman di mata.
* **Primary (`#5A1B24`):** Khusus untuk tombol *Call-to-Action* utama, panel merek, checkbox aktif, dan cincin fokus. Jangan dipakai sebagai warna teks paragraf panjang.
* **Surface (`#FBF7EF`):** Latar kolom input dan komponen kontainer. Sedikit lebih terang dari Canvas sehingga bidang input terbaca tanpa bayangan.
* **Text (`#3B0F16`):** Teks utama dan judul. Maroon yang sangat gelap, lebih hangat daripada hitam.
* **Accent / Sand (`#E9DBC3`):** Teks dan garis dekoratif di atas panel maroon, serta warna tombol pada mode gelap.
* **Muted (`#7D6A62`):** Teks pendukung, placeholder, dan label sekunder.
* **Line (`#DACBB3`):** Border input dan pemisah halus.
* **Error (`#9B2226`):** Pesan galat validasi. Pada mode gelap berubah menjadi `#F0928F` agar tetap kontras.
* **Mode gelap:** Canvas menjadi `#260D12`, Surface `#321219`, Text `#F1E6D4`. Tombol utama dibalik menjadi latar sand dengan teks maroon gelap. Panel merek tetap maroon pada kedua mode.

## 3. Typography Rules
* **Headlines:** Menggunakan font Serif (*Cormorant Garamond*, bobot 600) ukuran 44px (38px di ponsel) dengan line-height rapat 1.05 untuk kesan elegan seperti judul menu restoran. Logo dan kutipan di panel merek memakai keluarga font yang sama.
* **Body Text:** Menggunakan Humanist Sans (*DM Sans*) ukuran 16px, bobot 400, line-height 1.5.
* **Label & tautan kecil:** *DM Sans* 14px, bobot 500 untuk label input. Gunakan sentence case, hindari huruf kapital semua dan jarak antarhuruf yang dilebarkan.
* **Panjang baris:** Jaga di bawah 80 karakter. Kartu formulir dibatasi lebar maksimal 380px.

## 4. Components & Elevation
* **Layout:** Dua kolom sama lebar. Kiri panel merek maroon, kanan formulir di atas Canvas. Di bawah 820px, kolom bertumpuk dan panel merek menyusut menjadi header ringkas tanpa ornamen lengkung.
* **Buttons:** Tombol utama wajib menggunakan warna *Primary*, teks beige (`#F6EFE3`), tinggi 52px, `border-radius: 10px`, lebar penuh. Efek hover cukup dengan opasitas 0.92, tekan dengan skala 0.99. Saat memproses, tombol dinonaktifkan dan label berubah menjadi "Memproses…".
* **Inputs:** Tinggi 50px, latar *Surface*, border 1px *Line*, `border-radius: 10px`. Fokus ditandai border maroon dan cincin 3px maroon beropasitas 22%. Keadaan galat memakai border *Error* dan pesan di bawah input.
* **Tombol teks kecil:** "Tampilkan/Sembunyikan" di dalam kolom sandi memakai `border-radius: 8px`, tanpa latar, warna *Muted*.
* **Checkbox:** 18px, `border-radius: 5px`, terisi *Primary* dengan tanda centang saat aktif.
* **Ornamen lengkung:** Empat jalur SVG berbentuk lengkung konsentris dengan garis 1px warna Sand. Hanya muncul di panel merek desktop, tidak dipakai di tempat lain.
* **Elevation:** Flat UI tanpa *drop shadow*. Hierarki dibangun lewat warna latar dan border tipis. Satu-satunya "glow" adalah cincin fokus.
* **Motion:** Transisi singkat 0.15–0.2 detik hanya sebagai respons aksi pengguna. Tidak ada animasi otomatis. Hormati `prefers-reduced-motion`.

## 5. Copywriting
* Gunakan Bahasa Indonesia yang ramah dan lugas, sentence case, kata kerja aktif ("Masuk", "Daftar di sini").
* Nama aksi konsisten di seluruh alur: tombol "Masuk" menghasilkan pesan "Berhasil masuk".
* Pesan galat menyebut masalah dan cara memperbaikinya, tanpa permintaan maaf. Contoh: "Kata sandi minimal 8 karakter."

## 6. Do's and Don'ts
* **DO:** Gunakan warna *Primary* hanya untuk satu aksi utama di setiap area pandang (viewport).
* **DO:** Biarkan lengkungan Sand menjadi satu-satunya ornamen dekoratif. Sisanya dijaga tenang dan lega.
* **DO:** Pastikan setiap elemen interaktif punya indikator fokus keyboard yang terlihat.
* **DON'T:** Jangan menambahkan warna di luar keluarga maroon dan beige (misalnya biru atau hijau) sebagai aksen.
* **DON'T:** Jangan memakai bayangan, gradasi, atau putih murni `#FFFFFF` sebagai latar.
* **DON'T:** Jangan mencampuradukkan sudut melengkung (*rounded*) dengan sudut tajam (*sharp*) pada komponen yang sejajar.