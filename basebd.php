<?php
$usuario = \app\models\Usuarios::findOne(1); // Cambia el ID
var_dump($usuario->estadoUsuario);
die();