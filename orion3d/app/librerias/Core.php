<?php

class Core {
    protected string $controladorNombre = 'Paginas';
    protected string $metodoActual = 'inicio';
    protected array $parametros = [];

    protected object $controladorObjeto;

    public function __construct() {
        $url = $this->getUrl();

        // Controlador
        if (!empty($url[0])) {
            $posible = $this->toControllerName($url[0]);
            if (file_exists(RUTA_APP . '/controladores/' . $posible . '.php')) {
                $this->controladorNombre = $posible;
                unset($url[0]);
            }
        }

        $controllerPath = RUTA_APP . '/controladores/' . $this->controladorNombre . '.php';
        if (!file_exists($controllerPath)) {
            $this->render404("No existe el controlador: {$this->controladorNombre}");
            return;
        }

        require_once $controllerPath;

        $clase = $this->controladorNombre;
        $this->controladorObjeto = new $clase();

        // Método
        if (isset($url[1]) && method_exists($this->controladorObjeto, $url[1])) {
            $this->metodoActual = $url[1];
            unset($url[1]);
        } elseif (isset($url[1])) {
            $this->render404("No existe el método: {$url[1]}");
            return;
        }

        // Parámetros
        $this->parametros = $url ? array_values($url) : [];

        call_user_func_array([$this->controladorObjeto, $this->metodoActual], $this->parametros);
    }

    private function toControllerName(string $segment): string {
        $segment = str_replace(['-', '_'], ' ', strtolower($segment));
        return str_replace(' ', '', ucwords($segment));
    }

    public function getUrl(): array {
        if (!isset($_GET['url'])) return [];
        $url = rtrim($_GET['url'], '/');
        $url = filter_var($url, FILTER_SANITIZE_URL);
        return $url === '' ? [] : explode('/', $url);
    }

    private function render404(string $msg = ''): void {
        http_response_code(404);
        echo "<h1>404</h1>";
        if ($msg) echo "<p>{$msg}</p>";
        exit;
    }
}
