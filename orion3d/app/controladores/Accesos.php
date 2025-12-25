<?php
class Accesos extends Controlador
{
    private AccesosModel $accesosModel;

    public function __construct()
    {
        requireLogin();
        refreshAccesosSiCambiaVersion();
        $this->accesosModel = $this->modelo('AccesosModel');
    }

    public function asignaciones()
    {
        requirePermiso('accesos.asignaciones.editar');

        $roles = $this->accesosModel->rolesListar();
        $permisos = $this->accesosModel->permisosListar();
        $map = $this->accesosModel->mapRolPermiso();

        $this->vista('layouts/panel', [
            'titulo' => 'Accesos | Asignaciones',
            'contenido' => RUTA_APP . '/vistas/accesos/asignaciones/inicio.php',
            'roles' => $roles,
            'permisos' => $permisos,
            'map' => $map
        ]);
    }

    public function asignacionesGuardar()
    {
        requirePermiso('accesos.asignaciones.editar');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . RUTA_URL . "/accesos/asignaciones");
            exit;
        }

        $rolId = (int)($_POST['rol_id'] ?? 0);
        $permisos = $_POST['permisos'] ?? [];

        $res = $this->accesosModel->guardarPermisosDeRol($rolId, $permisos);
        $ok = $res['ok'];
        $code = $res['code'];
        if ($ok && !empty($_SESSION['id_usuario'])) {
            $miId = (int)$_SESSION['id_usuario'];
            $misRoles = $this->accesosModel->obtenerRolesDeUsuario($miId);

            // si el rol editado está en mis roles -> recalcular permisos sesión
            $rolEditadoNombre = null;
            foreach ($this->accesosModel->rolesListar() as $r) {
                if ((int)$r['id'] === $rolId) $rolEditadoNombre = $r['nombre'];
            }

            if ($rolEditadoNombre && in_array($rolEditadoNombre, $misRoles, true)) {
                $permsN = $this->accesosModel->obtenerPermisosDeUsuario($miId);
                $_SESSION['permisos'] = [];
                foreach ($permsN as $p) $_SESSION['permisos'][$p] = true;
            }
        }


        header("Location: " . RUTA_URL . "/accesos/asignaciones?ok=" . ($res['ok'] ? "1" : "0") . "&msg=" . urlencode($code));
        exit;
    }

    public function usuarios()
    {
        requirePermiso('accesos.usuarios.roles');

        $q = trim($_GET['q'] ?? '');
        $usuarios = $this->accesosModel->usuariosBuscar($q);
        $roles = $this->accesosModel->rolesListar();
        $asignaciones = $this->accesosModel->mapUsuarioRoles($usuarios);

        $this->vista('layouts/panel', [
            'titulo' => 'Accesos | Usuarios & Roles',
            'contenido' => RUTA_APP . '/vistas/accesos/usuarios/inicio.php',
            'usuarios' => $usuarios,
            'roles' => $roles,
            'asignaciones' => $asignaciones,
            'q' => $q
        ]);
    }

    public function usuarioRolesGuardar()
    {
        requirePermiso('accesos.usuarios.roles');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . RUTA_URL . "/accesos/usuarios");
            exit;
        }

        $userId = (int)($_POST['usuario_id'] ?? 0);
        $roles = $_POST['roles'] ?? [];

        $res = $this->accesosModel->guardarRolesDeUsuario($userId, $roles);
        $ok = $res['ok'];
        $code = $res['code'];

        if ($ok && !empty($_SESSION['id_usuario']) && $userId === (int)$_SESSION['id_usuario']) {
            // recalcula sesión (o puedes confiar solo en version refresh)
            $rolesN = $this->accesosModel->obtenerRolesDeUsuario($userId);
            $permsN = $this->accesosModel->obtenerPermisosDeUsuario($userId);

            $_SESSION['roles'] = $rolesN;
            $_SESSION['rol'] = $rolesN[0] ?? ($_SESSION['rol'] ?? 'usuario');
            $_SESSION['permisos'] = [];
            foreach ($permsN as $p) $_SESSION['permisos'][$p] = true;

            $_SESSION['accesos_version'] = $this->accesosModel->obtenerVersionAccesos();
        }

        header("Location: " . RUTA_URL . "/accesos/usuarios?ok=" . ($ok ? "1" : "0") . "&msg=" . urlencode($code));
        exit;
    }
    public function roles()
    {
        requirePermiso('accesos.roles.ver');

        $roles = $this->accesosModel->rolesListar();

        $this->vista('layouts/panel', [
            'titulo' => 'Accesos | Roles',
            'contenido' => RUTA_APP . '/vistas/accesos/roles/inicio.php',
            'roles' => $roles
        ]);
    }

    public function permisos()
    {
        requirePermiso('accesos.permisos.ver');

        $permisos = $this->accesosModel->permisosListar();

        $this->vista('layouts/panel', [
            'titulo' => 'Accesos | Permisos',
            'contenido' => RUTA_APP . '/vistas/accesos/permisos/inicio.php',
            'permisos' => $permisos
        ]);
    }
    public function logs()
    {
        requirePermiso('accesos.logs.ver');

        $acciones = $this->accesosModel->logsAccionesDisponibles();
        $actores  = $this->accesosModel->logsActoresDisponibles();

        $this->vista('layouts/panel', [
            'titulo' => 'Accesos | Auditoría',
            'contenido' => RUTA_APP . '/vistas/accesos/logs/inicio.php',
            'acciones' => $acciones,
            'actores' => $actores
        ]);
    }
    public function logsAjax()
    {
        requirePermiso('accesos.logs.ver');
        header('Content-Type: application/json; charset=utf-8');

        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = min(50, max(10, (int)($_GET['perPage'] ?? 15)));

        $f = [
            'q' => trim($_GET['q'] ?? ''),
            'accion' => trim($_GET['accion'] ?? ''),
            'actor_id' => (int)($_GET['actor_id'] ?? 0),
            'desde' => trim($_GET['desde'] ?? ''), // YYYY-MM-DD
            'hasta' => trim($_GET['hasta'] ?? ''), // YYYY-MM-DD
        ];

        $res = $this->accesosModel->logsBuscar($f, $page, $perPage);

        echo json_encode($res, JSON_UNESCAPED_UNICODE);
        exit;
    }
}
