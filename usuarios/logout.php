<?php
    // se usar session_start() dps.
    session_start();
    session_unset();
    session_destroy();
    echo json_encode(["status"=>"ok"]);
?>