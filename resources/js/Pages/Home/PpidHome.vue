<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { ref } from "vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";

const props = defineProps({
    heroStats: {
        type: Array,
        default: () => [
            { num: "312", label: "Permohonan diproses 2026" },
            { num: "2.4 hr", label: "Rata-rata waktu respons" },
            { num: "96%", label: "Permohonan terselesaikan" },
        ],
    },
    services: {
        type: Array,
        default: () => [
            {
                icon: "✎",
                title: "Permohonan Informasi",
                desc: "Ajukan permintaan informasi publik secara daring dan lacak statusnya kapan saja.",
                href: "/permohonan/buat",
                cta: "Ajukan sekarang",
            },
            {
                icon: "⚖",
                title: "Pengajuan Keberatan",
                desc: "Sampaikan keberatan atas jawaban permohonan yang belum sesuai dengan ketentuan.",
                href: "/keberatan/buat",
                cta: "Ajukan keberatan",
            },
            {
                icon: "☰",
                title: "Daftar Informasi Publik",
                desc: "Telusuri Daftar Informasi Publik (DIP) berkala, serta-merta, dan setiap saat.",
                href: "/informasi-publik",
                cta: "Lihat daftar",
            },
            {
                icon: "◈",
                title: "Pengaduan & Masukan",
                desc: "Sampaikan laporan, saran, atau pengaduan terkait layanan PPID Kemenag Gresik.",
                href: "/pengaduan",
                cta: "Kirim pengaduan",
            },
        ],
    },
    flow: {
        type: Array,
        default: () => [
            {
                num: "01",
                title: "Ajukan Permohonan",
                desc: "Isi formulir daring dengan data diri dan rincian informasi yang dibutuhkan.",
            },
            {
                num: "02",
                title: "Verifikasi Berkas",
                desc: "Tim PPID memeriksa kelengkapan dan kesesuaian permohonan Anda.",
            },
            {
                num: "03",
                title: "Proses & Klasifikasi",
                desc: "Informasi diklasifikasikan sesuai ketentuan keterbukaan informasi publik.",
            },
            {
                num: "04",
                title: "Informasi Diterima",
                desc: "Jawaban dikirim maksimal 10 hari kerja melalui email atau unduhan langsung.",
            },
        ],
    },
    stats: {
        type: Array,
        default: () => [
            { label: "Permohonan Diterima", num: "312", pct: 82 },
            { label: "Permohonan Selesai", num: "299", pct: 96 },
            { label: "Keberatan Masuk", num: "7", pct: 22 },
            { label: "Indeks Kepuasan", num: "4.7/5", pct: 94 },
        ],
    },
    news: {
        type: Array,
        default: () => [
            {
                date: "18 Agustus 2026",
                title: "Sosialisasi Keterbukaan Informasi Publik di Lingkungan KUA Gresik",
                excerpt:
                    "Kemenag Gresik menggelar sosialisasi penguatan layanan PPID di 18 Kantor Urusan Agama.",
                href: "#",
            },
            {
                date: "05 Agustus 2026",
                title: "Peluncuran Layanan Permohonan Informasi Daring Terbaru",
                excerpt:
                    "Kini masyarakat dapat mengajukan permohonan dan melacak status secara real-time.",
                href: "#",
            },
            {
                date: "22 Juli 2026",
                title: "Laporan Tahunan Keterbukaan Informasi Publik 2025 Terbit",
                excerpt:
                    "Laporan lengkap kinerja PPID Kemenag Gresik kini dapat diunduh publik.",
                href: "#",
            },
        ],
    },
    regulations: {
        type: Array,
        default: () => [
            {
                name: "UU No. 14 Tahun 2008",
                desc: "Tentang Keterbukaan Informasi Publik",
                tag: "Undang-Undang",
            },
            {
                name: "PP No. 61 Tahun 2010",
                desc: "Pelaksanaan UU Keterbukaan Informasi Publik",
                tag: "Peraturan Pemerintah",
            },
            {
                name: "PMA No. 21 Tahun 2018",
                desc: "Pengelolaan Layanan Informasi di Kementerian Agama",
                tag: "Peraturan Menteri",
            },
            {
                name: "SK Kakanwil Kemenag Jatim",
                desc: "Penetapan Struktur PPID Kabupaten Gresik",
                tag: "Keputusan",
            },
        ],
    },
});

const menuOpen = ref(false);
</script>

<template>
    <Head title="PPID Kemenag Gresik — Portal Keterbukaan Informasi Publik" />

    <div class="ppid">
        <div class="nav">
            <div class="wrap nav-inner">
                <Link href="/" class="brand">
                    <div class="brand-mark">
                        <ApplicationLogo
                            class="block h-11 w-auto fill-current text-gray-800"
                        />
                    </div>
                    <div class="brand-text">
                        <div class="t1">PPID Kementerian</div>
                        <div class="t1">Agama Kabupaten Gresik</div>
                    </div>
                </Link>

                <nav class="menu">
                    <Link href="/">Beranda</Link>
                    <Link href="/profil">Profil PPID</Link>
                    <a href="#layanan">Layanan Informasi</a>
                </nav>

                <Link href="/permohonan/buat" class="btn-primary"
                    >Ajukan Permohonan →</Link
                >

                <button
                    class="burger"
                    @click="menuOpen = !menuOpen"
                    aria-label="Buka menu"
                >
                    ☰
                </button>
            </div>

            <div v-if="menuOpen" class="mobile-menu">
                <Link href="/">Beranda</Link>
                <Link href="/profil">Profil PPID</Link>
                <a href="#layanan" @click="menuOpen = false"
                    >Layanan Informasi</a
                >
                <a href="#regulasi" @click="menuOpen = false">Regulasi</a>
                <a href="#berita" @click="menuOpen = false">Berita</a>
                <a href="#kontak" @click="menuOpen = false">Kontak</a>
            </div>
        </div>

        <div class="hero">
            <div class="wrap hero-inner">
                <div>
                    <div class="eyebrow">
                        <span class="dot"></span> UU No. 14 Tahun 2008 tentang
                        Keterbukaan Informasi Publik
                    </div>
                    <h1 class="hero-title">
                        Informasi publik <em>adalah hak Anda</em>, transparansi
                        adalah tugas kami.
                    </h1>
                    <p class="hero-sub">
                        PPID Kementerian Agama Kabupaten Gresik melayani
                        permohonan, keberatan, dan akses informasi publik secara
                        terbuka, cepat, dan akuntabel bagi seluruh masyarakat
                        Gresik.
                    </p>
                    <div class="hero-cta">
                        <Link href="/permohonan/buat" class="btn-primary"
                            >Ajukan Permohonan Informasi</Link
                        >
                        <Link href="/informasi-publik" class="btn-ghost"
                            >Lihat Daftar Informasi Publik</Link
                        >
                    </div>
                    <div class="hero-stats">
                        <div
                            class="hstat"
                            v-for="s in heroStats"
                            :key="s.label"
                        >
                            <div class="num">{{ s.num }}</div>
                            <div class="lbl">{{ s.label }}</div>
                        </div>
                    </div>
                </div>
                <div class="hero-visual">
                    <span class="badge-float">Badan Publik Informatif</span>
                    <div class="gate">
                        <div class="gate-card">
                            <div class="k">Klasifikasi Informasi</div>
                            <div class="v">Terbuka · Berkala · Serta-merta</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <section id="layanan">
            <div class="wrap">
                <div class="section-head">
                    <div>
                        <div class="kicker">Akses Cepat</div>
                        <h2 class="section-title">Layanan Informasi Publik</h2>
                    </div>
                    <p class="section-desc">
                        Empat pintu layanan utama yang paling sering diakses
                        oleh pemohon informasi di lingkungan Kemenag Gresik.
                    </p>
                </div>
                <div class="services">
                    <div
                        class="svc-card"
                        v-for="svc in services"
                        :key="svc.title"
                    >
                        <div class="svc-icon">{{ svc.icon }}</div>
                        <h3>{{ svc.title }}</h3>
                        <p>{{ svc.desc }}</p>
                        <Link :href="svc.href" class="svc-link"
                            >{{ svc.cta }} →</Link
                        >
                    </div>
                </div>
            </div>
        </section>

        <section class="flow-band">
            <div class="wrap">
                <div class="section-head">
                    <div>
                        <div class="kicker kicker-gold">Prosedur</div>
                        <h2 class="section-title title-white">
                            Alur Permohonan Informasi
                        </h2>
                    </div>
                    <p class="section-desc desc-light">
                        Empat tahap sederhana dari pengajuan hingga informasi
                        diterima pemohon.
                    </p>
                </div>
                <div class="flow-steps">
                    <div class="flow-step" v-for="f in flow" :key="f.num">
                        <div class="flow-num">{{ f.num }}</div>
                        <h4>{{ f.title }}</h4>
                        <p>{{ f.desc }}</p>
                    </div>
                </div>
            </div>
        </section>

        <section>
            <div class="wrap">
                <div class="section-head">
                    <div>
                        <div class="kicker">Dashboard Transparansi</div>
                        <h2 class="section-title">
                            Statistik Layanan Tahun 2026
                        </h2>
                    </div>
                    <p class="section-desc">
                        Data diperbarui otomatis setiap bulan sebagai bentuk
                        akuntabilitas publik.
                    </p>
                </div>
                <div class="stat-grid">
                    <div class="stat-card" v-for="st in stats" :key="st.label">
                        <div class="lbl">{{ st.label }}</div>
                        <div class="num">{{ st.num }}</div>
                        <div class="bar-track">
                            <div
                                class="bar-fill"
                                :style="{ width: st.pct + '%' }"
                            ></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="regulasi">
            <div class="wrap">
                <div class="section-head">
                    <div>
                        <div class="kicker">Landasan Hukum</div>
                        <h2 class="section-title">
                            Regulasi &amp; Dasar Hukum
                        </h2>
                    </div>
                    <p class="section-desc">
                        Peraturan yang menjadi acuan pelaksanaan layanan
                        informasi publik.
                    </p>
                </div>
                <div class="reg-list">
                    <div
                        class="reg-item"
                        v-for="r in regulations"
                        :key="r.name"
                    >
                        <div>
                            <div class="name">{{ r.name }}</div>
                            <div class="desc">{{ r.desc }}</div>
                        </div>
                        <span class="reg-tag">{{ r.tag }}</span>
                    </div>
                </div>
            </div>
        </section>

        <section>
            <div class="wrap">
                <div class="cta-band">
                    <div>
                        <h3>Butuh informasi yang belum tersedia?</h3>
                        <p>
                            Tim PPID Kemenag Gresik siap membantu permohonan
                            Anda dalam waktu maksimal 10 hari kerja.
                        </p>
                    </div>
                    <div class="cta-actions">
                        <Link href="/permohonan/buat" class="btn-white"
                            >Ajukan Permohonan</Link
                        >
                        <a href="#kontak" class="btn-outline-w">Hubungi Kami</a>
                    </div>
                </div>
            </div>
        </section>

        <footer id="kontak">
            <div class="wrap">
                <div class="footer-grid">
                    <div>
                        <div class="fbrand">
                            <div class="brand-mark">PG</div>
                            <div class="brand-text">
                                <div class="t2 t2-white">
                                    PPID Kemenag Gresik
                                </div>
                            </div>
                        </div>
                        <p>
                            Pejabat Pengelola Informasi dan Dokumentasi
                            Kementerian Agama Kabupaten Gresik, melayani hak
                            masyarakat atas informasi publik.
                        </p>
                        <p>
                            Jl. KH. Wahid Hasyim No. 1, Gresik, Jawa Timur 61111
                        </p>
                    </div>
                    <div>
                        <h5>Layanan</h5>
                        <ul>
                            <li>
                                <Link href="/permohonan/buat"
                                    >Permohonan Informasi</Link
                                >
                            </li>
                            <li>
                                <Link href="/keberatan/buat">Keberatan</Link>
                            </li>
                            <li>
                                <Link href="/informasi-publik"
                                    >Daftar Informasi Publik</Link
                                >
                            </li>
                            <li><Link href="/pengaduan">Pengaduan</Link></li>
                        </ul>
                    </div>
                    <div>
                        <h5>Tautan</h5>
                        <ul>
                            <li><Link href="/profil">Profil PPID</Link></li>
                            <li><a href="#regulasi">Regulasi</a></li>
                            <li><a href="#berita">Berita</a></li>
                            <li>
                                <Link href="/laporan-tahunan"
                                    >Laporan Tahunan</Link
                                >
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h5>Kontak</h5>
                        <ul>
                            <li>
                                <a href="mailto:ppid@kemenaggresik.go.id"
                                    >ppid@kemenaggresik.go.id</a
                                >
                            </li>
                            <li>
                                <a href="tel:+62313981234">(031) 3981234</a>
                            </li>
                            <li><span>Senin–Jumat, 08.00–15.00</span></li>
                        </ul>
                    </div>
                </div>
                <div class="foot-bottom">
                    <span>© 2026 PPID Kementerian Agama Kabupaten Gresik</span>
                    <span>Badan Publik Informatif</span>
                </div>
            </div>
        </footer>
    </div>
</template>

<style src="../../../css/ppidHome.css" scoped></style>
