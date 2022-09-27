<?php
session_start();
if (@$_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
    unset($_SESSION['user']);
}
