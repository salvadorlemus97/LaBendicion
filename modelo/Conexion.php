<?php
    require_once "../vendor/autoload.php";
    function obtenerBaseDeDatos()
    {
        $host = "127.0.0.1";
        $puerto = "27017";
        // $usuario = rawurlencode("parzibyte");
        $usuario = "";
        // $pass = rawurlencode("hunter2");
        $pass = "";
        $nombreBD = "farmacia_chamba";
        # Crea algo como mongodb://parzibyte:hunter2@127.0.0.1:27017/agenda
        // $cadenaConexion = sprintf("mongodb://%s:%s@%s:%s/%s", $usuario, $pass, $host, $puerto, $nombreBD);
        $cadenaConexion = sprintf("mongodb://127.0.0.1:27017", $usuario, $pass, $host, $puerto, $nombreBD);
        $cliente = new MongoDB\Client($cadenaConexion);
        return $cliente->selectDatabase($nombreBD);
    }