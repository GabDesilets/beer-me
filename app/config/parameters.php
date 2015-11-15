<?php

/** @var \Symfony\Component\DependencyInjection\ContainerBuilder $container */

// Load database configuration

$database= array_merge(
    ['scheme'=>'mysql', 'user' => 'root', 'pass' => '', 'host' => '127.0.0.1', 'port' => '3306', 'path' => '/symfony'],
    parse_url($_SERVER['SYMFONY_DATABASE'])
);

$container->setParameter('database_host', $database['host']);
$container->setParameter('database_port', $database['port']);
$container->setParameter('database_name', ltrim($database['path'],'/'));
$container->setParameter('database_user', $database['user']);
$container->setParameter('database_password', $database['pass']);

// Load mailer configuration

$mailer = array_merge(
    ['scheme'=>'smtp', 'user' => '', 'pass' => '', 'host' => '127.0.0.1', 'port' => '', 'path' => ''],
    parse_url($_SERVER['SYMFONY_MAILER'])
);

$container->setParameter('mailer_transport', $mailer['scheme']);
$container->setParameter('mailer_host', $mailer['host']);
$container->setParameter('mailer_port', $mailer['port']);
$container->setParameter('mailer_user', $mailer['user']);
$container->setParameter('mailer_password', $mailer['pass']);
