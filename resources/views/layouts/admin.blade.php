{{-- resources/views/layouts/admin.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin BusGO')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome untuk ikon (opsional, tapi digunakan di sidebar sebelumnya) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body { display: flex; min-height: 100vh; flex-direction: column; background-color: #f8f9fa; }
        .admin-layout { display: flex; flex-grow: 1; }
        .sidebar { width: 250px; background-color: #0d6efd; /* Warna biru tema Anda */ color: white; padding-top: 1rem;}
        .sidebar a { color: rgba(255,255,255,.8); text-decoration: none; display: block; padding: .75rem 1.25rem; }
        .sidebar a:hover, .sidebar .nav-item.active a { color: white; background-color: #0b5ed7; /* Biru lebih gelap saat hover/active */ }
        .sidebar .nav-icon { margin-right: 0.5rem; }
        .sidebar-header { padding: 1.5rem; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.2); margin-bottom: 1rem;}
        .sidebar-header h3 { color: white; margin:0; font-size: 1.5rem;}
        .main-content { flex-grow: 1; padding: 2rem; }
        .content-header h1 { margin-bottom: 1.5rem; }
        .card-header .btn { margin-left: auto; }
        /* Custom styling untuk tombol hapus agar lebih kecil dan merah */
        .btn-delete-custom {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            line-height: 1.5;
            border-radius: 0.2rem;
        }
    </style>
</head>
<body>
    <div class="admin-layout">
        @include('partials.admin_sidebar') {{-- Kita akan buat file partials/admin_sidebar.blade.php --}}

        <main class="main-content">
            <header class="content-header">
                <h1>@yield('page_title', 'Dashboard')</h1>
            </header>
            <section class="content-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </section>
        </main>
    </div>

    <!-- Bootstrap JS Bundle (Popper.js disertakan) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>