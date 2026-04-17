<?php
$pdo = new PDO('mysql:host=127.0.0.1;port=3306', 'root', 'ServBay.dev');
$pdo->exec('CREATE DATABASE IF NOT EXISTS doctools;');
echo "Database created successfully\n";
