<?php

class Controlador {

    public function modelo(string $modelo) {
        $ruta = RUTA_APP . '/modelos/' . $modelo . '.php';
        if (!file_exists($ruta)) {
            die("El modelo no existe: $modelo");
        }
        require_once $ruta;
        return new $modelo();
    }

    public function vista($vista, $datos = []) {
    if(file_exists(RUTA_APP . '/vistas/' . $vista . '.php')) {
        extract($datos); 
        require_once RUTA_APP . '/vistas/' . $vista . '.php';
    } else {
        die('La vista no existe');
    }
}

}
