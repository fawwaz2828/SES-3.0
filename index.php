<?php

declare(strict_types=1);

use Ses\Session\Auth;

require __DIR__ . '/includes/bootstrap.php';

// Logged-in users start from their dashboard.
if (Auth::check()) {
    redirect('dashboard.php');
}

$lightsOff = true; // the site stays dark until the hero lamp is switched on
$flushTop = true;  // the hero sits under the floating navbar
require __DIR__ . '/includes/layout/header.php';

foreach (['hero', 'intro', 'features', 'products', 'steps', 'highlights', 'testimonials', 'blog-preview'] as $section) {
    require __DIR__ . '/includes/sections/' . $section . '.php';
}

require __DIR__ . '/includes/layout/footer.php';
