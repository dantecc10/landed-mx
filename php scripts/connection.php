<?php
include_once "credentials.php";
$data = generatePasskey('sql');
$connection = new mysqli("landed.castelancarpinteyro.com", $data[0], $data[1], $data[2]);
