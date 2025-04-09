<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Agendamento</title>

    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome via CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- FullCalendar via CDN -->
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/locales-all.min.js'></script>

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow: hidden;
        }

        .sidebar {
            width: 250px;
            transition: all 0.3s;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            overflow-y: auto;
            z-index: 50;
        }

        .topbar {
            position: fixed;
            top: 0;
            right: 0;
            left: 250px;
            z-index: 40;
        }

        .main-content {
            margin-left: 250px;
            margin-top: 60px;
            height: calc(100vh - 60px);
            overflow-y: auto;
        }

        @media (max-width: 768px) {
            .sidebar {
                margin-left: -250px;
            }

            .sidebar.active {
                margin-left: 0;
            }

            .topbar {
                left: 0;
            }

            .main-content {
                margin-left: 0;
            }

            .sidebar.active+.main-wrapper .topbar {
                left: 250px;
            }

            .sidebar.active+.main-wrapper .main-content {
                margin-left: 250px;
            }
        }
    </style>

    @yield('styles')
</head>

<body class="bg-gray-100">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        @include('layouts.sidebar')

        <!-- Main wrapper -->
        <div class="main-wrapper flex-1">
            <!-- Cabeçalho (Topbar) -->
            <header class="topbar bg-white shadow">
                <div class="container mx-auto px-4 py-3 flex justify-between items-center">
                    <div class="flex items-center">
                        <button id="sidebarToggle" class="text-gray-500 focus:outline-none md:hidden">
                            <i class="fas fa-bars"></i>
                        </button>
                        <h1 class="ml-4 text-xl font-semibold text-gray-700">@yield('title', 'Sistema de Agendamento')</h1>
                    </div>
                    <div class="flex items-center">
                        @auth
                        <span class="mr-4 text-gray-600">{{ Auth::user()->name }}</span>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-red-500 hover:text-red-700">
                                <i class="fas fa-sign-out-alt mr-1"></i> Sair
                            </button>
                        </form>
                        @endauth
                    </div>
                </div>
            </header>

            <!-- Conteúdo da página -->
            <main class="main-content bg-gray-100 p-4">
                <div class="container mx-auto">
                    @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                        <p>{{ session('success') }}</p>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                        <p>{{ session('error') }}</p>
                    </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script>
        // Toggle sidebar on mobile
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('active');
        });
    </script>

    @yield('scripts')
</body>

</html>