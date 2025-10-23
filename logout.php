<?php
require 'config.php';
session_destroy();
header('Location: products.php');
exit;
