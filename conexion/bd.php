<?php
    $host = 'innovatesc.com.pe';
    $dbname = 'innovat2_sistemw';
    $username = 'innovat2_sistemaw';
    $password = '383to9*VcX@W^AM*';

    try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    } catch(PDOException $e) {
        die("Error de conexión: " . $e->getMessage());
    }

?>