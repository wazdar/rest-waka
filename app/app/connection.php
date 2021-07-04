<?php
declare(strict_types=1);

use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;


return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        PDO::class => function (ContainerInterface $c) {
            $connection = $c->get(SettingsInterface::class)->get('db');

            $db_host = $connection['db_host'];
            $db_name = $connection['db_name'];
            $db_user = $connection['db_user'];
            $db_pass = $connection['db_pass'];

            $pdo = new PDO("pgsql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            return $pdo;
        },
    ]);

};
