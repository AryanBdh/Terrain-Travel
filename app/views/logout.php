<?php
session_unset();

session_destroy();

header('Location: /travel/home');
?>