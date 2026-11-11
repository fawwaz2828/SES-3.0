<?php

declare(strict_types=1);

use Ses\Session\Auth;

require dirname(__DIR__) . '/includes/bootstrap.php';

Auth::logoutAdmin();
redirect('admin/login.php');
