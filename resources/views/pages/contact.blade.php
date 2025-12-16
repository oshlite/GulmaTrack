@extends('layouts.app')

@section('title', 'Kontak')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-phone"></i> Hubungi Kami</h1>
    <p>Kami siap membantu Anda dengan pertanyaan atau feedback apapun</p>
</div>

<div class="container">
    <style>
        .contact-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 40px;
        }

        .contact-info {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: var(--shadow);
        }

        .contact-info h2 {
            color: var(--title-color);
            margin-bottom: 25px;
            font-size: 20px;
            border-bottom: 3px solid var(--title-color);
            padding-bottom: 15px;
        }

        .contact-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 25px;
            padding-bottom: 25px;
            border-bottom: 1px solid var(--border-color);
        }

        .contact-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .contact-icon {
            font-size: 28px;
            margin-right: 20px;
            color: var(--primary-color);
            min-width: 40px;
        }

        .contact-details h3 {
            color: var(--dark-color);
            margin-bottom: 5px;
            font-size: 16px;
        }

        .contact-details p {
            color: #666;
            font-size: 14px;
            line-height: 1.6;
            margin: 0;
        }

        .contact-details a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .contact-details a:hover {
            color: var(--accent-color);
            text-decoration: underline;
        }

        .form-section {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: var(--shadow);
        }

        .form-section h2 {
            color: var(--title-color);
            margin-bottom: 25px;
            font-size: 20px;
            border-bottom: 3px solid var(--title-color);
            padding-bottom: 15px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-color);
            font-weight: 600;
            font-size: 14px;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-family: inherit;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(46, 204, 113, 0.1);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 120px;
        }

        .submit-btn {
            background-color: var(--primary-color);
            color: white;
            padding: 14px 32px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.3s ease;
            width: 100%;
        }

        .submit-btn:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            display: none;
            border-left: 5px solid #28a745;
        }

        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 25px;
            padding-top: 25px;
            border-top: 1px solid var(--border-color);
        }

        .social-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 45px;
            height: 45px;
            background-color: var(--light-color);
            border-radius: 50%;
            font-size: 20px;
            transition: all 0.3s ease;
            cursor: pointer;
            text-decoration: none;
            color: var(--primary-color);
        }

        .social-icon:hover {
            background-color: var(--primary-color);
            color: white;
            transform: translateY(-4px);
        }

        .office-hours {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }

        .office-hours h4 {
            margin-bottom: 15px;
            font-size: 16px;
        }

        .office-hours-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .office-hours-item:last-child {
            margin-bottom: 0;
        }

        .response-time {
            background: linear-gradient(135deg, var(--secondary-color), #2980b9);
            color: white;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            text-align: center;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .contact-container {
                grid-template-columns: 1fr;
                gap: 30px;
            }
        }

        .faq-section {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: var(--shadow);
            margin-top: 40px;
        }

        .faq-section h2 {
            color: var(--title-color);
            margin-bottom: 25px;
            font-size: 20px;
            border-bottom: 3px solid var(--title-color);
            padding-bottom: 15px;
        }

        .faq-item {
            margin-bottom: 20px;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 20px;
        }

        .faq-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .faq-question {
            font-weight: 600;
            color: var(--dark-color);
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .faq-question:hover {
            background-color: var(--light-color);
        }

        .faq-toggle {
            font-size: 18px;
            transition: transform 0.3s ease;
        }

        .faq-answer {
            color: #666;
            padding: 15px 10px;
            margin-top: 10px;
            display: none;
            border-left: 3px solid var(--primary-color);
            padding-left: 20px;
        }

        .faq-answer.active {
            display: block;
        }

        .faq-toggle.active {
            transform: rotate(180deg);
        }
    </style>

    <!-- Contact Container -->
    <div class="contact-container">
        <!-- Contact Info -->
        <div class="contact-info">
            <h2><i class="fas fa-map-pin"></i> Informasi Kontak</h2>

            <div class="contact-item">
                <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
                <div class="contact-details">
                    <h3>Lokasi</h3>
                    <p>
                        Jl. Raya perkebunan No. 123<br>
                        Pekanbaru, Riau 28001<br>
                        Indonesia
                    </p>
                </div>
            </div>

            <div class="contact-item">
                <div class="contact-icon"><i class="fas fa-phone-alt"></i></div>
                <div class="contact-details">
                    <h3>Telepon</h3>
                    <p>
                        <a href="tel:+621234567890">(+62) 123 456 7890</a><br>
                        <a href="tel:+621098765432">(+62) 109 876 5432</a>
                    </p>
                </div>
            </div>

            <div class="contact-item">
                <div class="contact-icon"><i class="fas fa-envelope"></i></div>
                <div class="contact-details">
                    <h3>Email</h3>
                    <p>
                        <a href="mailto:info@gulmatrack.com">info@gulmatrack.com</a><br>
                        <a href="mailto:support@gulmatrack.com">support@gulmatrack.com</a>
                    </p>
                </div>
            </div>

            <div class="contact-item">
                <div class="contact-icon"><i class="fas fa-globe"></i></div>
                <div class="contact-details">
                    <h3>Website & Media Sosial</h3>
                    <p>
                        <a href="#">www.gulmatrack.com</a>
                    </p>
                </div>
            </div>

            <!-- Social Links -->
            <div class="social-links">
                <a href="#" class="social-icon" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="social-icon" title="Twitter"><i class="fab fa-twitter"></i></a>
                <a href="#" class="social-icon" title="Instagram"><i class="fab fa-instagram"></i></a>
                <a href="#" class="social-icon" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
            </div>

            <!-- Office Hours -->
            <div class="office-hours">
                <h4><i class="fas fa-clock"></i> Jam Kantor</h4>
                <div class="office-hours-item">
                    <span>Senin - Jumat</span>
                    <span>08:00 - 17:00</span>
                </div>
                <div class="office-hours-item">
                    <span>Sabtu</span>
                    <span>09:00 - 13:00</span>
                </div>
                <div class="office-hours-item">
                    <span>Minggu & Hari Libur</span>
                    <span>Tutup</span>
                </div>
            </div>

            <div class="response-time">
                <i class="fas fa-comments"></i> Kami biasanya merespons dalam waktu kurang dari 24 jam
            </div>
        </div>

        <!-- Contact Form -->
        <div class="form-section">
            <h2><i class="fas fa-envelope"></i> Kirim Pesan</h2>
            <div class="success-message" id="successMessage">
                <i class="fas fa-check-circle"></i> Pesan Anda telah berhasil dikirim! Kami akan segera menghubungi Anda.
            </div>

            <form onsubmit="sendMessage(event)">
                <div class="form-group">
                    <label for="name">Nama Lengkap *</label>
                    <input type="text" id="name" name="name" required placeholder="Masukkan nama Anda">
                </div>

                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" required placeholder="example@email.com">
                </div>

                <div class="form-group">
                    <label for="phone">Nomor Telepon</label>
                    <input type="tel" id="phone" name="phone" placeholder="(+62) 123 456 7890">
                </div>

                <div class="form-group">
                    <label for="subject">Subjek *</label>
                    <select id="subject" name="subject" required>
                        <option value="">Pilih subjek...</option>
                        <option value="support">Dukungan Teknis</option>
                        <option value="feature">Permintaan Fitur</option>
                        <option value="partnership">Kemitraan</option>
                        <option value="feedback">Feedback & Saran</option>
                        <option value="other">Lainnya</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="message">Pesan *</label>
                    <textarea id="message" name="message" required placeholder="Tuliskan pesan Anda di sini..."></textarea>
                </div>

                <button type="submit" class="submit-btn"><i class="fas fa-paper-plane"></i> Kirim Pesan</button>
            </form>
        </div>
    </div>

    <!-- FAQ Section -->
    <div class="faq-section">
        <h2><i class="fas fa-question-circle"></i> Pertanyaan Umum (FAQ)</h2>

        <div class="faq-item">
            <div class="faq-question" onclick="toggleFAQ(this)">
                <span>Bagaimana cara mendaftar di GulmaTrack?</span>
                <span class="faq-toggle">▼</span>
            </div>
            <div class="faq-answer">
                Anda dapat mendaftar melalui tombol "Daftar" di halaman beranda. Siapkan data diri dan informasi 
                tentang area perkebunan Anda, kemudian ikuti proses verifikasi yang akan dilakukan oleh tim kami.
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question" onclick="toggleFAQ(this)">
                <span>Apakah GulmaTrack gratis digunakan?</span>
                <span class="faq-toggle">▼</span>
            </div>
            <div class="faq-answer">
                GulmaTrack menawarkan versi gratis dengan fitur dasar dan versi premium dengan fitur lengkap. 
                Hubungi kami untuk informasi lebih detail tentang paket pricing yang sesuai dengan kebutuhan Anda.
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question" onclick="toggleFAQ(this)">
                <span>Berapa lama proses verifikasi data saya?</span>
                <span class="faq-toggle">▼</span>
            </div>
            <div class="faq-answer">
                Proses verifikasi biasanya memakan waktu 2-5 hari kerja tergantung kelengkapan dokumen yang Anda 
                kirimkan. Tim kami akan menghubungi Anda jika ada data yang perlu dikonfirmasi lebih lanjut.
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question" onclick="toggleFAQ(this)">
                <span>Bagaimana cara mengakses peta interaktif?</span>
                <span class="faq-toggle">▼</span>
            </div>
            <div class="faq-answer">
                Setelah login, Anda dapat mengakses peta interaktif melalui menu "Wilayah" di navbar. Peta 
                menampilkan visualisasi geografis semua area produksi dengan informasi detail yang dapat diklik.
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question" onclick="toggleFAQ(this)">
                <span>Apakah data saya aman di GulmaTrack?</span>
                <span class="faq-toggle">▼</span>
            </div>
            <div class="faq-answer">
                Ya, kami menggunakan enkripsi tingkat enterprise dan protokol keamanan terkini untuk melindungi 
                data Anda. Semua informasi disimpan di server yang secure dan di-backup secara berkala.
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question" onclick="toggleFAQ(this)">
                <span>Bagaimana jika saya menemukan bug atau error?</span>
                <span class="faq-toggle">▼</span>
            </div>
            <div class="faq-answer">
                Silakan laporkan bug melalui email support@gulmatrack.com atau gunakan form kontak di halaman ini. 
                Sertakan detail tentang error yang Anda temukan dan screenshot jika memungkinkan. Tim kami akan 
                segera menanggani laporan Anda.
            </div>
        </div>
    </div>
</div>

<script>
    function sendMessage(event) {
        event.preventDefault();

        const name = document.getElementById('name').value;
        const email = document.getElementById('email').value;
        const subject = document.getElementById('subject').value;
        const message = document.getElementById('message').value;

        // Validasi
        if (!name || !email || !subject || !message) {
            alert('Silakan lengkapi semua field yang wajib diisi');
            return;
        }

        // Simulate sending message
        console.log({
            name: name,
            email: email,
            subject: subject,
            message: message
        });

        // Show success message
        const successMsg = document.getElementById('successMessage');
        successMsg.style.display = 'block';

        // Reset form
        event.target.reset();

        // Hide success message after 5 seconds
        setTimeout(() => {
            successMsg.style.display = 'none';
        }, 5000);
    }

    function toggleFAQ(element) {
        const answer = element.nextElementSibling;
        const toggle = element.querySelector('.faq-toggle');

        // Close other FAQs
        document.querySelectorAll('.faq-answer.active').forEach(item => {
            if (item !== answer) {
                item.classList.remove('active');
                item.previousElementSibling.querySelector('.faq-toggle').classList.remove('active');
            }
        });

        // Toggle current FAQ
        answer.classList.toggle('active');
        toggle.classList.toggle('active');
    }
</script>
@endsection
