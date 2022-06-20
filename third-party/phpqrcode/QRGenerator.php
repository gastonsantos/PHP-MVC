<?php
include('./qrlib.php');

$idViaje = $_GET["id"];
QRcode::png("http://localhost/tpFinalGrupo13/viaje/verProforma?id=" . $idViaje);