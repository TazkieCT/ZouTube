<?php
require_once 'config.php';
require_once 'functions.php';

$result = handleFileUpload();
extract($result);

require 'index.php';
?>