<?php
session_start();
session_destroy();
header('Location: /board/index.php');
exit;
