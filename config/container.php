<?php

use Slim\App;
use Slim\Factory\AppFactory;
use App\Domain\User\Entity\User;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Container\ContainerInterface;
use App\Domain\Bookmark\BookmarkService;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use App\Domain\Bookmark\BookmarkRepositoryInterface;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Infrastruktur\Persistence\PostgresUserRepository;
use App\Infrastruktur\Persistence\PostgresBookmarkRepository;

return [
    'settings' => function () {
        return require __DIR__ . '/settings.php';
    },


    App::class => function (ContainerInterface $c) {
        $app = AppFactory::createFromContainer($c);
        //load routes
        $routes = require __DIR__ . '/routes.php';
        //load middleware
        $middleware = require __DIR__ . '/middleware.php';
        $routes($app);
        $middleware($app);
        return $app;
    },
    ResponseFactoryInterface::class => function (ContainerInterface $container) {
        return $container->get(Psr17Factory::class);
    },

    ServerRequestFactoryInterface::class => function (ContainerInterface $container) {
        return $container->get(Psr17Factory::class);
    },

    StreamFactoryInterface::class => function (ContainerInterface $container) {
        return $container->get(Psr17Factory::class);
    },

    UploadedFileFactoryInterface::class => function (ContainerInterface $container) {
        return $container->get(Psr17Factory::class);
    },

    UriFactoryInterface::class => function (ContainerInterface $container) {
        return $container->get(Psr17Factory::class);
    },
    PDO::class => function (ContainerInterface $c) {
        $db = $c->get('settings')['db'];
        return new PDO(
            "pgsql:host={$db['host']};port={$db['port']};dbname={$db['database']}",
            $db['user'],
            $db['password'],
            $db['options']
        );
    },
    BookmarkRepositoryInterface::class => function ($c) {
        return new PostgresBookmarkRepository($c->get(PDO::class));
    },
    UserRepositoryInterface::class => function ($c) {
        return new PostgresUserRepository($c->get(PDO::class));
    },
];
