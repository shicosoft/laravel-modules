<?php

namespace Koyeo\Modules\Providers;

use Illuminate\Support\ServiceProvider;
use Koyeo\Modules\Commands\CommandCommand;
use Koyeo\Modules\Commands\ControllerCommand;
use Koyeo\Modules\Commands\DisableCommand;
use Koyeo\Modules\Commands\DumpCommand;
use Koyeo\Modules\Commands\EnableCommand;
use Koyeo\Modules\Commands\GenerateEventCommand;
use Koyeo\Modules\Commands\GenerateJobCommand;
use Koyeo\Modules\Commands\GenerateListenerCommand;
use Koyeo\Modules\Commands\GenerateMailCommand;
use Koyeo\Modules\Commands\GenerateMiddlewareCommand;
use Koyeo\Modules\Commands\GenerateNotificationCommand;
use Koyeo\Modules\Commands\GenerateProviderCommand;
use Koyeo\Modules\Commands\GenerateRouteProviderCommand;
use Koyeo\Modules\Commands\InstallCommand;
use Koyeo\Modules\Commands\ListCommand;
use Koyeo\Modules\Commands\MakeCommand;
use Koyeo\Modules\Commands\MakeRequestCommand;
use Koyeo\Modules\Commands\MigrateCommand;
use Koyeo\Modules\Commands\MigrateRefreshCommand;
use Koyeo\Modules\Commands\MigrateResetCommand;
use Koyeo\Modules\Commands\MigrateRollbackCommand;
use Koyeo\Modules\Commands\MigrationCommand;
use Koyeo\Modules\Commands\ModelCommand;
use Koyeo\Modules\Commands\PublishCommand;
use Koyeo\Modules\Commands\PublishConfigurationCommand;
use Koyeo\Modules\Commands\PublishMigrationCommand;
use Koyeo\Modules\Commands\PublishTranslationCommand;
use Koyeo\Modules\Commands\SeedCommand;
use Koyeo\Modules\Commands\SeedMakeCommand;
use Koyeo\Modules\Commands\SetupCommand;
use Koyeo\Modules\Commands\UpdateCommand;
use Koyeo\Modules\Commands\UseCommand;

class ConsoleServiceProvider extends ServiceProvider
{
    protected $defer = false;

    /**
     * The available commands
     *
     * @var array
     */
    protected $commands = [
        MakeCommand::class,
        CommandCommand::class,
        ControllerCommand::class,
        DisableCommand::class,
        EnableCommand::class,
        GenerateEventCommand::class,
        GenerateListenerCommand::class,
        GenerateMiddlewareCommand::class,
        GenerateProviderCommand::class,
        GenerateRouteProviderCommand::class,
        InstallCommand::class,
        ListCommand::class,
        MigrateCommand::class,
        MigrateRefreshCommand::class,
        MigrateResetCommand::class,
        MigrateRollbackCommand::class,
        MigrationCommand::class,
        ModelCommand::class,
        PublishCommand::class,
        PublishMigrationCommand::class,
        PublishTranslationCommand::class,
        SeedCommand::class,
        SeedMakeCommand::class,
        SetupCommand::class,
        UpdateCommand::class,
        UseCommand::class,
        DumpCommand::class,
        MakeRequestCommand::class,
        PublishConfigurationCommand::class,
        GenerateJobCommand::class,
        GenerateMailCommand::class,
        GenerateNotificationCommand::class,
    ];

    /**
     * Register the commands.
     */
    public function register()
    {
        $this->commands($this->commands);
    }

    /**
     * @return array
     */
    public function provides()
    {
        $provides = $this->commands;

        return $provides;
    }
}
