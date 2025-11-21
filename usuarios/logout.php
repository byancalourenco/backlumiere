<?php
    // Se usar session_start() no futuro.
    session_start();
    session_unset();
    session_destroy();
    echo json_encode(["status"=>"ok"]);
?>