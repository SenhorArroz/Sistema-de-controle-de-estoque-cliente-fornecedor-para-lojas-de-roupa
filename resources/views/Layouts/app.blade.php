<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Controle de Estoque') - Nome da Loja</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            padding-top: 70px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #f8f9fa;
        }
        .main-content {
            flex: 1;
        }
        .navbar-brand.mx-auto {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
        }
    </style>
</head>
<body>

    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top shadow">
            <div class="container-fluid">
                <a class="navbar-brand mx-auto" href="{{ route('dashboard') }}">
                    Nome da Loja
                </a>
                <button class="btn btn-outline-light" type="button" data-bs-toggle="offcanvas" data-bs-target="#navigationOffcanvas" aria-controls="navigationOffcanvas">
                    <i class="bi bi-list fs-4"></i>
                </button>
            </div>
        </nav>
    </header>

    <main class="main-content">
        @yield('content')
    </main>

    <footer class="bg-dark text-white text-center p-3 mt-auto">
        <div class="container">
            <p class="mb-0">&copy; {{ date('Y') }} Nome da Loja. Todos os direitos reservados.</p>
            <p class="mb-0 small">Suporte: seu.email@provedor.com</p>
        </div>
    </footer>


    <div class="offcanvas offcanvas-end text-bg-dark" tabindex="-1" id="navigationOffcanvas" aria-labelledby="navigationOffcanvasLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="navigationOffcanvasLabel">Navegação</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <p>Selecione uma das opções.</p>
            <div class="d-grid gap-3">
                <a href="{{ route('dashboard') }}" class="btn btn-light text-dark">
                    <i class="bi bi-house-fill me-2"></i> Dashboard
                </a>

                <a href="{{ route('clientes.index') }}" class="btn btn-light text-dark">
                    <i class="bi bi-people-fill me-2"></i> Clientes
                </a>

                <div class="btn-group w-100">
                    <button type="button" class="btn btn-light text-dark dropdown-toggle d-flex justify-content-center align-items-center" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-box-seam-fill me-2"></i> Estoque
                    </button>
                    <ul class="dropdown-menu w-100">
                        <li>
                            <a class="dropdown-item" href="{{ route('produtos.index') }}">
                                <i class="bi bi-boxes me-2"></i> Produtos
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('categorias.index') }}">
                                <i class="bi bi-tags-fill me-2"></i> Categorias
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('tamanhos.index') }}">
                                <i class="bi bi-rulers me-2"></i> Tamanhos
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('cores.index') }}">
                                <i class="bi bi-palette-fill me-2"></i> Cores
                            </a>
                        </li>
                    </ul>
                </div>

                <a href="{{ route('fornecedores.index') }}" class="btn btn-light text-dark">
                    <i class="bi bi-truck me-2"></i> Fornecedores
                </a>
                <a href="#" class="btn btn-light text-dark">
                    <i class="bi bi-arrow-down-up me-2"></i> Movimentos
                </a>
            </div>
            <hr>
            <div class="d-grid">
                 <a href="#" class="btn btn-danger">
                    <i class="bi bi-box-arrow-right me-2"></i> Sair do Sistema
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
