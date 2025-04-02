<?php
session_start();
session_destroy();
header("Location: ../../src/views/student_portal.html");
exit;
?>