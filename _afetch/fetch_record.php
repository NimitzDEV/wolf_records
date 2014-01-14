<?php
require_once('./ninjin_g.php');
require_once('./guta.php');

$ninjin = new Ninjin();
$ninjin->fetch_ninjin();
$guta = new Guta();
$guta->fetch_guta();
