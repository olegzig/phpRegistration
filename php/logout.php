<?php
if (@$_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
    session_start();
    unset($_SESSION['user']);
}
