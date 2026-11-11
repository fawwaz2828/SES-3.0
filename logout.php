<?php

declare(strict_types=1);

use Ses\Session\Auth;

require __DIR__ . '/includes/bootstrap.php';

Auth::logout();
redirect('index.php');
