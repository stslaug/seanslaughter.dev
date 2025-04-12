<?php
session_start();
session_destroy();
header("Location: ../card-home.php");
exit();