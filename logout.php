<?php
require_once 'config/koneksi.php';

session_destroy();
redirect('index.php');
?>