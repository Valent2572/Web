<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ToDo - Premium Task Management</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --bg-color: #1e272e;
            --surface-color: #121212;
            --accent-color: #d4af37;
            --text-color: #f5f5f5;
            --muted-text: #8e8e8e;
            --gold-gradient: linear-gradient(135deg, #d4af37 0%, #f9d976 50%, #d4af37 100%);
            --gunmetal-gradient: linear-gradient(135deg, #1e272e 0%, #2f3640 100%);
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 50px;
        }

        .logo {
            font-size: 2.5rem;
            font-weight: 800;
            background: var(--gold-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -2px;
        }

        .btn {
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: none;
        }

        .btn-gold {
            background: var(--gold-gradient);
            color: #000;
            box-shadow: 0 10px 20px rgba(212, 175, 55, 0.2);
        }

        .btn-gold:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(212, 175, 55, 0.4);
        }

        .card {
            background: var(--surface-color);
            border: 1px solid rgba(212, 175, 55, 0.1);
            border-radius: 20px;
            padding: 24px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--gold-gradient);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .card:hover {
            transform: translateX(5px);
            border-color: rgba(212, 175, 55, 0.3);
        }

        .card:hover::before {
            opacity: 1;
        }

        .alert {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            background: rgba(46, 204, 113, 0.1);
            border: 1px solid #2ecc71;
            color: #2ecc71;
        }

        /* Glassmorphism elements */
        .glass {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal {
            background: var(--bg-color);
            padding: 40px;
            border-radius: 30px;
            width: 100%;
            max-width: 500px;
            border: 1px solid rgba(212, 175, 55, 0.2);
            position: relative;
        }

        input, textarea {
            width: 100%;
            padding: 15px;
            background: var(--surface-color);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            color: #fff;
            margin-bottom: 20px;
            font-family: inherit;
            box-sizing: border-box;
        }

        input:focus, textarea:focus {
            outline: none;
            border-color: var(--accent-color);
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: var(--muted-text);
            font-size: 0.9rem;
        }

        .checkbox-container {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            cursor: pointer;
        }

        .checkbox-container input {
            width: auto;
            margin-bottom: 0;
        }

        .badge {
            padding: 4px 8px;
            border-radius: 6px;
            font-weight: 800;
            letter-spacing: 0.5px;
        }

        .badge-low { background: #3498db; color: #fff; }
        .badge-medium { background: #f39c12; color: #fff; }
        .badge-high { background: #e74c3c; color: #fff; }
        .badge-late { background: #e74c3c; border: 1px solid #fff; color: #fff; }

    </style>
    @yield('styles')
</head>
<body>
    <div class="container">
        @if(session('success'))
            <div class="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @yield('content')

        <footer style="margin-top: 80px; text-align: center; color: var(--muted-text); font-size: 0.8rem; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 30px;">
            <p>&copy; {{ date('Y') }} ToDo - Premium Task Management. Built for productivity.</p>
        </footer>
    </div>

    @yield('scripts')
</body>
</html>
