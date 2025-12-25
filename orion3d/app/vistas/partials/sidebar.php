<?php
$rol = $_SESSION['rol'] ?? 'usuario';

$iconos = [
  'admin' => 'fa-crown',
  'profesor' => 'fa-chalkboard-teacher',
  'usuario' => 'fa-user'
];

$icon_rol = $iconos[$rol] ?? 'fa-user';
$items = menuPanel();

function currentPath(): string {
    $url = $_GET['url'] ?? '';
    $url = trim($url, '/');
    return $url === '' ? 'dashboard' : $url;
}
$CURRENT = currentPath();

function isActiveUrl(string $url): bool {
    $path = parse_url($url, PHP_URL_PATH) ?? '';
    $path = str_replace('/orion3d/', '', $path);
    $path = trim($path, '/');
    if ($path === '') $path = 'dashboard';
    return strpos(($GLOBALS['CURRENT'] ?? ''), $path) === 0;
}

function iconHtml(string $icon): string {
    // Si te pasan fa-solid/fa-regular/fa-brands => úsalo tal cual (FA6)
    if (strpos($icon, 'fa-solid') !== false || strpos($icon, 'fa-regular') !== false || strpos($icon, 'fa-brands') !== false) {
        return '<i class="' . htmlspecialchars($icon) . '"></i>';
    }
    // Si es fa-xxx => asumimos "fas" (compat)
    return '<i class="fas ' . htmlspecialchars($icon) . '"></i>';
}

function renderItem($item)
{
    if (isset($item['section'])) {
        echo '<div class="menu-section">' . htmlspecialchars($item['section']) . '</div>';
        return;
    }

    if (!empty($item['dropdown'])) {
        $id = 'submenu_' . md5($item['label']);

        // abierto si algún subitem está activo
        $open = false;
        foreach ($item['items'] as $sub) {
            if (isActiveUrl($sub['url'])) { $open = true; break; }
        }

        echo '<div class="menu-item-dropdown">';
        echo '  <button class="menu-toggle ' . ($open ? 'open' : '') . '" type="button" data-target="' . $id . '">';
        echo        iconHtml($item['icon']) . ' ' . htmlspecialchars($item['label']) . ' <i class="fas fa-chevron-right arrow"></i>';
        echo '  </button>';

        echo '  <div id="' . $id . '" class="submenu-content" style="' . ($open ? 'display:block;' : 'display:none;') . '">';
        foreach ($item['items'] as $sub) {
            $active = isActiveUrl($sub['url']) ? 'active' : '';
            echo '    <a class="' . $active . '" href="' . $sub['url'] . '">' . iconHtml($sub['icon']) . ' ' . htmlspecialchars($sub['label']) . '</a>';
        }
        echo '  </div>';
        echo '</div>';
        return;
    }

    $active = isActiveUrl($item['url']) ? 'active' : '';
    echo '<a class="' . $active . '" href="' . $item['url'] . '">' . iconHtml($item['icon']) . ' ' . htmlspecialchars($item['label']) . '</a>';
}
?>

<aside class="sidebar-orion">
    <div class="perfil-sidebar text-center">
        <div class="foto-halo mx-auto mb-3">
            <img src="<?php echo $_SESSION['foto_perfil']; ?>" alt="Perfil">
        </div>
        <h3><?php echo explode(' ', $_SESSION['nombre_usuario'])[0]; ?></h3>
        <span class="rol-tag">
            <i class="fas <?php echo $icon_rol; ?> me-1"></i>
            <?php echo strtoupper($_SESSION['rol']); ?>
        </span>
    </div>

    <nav class="menu-sidebar mt-4">
        <?php foreach ($items as $item) renderItem($item); ?>
    </nav>
</aside>
