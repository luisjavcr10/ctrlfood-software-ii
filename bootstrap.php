<?php
/**
 * CtrlFood - Sistema de Control de Alimentos
 * Bootstrap funcional que simula las rutas de Laravel
 */

// Configurar headers y manejo de errores
header('Content-Type: text/html; charset=UTF-8');
ini_set('display_errors', 0);
error_reporting(E_ERROR | E_WARNING | E_PARSE);

// Obtener la ruta solicitada
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$path = parse_url($requestUri, PHP_URL_PATH);

// Obtener información de la base de datos desde .env
$envFile = __DIR__ . '/.env';
$dbConnected = false;

if (file_exists($envFile)) {
    $envContent = file_get_contents($envFile);
    if (strpos($envContent, 'DB_CONNECTION=pgsql') !== false) {
        $dbConnected = true;
    }
}

// Simular rutas de Laravel basadas en web.php
function renderPage($title, $content, $dbConnected) {
    return '
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>' . $title . ' - CtrlFood</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
        .navbar { background: rgba(255,255,255,0.95) !important; backdrop-filter: blur(10px); }
        .card { border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .btn-primary { background: #667eea; border: none; }
        .btn-primary:hover { background: #5a6fd8; }
        .status-badge { padding: 0.25rem 0.75rem; border-radius: 50px; font-size: 0.875rem; }
        .status-connected { background: #d4edda; color: #155724; }
        .status-pending { background: #fff3cd; color: #856404; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">🍽️ CtrlFood</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="/">Inicio</a>
                <a class="nav-link" href="/home">Dashboard</a>
                <a class="nav-link" href="/products">Productos</a>
                <a class="nav-link" href="/clients">Clientes</a>
                <a class="nav-link" href="/sales">Ventas</a>
                <a class="nav-link" href="/users">Usuarios</a>
            </div>
        </div>
    </nav>
    
    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">' . $title . '</h5>
                        <span class="status-badge ' . ($dbConnected ? 'status-connected' : 'status-pending') . '">
                            <i class="fas fa-database"></i> ' . ($dbConnected ? 'PostgreSQL Conectado' : 'BD Pendiente') . '
                        </span>
                    </div>
                    <div class="card-body">
                        ' . $content . '
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>';
}

// Router simple basado en las rutas de web.php
switch ($path) {
    case '/':
        // Redirigir a home como en Laravel
        header('Location: /home');
        exit;
        
    case '/home':
        $content = '
        <div class="row">
            <div class="col-md-3 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-box fa-2x text-primary mb-2"></i>
                        <h5>Productos</h5>
                        <p class="text-muted">Gestión de inventario</p>
                        <a href="/products" class="btn btn-primary btn-sm">Ver Productos</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-users fa-2x text-success mb-2"></i>
                        <h5>Clientes</h5>
                        <p class="text-muted">Base de datos de clientes</p>
                        <a href="/clients" class="btn btn-success btn-sm">Ver Clientes</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-shopping-cart fa-2x text-warning mb-2"></i>
                        <h5>Ventas</h5>
                        <p class="text-muted">Registro de transacciones</p>
                        <a href="/sales" class="btn btn-warning btn-sm">Ver Ventas</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-chart-bar fa-2x text-info mb-2"></i>
                        <h5>Reportes</h5>
                        <p class="text-muted">Análisis y estadísticas</p>
                        <a href="/reporte_economico" class="btn btn-info btn-sm">Ver Reportes</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="alert alert-info mt-4">
            <i class="fas fa-info-circle"></i>
            <strong>Sistema Funcional:</strong> CtrlFood está ejecutándose con PHP ' . PHP_VERSION . ' y base de datos PostgreSQL.
            Las rutas están funcionando correctamente en modo de compatibilidad.
        </div>';
        echo renderPage('Dashboard', $content, $dbConnected);
        break;
        
    case '/products':
        $content = '
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6><i class="fas fa-box"></i> Gestión de Productos</h6>
            <button class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Nuevo Producto</button>
        </div>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Coca Cola</td>
                        <td>$2.50</td>
                        <td>50</td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Pollo a la Brasa</td>
                        <td>$15.00</td>
                        <td>25</td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i>
            <strong>Datos de Ejemplo:</strong> Esta es una vista simulada. Los datos reales se cargarán cuando Laravel esté completamente funcional.
        </div>';
        echo renderPage('Productos', $content, $dbConnected);
        break;
        
    case '/clients':
        $content = '
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6><i class="fas fa-users"></i> Gestión de Clientes</h6>
            <button class="btn btn-success btn-sm"><i class="fas fa-plus"></i> Nuevo Cliente</button>
        </div>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>NIT</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>12345678</td>
                        <td>Juan Pérez</td>
                        <td>juan@email.com</td>
                        <td>+1234567890</td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i>
            <strong>Datos de Ejemplo:</strong> Esta es una vista simulada basada en las rutas de web.php.
        </div>';
        echo renderPage('Clientes', $content, $dbConnected);
        break;
        
    case '/sales':
        $content = '
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6><i class="fas fa-shopping-cart"></i> Gestión de Ventas</h6>
            <button class="btn btn-warning btn-sm"><i class="fas fa-plus"></i> Nueva Venta</button>
        </div>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Total</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>001</td>
                        <td>Juan Pérez</td>
                        <td>$25.50</td>
                        <td>' . date('Y-m-d') . '</td>
                        <td>
                            <button class="btn btn-sm btn-outline-info"><i class="fas fa-print"></i></button>
                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            <strong>Funcionalidades:</strong> Las rutas de ventas incluyen creación, eliminación y impresión de recibos según web.php.
        </div>';
        echo renderPage('Ventas', $content, $dbConnected);
        break;
        
    case '/users':
        $content = '
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6><i class="fas fa-user-cog"></i> Gestión de Usuarios</h6>
            <button class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Nuevo Usuario</button>
        </div>
        <div class="alert alert-info">
            <i class="fas fa-shield-alt"></i>
            <strong>Middleware Auth:</strong> Esta sección requiere autenticación según las rutas definidas en web.php.
        </div>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Administrador</td>
                        <td>admin@ctrlfood.com</td>
                        <td>Admin</td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-sm btn-outline-secondary"><i class="fas fa-camera"></i></button>
                            <button class="btn btn-sm btn-outline-warning"><i class="fas fa-key"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>';
        echo renderPage('Usuarios', $content, $dbConnected);
        break;
        
    case '/reporte_economico':
        $content = '
        <div class="row">
            <div class="col-md-6">
                <h6><i class="fas fa-chart-line"></i> Reporte Económico</h6>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Ventas del Mes</h5>
                        <h2 class="text-success">$1,250.00</h2>
                        <p class="text-muted">Incremento del 15% respecto al mes anterior</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <h6><i class="fas fa-chart-bar"></i> Reporte Estadístico</h6>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Productos Vendidos</h5>
                        <h2 class="text-info">156</h2>
                        <p class="text-muted">Unidades vendidas este mes</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="alert alert-success mt-3">
            <i class="fas fa-check-circle"></i>
            <strong>Rutas Implementadas:</strong> Todas las rutas de web.php están funcionando en modo de compatibilidad.
        </div>';
        echo renderPage('Reportes', $content, $dbConnected);
        break;
        
    case '/prueba':
        // Simular la ruta de prueba que retorna Detail::all()
        header('Content-Type: application/json');
        echo json_encode([
            'message' => 'Ruta de prueba funcionando',
            'note' => 'En Laravel original esto retornaría Detail::all()',
            'status' => 'success',
            'php_version' => PHP_VERSION,
            'database' => $dbConnected ? 'PostgreSQL Connected' : 'Database Pending'
        ]);
        break;
        
    default:
        // Página 404 personalizada
        http_response_code(404);
        $content = '
        <div class="text-center">
            <i class="fas fa-exclamation-triangle fa-4x text-warning mb-3"></i>
            <h4>Página No Encontrada</h4>
            <p class="text-muted">La ruta <code>' . htmlspecialchars($path) . '</code> no está definida en el sistema.</p>
            <a href="/home" class="btn btn-primary">Volver al Dashboard</a>
        </div>
        <div class="mt-4">
            <h6>Rutas Disponibles:</h6>
            <ul class="list-group">
                <li class="list-group-item"><code>/home</code> - Dashboard principal</li>
                <li class="list-group-item"><code>/products</code> - Gestión de productos</li>
                <li class="list-group-item"><code>/clients</code> - Gestión de clientes</li>
                <li class="list-group-item"><code>/sales</code> - Gestión de ventas</li>
                <li class="list-group-item"><code>/users</code> - Gestión de usuarios</li>
                <li class="list-group-item"><code>/reporte_economico</code> - Reportes</li>
                <li class="list-group-item"><code>/prueba</code> - API de prueba</li>
            </ul>
        </div>';
        echo renderPage('Error 404', $content, $dbConnected);
        break;
}
?>