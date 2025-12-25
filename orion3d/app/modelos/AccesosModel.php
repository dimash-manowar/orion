<?php
class AccesosModel extends Mysql
{
    // --------- LISTADOS ---------
    public function rolesListar(): array
    {
        return $this->select("SELECT id, nombre, descripcion FROM roles ORDER BY nombre");
    }

    public function permisosListar(): array
    {
        return $this->select("SELECT id, clave, modulo, descripcion FROM permisos ORDER BY modulo, clave");
    }

    // --------- LOGIN CACHE ---------
    public function obtenerRolesDeUsuario(int $usuarioId): array
    {
        $rows = $this->select(
            "SELECT r.nombre
       FROM usuario_rol ur
       JOIN roles r ON r.id = ur.rol_id
       WHERE ur.usuario_id = ?
       ORDER BY r.nombre",
            [$usuarioId]
        );
        return array_map(fn($r) => $r['nombre'], $rows);
    }

    public function obtenerPermisosDeUsuario(int $usuarioId): array
    {
        $rows = $this->select(
            "SELECT DISTINCT p.clave
       FROM usuario_rol ur
       JOIN rol_permiso rp ON rp.rol_id = ur.rol_id
       JOIN permisos p ON p.id = rp.permiso_id
       WHERE ur.usuario_id = ?",
            [$usuarioId]
        );
        return array_map(fn($r) => $r['clave'], $rows);
    }

    // --------- MATRIZ rol_permiso ---------
    public function mapRolPermiso(): array
    {
        $rows = $this->select("SELECT rol_id, permiso_id FROM rol_permiso");
        $map = [];
        foreach ($rows as $r) {
            $map[(int)$r['rol_id']][(int)$r['permiso_id']] = true;
        }
        return $map;
    }

    public function guardarPermisosDeRol(int $rolId, array $permisoIds): array
    {
        if ($rolId <= 0) return ['ok' => false, 'code' => 'bad_request', 'message' => 'Rol inválido'];

        $permisoIds = array_values(array_unique(array_map('intval', $permisoIds)));

        try {
            $this->pdo->beginTransaction();

            // Snapshot antes
            $antes = $this->select(
                "SELECT p.clave
       FROM rol_permiso rp
       JOIN permisos p ON p.id = rp.permiso_id
       WHERE rp.rol_id = ?",
                [$rolId]
            );
            $antes = array_map(fn($r) => $r['clave'], $antes);

            $this->delete("DELETE FROM rol_permiso WHERE rol_id = ?", [$rolId]);

            foreach ($permisoIds as $pid) {
                if ($pid <= 0) continue;
                $this->insert("INSERT INTO rol_permiso (rol_id, permiso_id) VALUES (?, ?)", [$rolId, $pid]);
            }

            // Snapshot después
            $despues = $this->select(
                "SELECT p.clave
       FROM rol_permiso rp
       JOIN permisos p ON p.id = rp.permiso_id
       WHERE rp.rol_id = ?",
                [$rolId]
            );
            $despues = array_map(fn($r) => $r['clave'], $despues);

            $this->logAcceso('permisos.rol.guardar', 'rol', $rolId, [
                'antes' => $antes,
                'despues' => $despues
            ]);
            $this->subirVersionAccesos();

            $this->pdo->commit();
            return ['ok' => true, 'code' => 'ok', 'message' => 'Permisos guardados'];
        } catch (Throwable $e) {
            if ($this->pdo->inTransaction()) $this->pdo->rollBack();
            return ['ok' => false, 'code' => 'db_error', 'message' => 'Error al guardar permisos'];
        }
    }


    // --------- USUARIOS & ROLES ---------
    public function usuariosBuscar(string $q = ''): array
    {
        $q = trim($q);
        if ($q === '') {
            return $this->select("SELECT id_usuario, nombre, email, rol FROM usuarios ORDER BY id_usuario DESC LIMIT 50");
        }

        // búsqueda por nombre o email
        return $this->select(
            "SELECT id_usuario, nombre, email, rol
       FROM usuarios
       WHERE nombre LIKE ? OR email LIKE ?
       ORDER BY id_usuario DESC
       LIMIT 50",
            ["%{$q}%", "%{$q}%"]
        );
    }

    public function mapUsuarioRoles(array $usuarios): array
    {
        if (empty($usuarios)) return [];

        $ids = array_map(fn($u) => (int)$u['id_usuario'], $usuarios);
        $ids = array_values(array_filter($ids, fn($id) => $id > 0));
        if (!$ids) return [];

        // placeholders para IN (...)
        $in = implode(',', array_fill(0, count($ids), '?'));

        $rows = $this->select(
            "SELECT usuario_id, rol_id
       FROM usuario_rol
       WHERE usuario_id IN ($in)",
            $ids
        );

        $map = [];
        foreach ($rows as $r) {
            $map[(int)$r['usuario_id']][(int)$r['rol_id']] = true;
        }
        return $map;
    }

    public function guardarRolesDeUsuario(int $usuarioId, array $rolIds): array
    {
        if ($usuarioId <= 0) {
            return ['ok' => false, 'code' => 'bad_request', 'message' => 'Usuario inválido'];
        }

        $rolIds = array_values(array_unique(array_map('intval', $rolIds)));

        try {
            $this->pdo->beginTransaction();

            // --- protección último admin ---
            $adminId = $this->rolIdPorNombre('admin');
            $quiereAdmin = ($adminId && in_array($adminId, $rolIds, true));
            $eraAdmin = $this->usuarioTieneRol($usuarioId, 'admin');

            if ($eraAdmin && !$quiereAdmin) {
                $admins = $this->contarUsuariosConRol('admin');
                if ($admins <= 1) {
                    $this->pdo->rollBack();
                    return ['ok' => false, 'code' => 'ultimo_admin', 'message' => 'No puedes quitar el último admin del sistema'];
                }
            }

            // (opcional) protección: no quitarte tu propio admin
            if (!empty($_SESSION['id_usuario']) && (int)$_SESSION['id_usuario'] === $usuarioId && $eraAdmin && !$quiereAdmin) {
                $this->pdo->rollBack();
                return ['ok' => false, 'code' => 'auto_admin', 'message' => 'No puedes quitarte tu rol admin a ti mismo'];
            }

            // Snapshot antes (para logs)
            $antes = $this->obtenerRolesDeUsuario($usuarioId);

            $this->delete("DELETE FROM usuario_rol WHERE usuario_id = ?", [$usuarioId]);

            foreach ($rolIds as $rid) {
                if ($rid <= 0) continue;
                $this->insert("INSERT INTO usuario_rol (usuario_id, rol_id) VALUES (?, ?)", [$usuarioId, $rid]);
            }

            // sincronizar usuarios.rol (compat visual)
            $principal = $this->resolverRolPrincipal($usuarioId);
            if ($principal) {
                $this->update("UPDATE usuarios SET rol = ? WHERE id_usuario = ?", [$principal, $usuarioId]);
            }

            $despues = $this->obtenerRolesDeUsuario($usuarioId);

            // logs + version bump
            $this->logAcceso('roles.usuario.guardar', 'usuario', $usuarioId, [
                'antes' => $antes,
                'despues' => $despues
            ]);
            $this->subirVersionAccesos();

            $this->pdo->commit();
            return ['ok' => true, 'code' => 'ok', 'message' => 'Roles guardados'];
        } catch (Throwable $e) {
            if ($this->pdo->inTransaction()) $this->pdo->rollBack();
            return ['ok' => false, 'code' => 'db_error', 'message' => 'Error al guardar roles'];
        }
    }


    private function resolverRolPrincipal(int $usuarioId): ?string
    {
        $rows = $this->select(
            "SELECT r.nombre
       FROM usuario_rol ur
       JOIN roles r ON r.id = ur.rol_id
       WHERE ur.usuario_id = ?",
            [$usuarioId]
        );

        $tiene = array_flip(array_map(fn($r) => $r['nombre'], $rows));
        foreach (['admin', 'profesor', 'usuario'] as $prio) {
            if (isset($tiene[$prio])) return $prio;
        }
        // si tiene roles custom, usa el primero alfabético
        $nombres = array_keys($tiene);
        sort($nombres);
        return $nombres[0] ?? null;
    }
    public function contarUsuariosConRol(string $rolNombre): int
    {
        $row = $this->select_one(
            "SELECT COUNT(DISTINCT ur.usuario_id) AS total
     FROM usuario_rol ur
     JOIN roles r ON r.id = ur.rol_id
     WHERE r.nombre = ?",
            [$rolNombre]
        );
        return (int)($row['total'] ?? 0);
    }

    public function usuarioTieneRol(int $usuarioId, string $rolNombre): bool
    {
        $row = $this->select_one(
            "SELECT 1
     FROM usuario_rol ur
     JOIN roles r ON r.id = ur.rol_id
     WHERE ur.usuario_id = ? AND r.nombre = ?
     LIMIT 1",
            [$usuarioId, $rolNombre]
        );
        return !empty($row);
    }

    public function rolIdPorNombre(string $rolNombre): ?int
    {
        $row = $this->select_one("SELECT id FROM roles WHERE nombre = ? LIMIT 1", [$rolNombre]);
        return $row ? (int)$row['id'] : null;
    }
    public function obtenerVersionAccesos(): int
    {
        $row = $this->select_one("SELECT version FROM accesos_config WHERE id=1");
        return (int)($row['version'] ?? 1);
    }

    public function subirVersionAccesos(): void
    {
        $this->update("UPDATE accesos_config SET version = version + 1 WHERE id=1");
    }

    public function logAcceso(string $accion, string $objetivoTipo, ?int $objetivoId, array $detalle = []): void
    {
        iniciarSesion();
        $actor = (int)($_SESSION['id_usuario'] ?? 0);
        if ($actor <= 0) return;

        $ip = $_SERVER['REMOTE_ADDR'] ?? null;
        $ua = $_SERVER['HTTP_USER_AGENT'] ?? null;
        $json = !empty($detalle) ? json_encode($detalle, JSON_UNESCAPED_UNICODE) : null;

        $this->insert(
            "INSERT INTO logs_accesos (actor_id, accion, objetivo_tipo, objetivo_id, detalle, ip, user_agent)
     VALUES (?, ?, ?, ?, ?, ?, ?)",
            [$actor, $accion, $objetivoTipo, $objetivoId, $json, $ip, $ua]
        );
    }
    public function logsAccionesDisponibles(): array
    {
        $rows = $this->select("SELECT DISTINCT accion FROM logs_accesos ORDER BY accion");
        return array_map(fn($r) => $r['accion'], $rows);
    }

    public function logsActoresDisponibles(): array
    {
        return $this->select(
            "SELECT DISTINCT u.id_usuario, u.nombre, u.email
     FROM logs_accesos l
     JOIN usuarios u ON u.id_usuario = l.actor_id
     ORDER BY u.nombre"
        );
    }

    public function logsBuscar(array $f, int $page, int $perPage): array
    {
        $where = [];
        $vals = [];

        if (!empty($f['q'])) {
            $where[] = "(u.nombre LIKE ? OR u.email LIKE ? OR l.accion LIKE ? OR l.objetivo_tipo LIKE ? OR CAST(l.objetivo_id AS CHAR) LIKE ? OR l.detalle LIKE ?)";
            $q = '%' . $f['q'] . '%';
            array_push($vals, $q, $q, $q, $q, $q, $q);
        }

        if (!empty($f['accion'])) {
            $where[] = "l.accion = ?";
            $vals[] = $f['accion'];
        }

        if (!empty($f['actor_id'])) {
            $where[] = "l.actor_id = ?";
            $vals[] = (int)$f['actor_id'];
        }

        if (!empty($f['desde'])) {
            $where[] = "DATE(l.creado_en) >= ?";
            $vals[] = $f['desde'];
        }

        if (!empty($f['hasta'])) {
            $where[] = "DATE(l.creado_en) <= ?";
            $vals[] = $f['hasta'];
        }

        $sqlWhere = $where ? ("WHERE " . implode(" AND ", $where)) : "";

        $countRow = $this->select_one(
            "SELECT COUNT(*) AS total
     FROM logs_accesos l
     JOIN usuarios u ON u.id_usuario = l.actor_id
     $sqlWhere",
            $vals
        );

        $total = (int)($countRow['total'] ?? 0);
        $pages = max(1, (int)ceil($total / $perPage));
        $page = min($page, $pages);
        $offset = ($page - 1) * $perPage;

        $rows = $this->select(
            "SELECT l.id, l.creado_en, l.accion, l.objetivo_tipo, l.objetivo_id, l.detalle, l.ip,
            u.id_usuario AS actor_id, u.nombre AS actor_nombre, u.email AS actor_email
     FROM logs_accesos l
     JOIN usuarios u ON u.id_usuario = l.actor_id
     $sqlWhere
     ORDER BY l.id DESC
     LIMIT $perPage OFFSET $offset",
            $vals
        );

        return [
            'ok' => true,
            'meta' => [
                'total' => $total,
                'page' => $page,
                'perPage' => $perPage,
                'pages' => $pages
            ],
            'data' => $rows
        ];
    }
}
