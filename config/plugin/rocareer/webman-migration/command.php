<?php
/**
 * File      command.php
 * Author    albert@rocareer.com
 * Time      2025-04-28 03:20:23
 * Describe  command.php
 */

use Rocareer\WebmanMigration\command\MigrateCreate;
use Rocareer\WebmanMigration\command\MigrateRun;

return[
    MigrateRun::class,
    MigrateCreate::class
];