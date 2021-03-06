<?php

return [
  'providers' => [
    0  => 'Illuminate\\Auth\\AuthServiceProvider',
    1  => 'Illuminate\\Bus\\BusServiceProvider',
    2  => 'Illuminate\\Cache\\CacheServiceProvider',
    3  => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    4  => 'Illuminate\\Cookie\\CookieServiceProvider',
    5  => 'Illuminate\\Database\\DatabaseServiceProvider',
    6  => 'Illuminate\\Encryption\\EncryptionServiceProvider',
    7  => 'Illuminate\\Filesystem\\FilesystemServiceProvider',
    8  => 'Illuminate\\Foundation\\Providers\\FoundationServiceProvider',
    9  => 'Illuminate\\Hashing\\HashServiceProvider',
    10 => 'Illuminate\\Mail\\MailServiceProvider',
    11 => 'Illuminate\\Pagination\\PaginationServiceProvider',
    12 => 'Illuminate\\Pipeline\\PipelineServiceProvider',
    13 => 'Illuminate\\Queue\\QueueServiceProvider',
    14 => 'Illuminate\\Redis\\RedisServiceProvider',
    15 => 'Illuminate\\Auth\\Passwords\\PasswordResetServiceProvider',
    16 => 'Illuminate\\Session\\SessionServiceProvider',
    17 => 'Illuminate\\Translation\\TranslationServiceProvider',
    18 => 'Illuminate\\Validation\\ValidationServiceProvider',
    19 => 'Illuminate\\View\\ViewServiceProvider',
    20 => 'App\\Providers\\AppServiceProvider',
    21 => 'App\\Providers\\BusServiceProvider',
    22 => 'App\\Providers\\ConfigServiceProvider',
    23 => 'App\\Providers\\EventServiceProvider',
    24 => 'App\\Providers\\RouteServiceProvider',
    25 => 'Aacotroneo\\Saml2\\Saml2ServiceProvider',
    26 => 'RachidLaasri\\LaravelInstaller\\Providers\\LaravelInstallerServiceProvider',
  ],
  'eager' => [
    0  => 'Illuminate\\Auth\\AuthServiceProvider',
    1  => 'Illuminate\\Cookie\\CookieServiceProvider',
    2  => 'Illuminate\\Database\\DatabaseServiceProvider',
    3  => 'Illuminate\\Encryption\\EncryptionServiceProvider',
    4  => 'Illuminate\\Filesystem\\FilesystemServiceProvider',
    5  => 'Illuminate\\Foundation\\Providers\\FoundationServiceProvider',
    6  => 'Illuminate\\Pagination\\PaginationServiceProvider',
    7  => 'Illuminate\\Session\\SessionServiceProvider',
    8  => 'Illuminate\\View\\ViewServiceProvider',
    9  => 'App\\Providers\\AppServiceProvider',
    10 => 'App\\Providers\\BusServiceProvider',
    11 => 'App\\Providers\\ConfigServiceProvider',
    12 => 'App\\Providers\\EventServiceProvider',
    13 => 'App\\Providers\\RouteServiceProvider',
    14 => 'Aacotroneo\\Saml2\\Saml2ServiceProvider',
    15 => 'RachidLaasri\\LaravelInstaller\\Providers\\LaravelInstallerServiceProvider',
  ],
  'deferred' => [
    'Illuminate\\Bus\\Dispatcher'                            => 'Illuminate\\Bus\\BusServiceProvider',
    'Illuminate\\Contracts\\Bus\\Dispatcher'                 => 'Illuminate\\Bus\\BusServiceProvider',
    'Illuminate\\Contracts\\Bus\\QueueingDispatcher'         => 'Illuminate\\Bus\\BusServiceProvider',
    'cache'                                                  => 'Illuminate\\Cache\\CacheServiceProvider',
    'cache.store'                                            => 'Illuminate\\Cache\\CacheServiceProvider',
    'memcached.connector'                                    => 'Illuminate\\Cache\\CacheServiceProvider',
    'command.cache.clear'                                    => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.cache.forget'                                   => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.clear-compiled'                                 => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.auth.resets.clear'                              => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.config.cache'                                   => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.config.clear'                                   => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.down'                                           => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.environment'                                    => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.key.generate'                                   => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.migrate'                                        => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.migrate.fresh'                                  => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.migrate.install'                                => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.migrate.refresh'                                => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.migrate.reset'                                  => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.migrate.rollback'                               => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.migrate.status'                                 => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.optimize'                                       => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.package.discover'                               => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.preset'                                         => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.queue.failed'                                   => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.queue.flush'                                    => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.queue.forget'                                   => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.queue.listen'                                   => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.queue.restart'                                  => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.queue.retry'                                    => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.queue.work'                                     => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.route.cache'                                    => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.route.clear'                                    => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.route.list'                                     => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.seed'                                           => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'Illuminate\\Console\\Scheduling\\ScheduleFinishCommand' => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'Illuminate\\Console\\Scheduling\\ScheduleRunCommand'    => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.storage.link'                                   => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.up'                                             => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.view.clear'                                     => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.app.name'                                       => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.auth.make'                                      => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.cache.table'                                    => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.console.make'                                   => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.controller.make'                                => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.event.generate'                                 => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.event.make'                                     => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.factory.make'                                   => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.job.make'                                       => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.listener.make'                                  => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.mail.make'                                      => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.middleware.make'                                => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.migrate.make'                                   => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.model.make'                                     => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.notification.make'                              => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.notification.table'                             => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.policy.make'                                    => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.provider.make'                                  => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.queue.failed-table'                             => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.queue.table'                                    => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.request.make'                                   => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.resource.make'                                  => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.rule.make'                                      => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.seeder.make'                                    => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.session.table'                                  => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.serve'                                          => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.test.make'                                      => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.vendor.publish'                                 => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'migrator'                                               => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'migration.repository'                                   => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'migration.creator'                                      => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'composer'                                               => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'hash'                                                   => 'Illuminate\\Hashing\\HashServiceProvider',
    'mailer'                                                 => 'Illuminate\\Mail\\MailServiceProvider',
    'swift.mailer'                                           => 'Illuminate\\Mail\\MailServiceProvider',
    'swift.transport'                                        => 'Illuminate\\Mail\\MailServiceProvider',
    'Illuminate\\Mail\\Markdown'                             => 'Illuminate\\Mail\\MailServiceProvider',
    'Illuminate\\Contracts\\Pipeline\\Hub'                   => 'Illuminate\\Pipeline\\PipelineServiceProvider',
    'queue'                                                  => 'Illuminate\\Queue\\QueueServiceProvider',
    'queue.worker'                                           => 'Illuminate\\Queue\\QueueServiceProvider',
    'queue.listener'                                         => 'Illuminate\\Queue\\QueueServiceProvider',
    'queue.failer'                                           => 'Illuminate\\Queue\\QueueServiceProvider',
    'queue.connection'                                       => 'Illuminate\\Queue\\QueueServiceProvider',
    'redis'                                                  => 'Illuminate\\Redis\\RedisServiceProvider',
    'redis.connection'                                       => 'Illuminate\\Redis\\RedisServiceProvider',
    'auth.password'                                          => 'Illuminate\\Auth\\Passwords\\PasswordResetServiceProvider',
    'auth.password.broker'                                   => 'Illuminate\\Auth\\Passwords\\PasswordResetServiceProvider',
    'translator'                                             => 'Illuminate\\Translation\\TranslationServiceProvider',
    'translation.loader'                                     => 'Illuminate\\Translation\\TranslationServiceProvider',
    'validator'                                              => 'Illuminate\\Validation\\ValidationServiceProvider',
    'validation.presence'                                    => 'Illuminate\\Validation\\ValidationServiceProvider',
  ],
  'when' => [
    'Illuminate\\Bus\\BusServiceProvider' => [
    ],
    'Illuminate\\Cache\\CacheServiceProvider' => [
    ],
    'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider' => [
    ],
    'Illuminate\\Hashing\\HashServiceProvider' => [
    ],
    'Illuminate\\Mail\\MailServiceProvider' => [
    ],
    'Illuminate\\Pipeline\\PipelineServiceProvider' => [
    ],
    'Illuminate\\Queue\\QueueServiceProvider' => [
    ],
    'Illuminate\\Redis\\RedisServiceProvider' => [
    ],
    'Illuminate\\Auth\\Passwords\\PasswordResetServiceProvider' => [
    ],
    'Illuminate\\Translation\\TranslationServiceProvider' => [
    ],
    'Illuminate\\Validation\\ValidationServiceProvider' => [
    ],
  ],
];
