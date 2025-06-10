<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ITventory - Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #3b82f6;
            --primary-color-hover: #2563eb;
            --text-color: #e2e8f0;
            --text-color-muted: #94a3b8;
            --glass-bg: rgba(30, 30, 45, 0.4);
            --glass-border: rgba(255, 255, 255, 0.15);
            --input-bg: rgba(255, 255, 255, 0.05);
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Poppins', sans-serif;
            background-image: url('img/Background.jpg');
            background-size: cover;
            background-position: center;
            background-color: #111827;
            color: var(--text-color);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 1rem;
        }
        .login-form-container {
            width: 100%;
            max-width: 420px;
            background: var(--glass-bg);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border: 1px solid var(--glass-border);
            box-shadow: 0 10px 35px rgba(0, 0, 0, 0.2);
            border-radius: 16px;
            padding: 3rem;
        }
        .form-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 2rem;
        }
        .form-header img { height: 50px; }
        .form-header h2 { font-size: 2rem; font-weight: 600; color: #fff; margin: 0; }
        .form-title h3 { font-size: 1.75rem; font-weight: 500; color: #fff; margin-bottom: 0.5rem; }
        .form-title p { font-size: 1rem; font-weight: 300; color: var(--text-color-muted); margin-bottom: 2.5rem; }
        .input-group { margin-bottom: 1.5rem; }
        .input-group label {
            display: block;
            font-size: 0.875rem;
            font-weight: 400;
            color: var(--text-color-muted);
            margin-bottom: 0.5rem;
        }
        .input-group input {
            width: 100%;
            background: var(--input-bg);
            border: 1px solid var(--glass-border);
            padding: 14px 16px;
            border-radius: 8px;
            color: var(--text-color);
            font-size: 1rem;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s ease;
        }
        .input-group input::placeholder { color: var(--text-color-muted); }
        .input-group input:focus {
            outline: none;
            border-color: var(--primary-color);
            background: rgba(59, 130, 246, 0.1);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
        }
        .submit-button {
            width: 100%;
            padding: 16px;
            margin-top: 10px;
            border-radius: 8px;
            background-color: var(--primary-color);
            color: #fff;
            font-size: 1rem;
            font-weight: 500;
            letter-spacing: 0.5px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease-in-out;
        }
        .submit-button:hover {
            background-color: var(--primary-color-hover);
            box-shadow: 0 5px 15px rgba(59, 130, 246, 0.4);
            transform: translateY(-3px);
        }

        /* ===== CSS BARU UNTUK IKON MATA ===== */
        .password-wrapper { position: relative; }
        .input-group input#password { padding-right: 45px; }
        .toggle-password {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--text-color-muted);
            transition: color 0.3s ease;
        }
        .toggle-password:hover { color: var(--text-color); }
        .toggle-password svg { width: 20px; height: 20px; }
        .icon-hide { display: none; }

        .error-message {
            display: flex; /* Menggunakan flexbox untuk mensejajarkan ikon dan teks */
            align-items: center;
            background-color: rgba(239, 68, 68, 0.15); /* Warna merah lebih soft dengan transparansi */
            color: #fca5a5; /* Warna teks merah terang yang cocok dengan background */
            padding: 12px 16px; /* Padding lebih kecil (tinggi 12px, lebar 16px) */
            border: 1px solid rgba(239, 68, 68, 0.3); /* Border semi-transparan */
            border-radius: 8px;
            margin-bottom: 1.25rem; /* Margin bawah sedikit dikurangi */
            font-size: 0.9rem; /* Font lebih kecil */
            font-weight: 400;

            /* Animasi halus saat muncul */
            animation: fadeInDown 0.4s ease-out;
        }

        .error-message svg {
            width: 20px;
            height: 20px;
            margin-right: 10px; /* Jarak antara ikon dan teks */
            flex-shrink: 0; /* Mencegah ikon menyusut */
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

    </style>
</head>
<body>
    <div class="login-form-container">
        <div class="form-header">
            <img src="img/Scuto-logo.svg" alt="Logo ITventoris">
            <h2>ITventoris</h2>
        </div>
        <div class="form-title">
            <h3>Selamat Datang!</h3>
            <p>Silakan masuk untuk melanjutkan.</p>
        </div>

    <form method="POST" action="{{ route('login.post') }}">
        @csrf
        <div class="input-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="example@gmail.com" value="{{ old('email') }}" required autocomplete="email" autofocus>
        </div>
        
        <div class="input-group">
            <label for="password">Password</label>
            <div class="password-wrapper">
                <input type="password" id="password" name="password" placeholder="Masukkan password Anda" required autocomplete="current-password">
                <span id="togglePassword" class="toggle-password">
                    <svg id="icon-eye" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <svg id="icon-eye-slash" class="icon-hide" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.243 4.243l-4.243-4.243" />
                    </svg>
                </span>
            </div>
        </div>

        @if ($errors->any())
        <div class="error-message">
            {{-- Ikon Peringatan (SVG) --}}
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
            </svg>
            {{-- Teks Pesan Error --}}
            <span>{{ $errors->first() }}</span>
        </div>
        @endif

        <button type="submit" class="submit-button">Masuk</button>
    </form>
</div>

    <script>
        // === LOGIKA UNTUK MENAMPILKAN/SEMBUNYIKAN PASSWORD ===
        const togglePasswordButton = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('icon-eye');
        const eyeSlashIcon = document.getElementById('icon-eye-slash');

        togglePasswordButton.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            eyeIcon.classList.toggle('icon-hide');
            eyeSlashIcon.classList.toggle('icon-hide');
        });
    </script>
</body>
</html>