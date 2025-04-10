<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Agendamento</title>

    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'dark-blue': '#0f2d5e',
                        'light-blue': '#4b9cd3',
                    },
                }
            }
        }
    </script>

    <!-- Font Awesome via CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #4b9cd3 0%, #0f2d5e 100%);
            height: 100vh;
            margin: 0;
        }

        .login-box {
            background-color: #0f2d5e;
            border-radius: 0.75rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .input-field {
            background-color: rgba(255, 255, 255, 0.95);
            transition: all 0.3s ease;
        }

        .input-field:focus {
            background-color: #fff;
            box-shadow: 0 0 0 2px rgba(75, 156, 211, 0.5);
        }

        .login-btn {
            background: linear-gradient(90deg, #3182ce 0%, #1e4a94 100%);
            transition: all 0.3s ease;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(15, 45, 94, 0.35);
        }
    </style>
</head>

<body>
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8 p-10 rounded-xl shadow-2xl login-box text-white">
            <div class="text-center">
                <img class="mx-auto h-24 w-auto" src="{{ asset('img/logo.png') }}" alt="Logo">
                <h2 class="mt-6 text-3xl font-extrabold text-white">
                    Sistema de Agendamento
                </h2>
                <p class="mt-2 text-sm text-gray-300">
                    Entre com seu usuário e senha
                </p>
            </div>

            <form class="mt-8 space-y-6" action="{{ route('login') }}" method="POST">
                @csrf

                @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-md" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="rounded-md -space-y-px">
                    <div class="mb-5">
                        <label for="username" class="block text-sm font-medium text-gray-300 mb-2">Usuário</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-500"></i>
                            </div>
                            <input id="username" name="username" type="text" required class="input-field pl-10 appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-light-blue focus:border-light-blue focus:z-10 sm:text-sm" placeholder="Insira seu usuário">
                        </div>
                    </div>

                    <div class="mb-5">
                        <label for="password" class="block text-sm font-medium text-gray-300 mb-2">Senha</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-500"></i>
                            </div>
                            <input id="password" name="password" type="password" required class="input-field pl-10 appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-light-blue focus:border-light-blue focus:z-10 sm:text-sm" placeholder="Insira sua senha">
                        </div>
                    </div>
                </div>

                <div>
                    <button type="submit" class="login-btn group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-light-blue">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <i class="fas fa-sign-in-alt"></i>
                        </span>
                        Entrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>