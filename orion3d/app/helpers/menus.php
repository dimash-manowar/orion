<?php

function menuPanel(): array
{
    $menu = [];

    // ===== COMÚN (siempre que tengas sesión) =====
    if (isLogged()) {
        $menu[] = [
            'label' => 'Dashboard',
            'icon'  => 'fa-th-large',
            'url'   => RUTA_URL . '/dashboard',
        ];

        $menu[] = [
            'label' => 'Mensajes',
            'icon'  => 'fa-envelope',
            'url'   => RUTA_URL . '/mensajes',
        ];
    }

    // ===== SEGURIDAD / ACCESOS (RBAC) =====
    $tieneAccesos =
        can('accesos.roles.ver') ||
        can('accesos.permisos.ver') ||
        can('accesos.asignaciones.editar') ||
        can('accesos.usuarios.roles');

    if ($tieneAccesos) {
        $items = [];

        if (can('accesos.asignaciones.editar')) $items[] = ['label' => 'Asignaciones', 'icon' => 'fa-check-square', 'url' => RUTA_URL . '/accesos/asignaciones'];
        if (can('accesos.usuarios.roles'))      $items[] = ['label' => 'Usuarios & Roles', 'icon' => 'fa-user-shield', 'url' => RUTA_URL . '/accesos/usuarios'];
        if (can('accesos.roles.ver'))           $items[] = ['label' => 'Roles', 'icon' => 'fa-id-badge', 'url' => RUTA_URL . '/accesos/roles'];
        if (can('accesos.permisos.ver'))        $items[] = ['label' => 'Permisos', 'icon' => 'fa-key', 'url' => RUTA_URL . '/accesos/permisos'];
        if (can('accesos.logs.ver'))            $items[] = ['label' => 'Auditoría', 'icon' => 'fa-clipboard-list', 'url' => RUTA_URL . '/accesos/logs'];





        $menu[] = ['section' => 'SEGURIDAD'];
        $menu[] = [
            'dropdown' => true,
            'label' => 'Accesos',
            'icon'  => 'fa-shield-halved',
            'items' => $items
        ];
    }

    // ===== ADMIN (por permiso, no por rol enum) =====
    // Ajusta estos permisos a los tuyos reales cuando los definas.
    // Mientras tanto, puedes engancharlo a "accesos..." o a un permiso admin general.
    if (can('accesos.usuarios.roles')) {
        $menu[] = ['section' => 'ADMINISTRACIÓN'];
        $menu[] = [
            'label' => 'Gestión Usuarios',
            'icon'  => 'fa-users',
            'url'   => RUTA_URL . '/usuarios/lista',
        ];
        $menu[] = [
            'label' => 'Ajustes Sistema',
            'icon'  => 'fa-cogs',
            'url'   => RUTA_URL . '/configuracion/sistema',
        ];
    }

    // ===== DOCENCIA =====
    // Si tu profesor/admin puede gestionar cursos/lecciones:
    if (can('cursos.ver') || can('cursos.gestionar') || can('lecciones.ver') || can('lecciones.gestionar')) {
        $menu[] = ['section' => 'DOCENCIA'];

        if (can('mensajeria.usar')) {
            $menu[] = [
                'label' => 'Mensajería',
                'icon'  => 'fa-comments',
                'url'   => RUTA_URL . '/mensajeria',
            ];
        }

        if (can('cursos.ver') || can('cursos.gestionar')) {
            $itemsCursos = [];
            if (can('cursos.ver'))        $itemsCursos[] = ['label' => 'Listado Cursos', 'icon' => 'fa-list', 'url' => RUTA_URL . '/cursos/lista'];
            if (can('cursos.gestionar'))  $itemsCursos[] = ['label' => 'Nuevo Curso', 'icon' => 'fa-plus', 'url' => RUTA_URL . '/cursos/nuevo'];

            $menu[] = [
                'dropdown' => true,
                'label' => 'Mis Cursos',
                'icon'  => 'fa-graduation-cap',
                'items' => $itemsCursos
            ];
        }

        if (can('lecciones.ver') || can('lecciones.gestionar')) {
            $itemsLecciones = [];
            if (can('lecciones.gestionar')) $itemsLecciones[] = ['label' => 'Nueva Lección', 'icon' => 'fa-plus', 'url' => RUTA_URL . '/lecciones/nueva'];
            if (can('lecciones.gestionar')) $itemsLecciones[] = ['label' => 'Editar Lecciones', 'icon' => 'fa-pen', 'url' => RUTA_URL . '/lecciones/gestionar'];
            if (can('lecciones.ver') && empty($itemsLecciones)) {
                // si solo puede ver, pon algún enlace que exista
                $itemsLecciones[] = ['label' => 'Ver Lecciones', 'icon' => 'fa-book', 'url' => RUTA_URL . '/lecciones'];
            }

            $menu[] = [
                'dropdown' => true,
                'label' => 'Mis Lecciones',
                'icon'  => 'fa-book-open',
                'items' => $itemsLecciones
            ];
        }

        // Inscripciones / Recursos (si quieres por permisos, define permisos y conéctalos)
        $menu[] = [
            'label' => 'Inscripciones',
            'icon'  => 'fa-user-check',
            'url'   => RUTA_URL . '/inscripciones',
        ];
        $menu[] = [
            'label' => 'Recursos',
            'icon'  => 'fa-folder-open',
            'url'   => RUTA_URL . '/recursos',
        ];
    }

    // ===== APRENDIZAJE (usuario/alumno) =====
    if (can('cursos.ver')) {
        $menu[] = ['section' => 'APRENDIZAJE'];
        $menu[] = [
            'label' => 'Mis Cursos',
            'icon'  => 'fa-graduation-cap',
            'url'   => RUTA_URL . '/cursos/explorar',
        ];
        $menu[] = [
            'label' => 'Mis Modelos 3D',
            'icon'  => 'fa-cube',
            'url'   => RUTA_URL . '/proyectos/galeria',
        ];
    }

    return $menu;
}
