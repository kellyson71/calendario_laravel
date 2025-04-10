<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Ficha do Cliente') - Sistema de Agendamento</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome via CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        @media print {
            body {
                font-size: 12pt;
                background: white !important;
            }

            .no-print {
                display: none !important;
            }

            .print-only {
                display: block !important;
            }

            .page-break {
                page-break-before: always;
            }

            .print-container {
                width: 100% !important;
                max-width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .bg-primary {
                background-color: #ccc !important;
                color: #000 !important;
            }

            .text-white {
                color: #000 !important;
            }
        }

        .print-only {
            display: none;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
        }

        .form-header {
            border-bottom: 2px solid #333;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }

        .form-footer {
            border-top: 1px solid #ddd;
            margin-top: 30px;
            padding-top: 10px;
            font-size: 0.8em;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-label {
            font-weight: bold;
        }

        .signature-line {
            border-top: 1px solid #333;
            margin-top: 30px;
            padding-top: 10px;
            width: 80%;
            text-align: center;
            margin-left: auto;
            margin-right: auto;
        }
    </style>

    @yield('styles')
</head>

<body>
    <div class="container print-container my-4 bg-white p-5 shadow-md">
        <!-- Botões de impressão (visível apenas na tela) -->
        <div class="no-print mb-4 text-end">
            <button onclick="window.print()" class="btn btn-primary">
                <i class="fas fa-print me-2"></i> Imprimir Ficha
            </button>
            <a href="javascript:history.back()" class="btn btn-secondary ms-2">
                <i class="fas fa-arrow-left me-2"></i> Voltar
            </a>
        </div>

        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Script para evitar erros de sintaxe e melhorar o carregamento
        window.addEventListener('DOMContentLoaded', function() {
            console.log('Página de impressão carregada');

            // Gerenciador de erros global para evitar quebras de scripts
            window.addEventListener('error', function(e) {
                console.log('Erro identificado e tratado:', e.message);
                // Previne que o erro quebre a página
                e.preventDefault();
                return true;
            });
        });
    </script>

    @yield('scripts')
</body>

</html>