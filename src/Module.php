<?php

namespace Koyeo\Modules;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class Module extends ServiceProvider
{
    /**
     * The laravel application instance.
     *
     * @var Application
     */
    protected $app;

    /**
     * The module name.
     *
     * @var
     */
    protected $name;

    /**
     * The module path.
     *
     * @var string
     */
    protected $path;


    /**
     * The module bootstrap kernel.
     *
     * @var array
     */
    protected $bootstrap;

    /**
     * The constructor.
     *
     * @param Application $app
     * @param $name
     * @param $path
     */
    public function __construct(Application $app, $name, $path)
    {
        $this->app = $app;
        $this->name = $name;
        $this->path = realpath($path);
        $this->bootstrap = $this->getBootstrapKernel();
    }

    /**
     * Get laravel instance.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function getLaravel()
    {
        return $this->app;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the module bootstrap kernel object;
     */
    public function getBootstrapKernel()
    {

        $kernel = "Modules\\$this->name\\Bootstrap\\Kernel";

        return new $kernel;
    }

    /**
     * Get name in lower case.
     *
     * @return string
     */
    public function getLowerName()
    {
        return strtolower($this->name);
    }

    /**
     * Get name in studly case.
     *
     * @return string
     */
    public function getStudlyName()
    {
        return Str::studly($this->name);
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->get('description');
    }

    /**
     * Get alias.
     *
     * @return string
     */
    public function getAlias()
    {
        return $this->get('alias');
    }

    /**
     * Get priority.
     *
     * @return string
     */
    public function getPriority()
    {
        return $this->get('priority');
    }

    /**
     * Get module requirements.
     *
     * @return array
     */
    public function getRequires()
    {
        return $this->get('requires');
    }

    /**
     * Get path.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set path.
     *
     * @param string $path
     *
     * @return $this
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        if (config('modules.register.translations', true) === true) {

            $this->registerTranslation();
        }

        $this->fireEvent('boot');
    }

    /**
     * Register module's translation.
     *
     * @return void
     */
    protected function registerTranslation()
    {
        $lowerName = $this->getLowerName();

        $langPath = $this->getPath() . "/Resources/lang";

        if (is_dir($langPath)) {

            $this->loadTranslationsFrom($langPath, $lowerName);
        }
    }

    /**
     * Get json contents.
     *
     * @return Json
     */
    public function json($file = null)
    {
        if (is_null($file)) {
            $file = 'module.json';
        }

        return new Json($this->getPath() . '/' . $file, $this->app['files']);
    }

    /**
     * Get a specific data from json file by given the key.
     *
     * @param $key
     * @param null $default
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return $this->json()->get($key, $default);
    }

    /**
     * Get a specific data from composer.json file by given the key.
     *
     * @param $key
     * @param null $default
     *
     * @return mixed
     */
    public function getComposerAttr($key, $default = null)
    {
        return $this->json('composer.json')->get($key, $default);
    }

    /**
     * Register the module.
     */
    public function register()
    {
        $this->registerFiles();

        $this->registerProviders();

        $this->registerAliases();

        $this->registerRoutes();

        $this->registerCommands();

        $this->fireEvent('register');
    }

    /**
     * Register the module event.
     *
     * @param string $event
     */
    protected function fireEvent($event)
    {
        $this->app['events']->fire(sprintf('modules.%s.' . $event, $this->getLowerName()), [$this]);
    }

    /**
     * Register the module routes.
     */
    protected function registerRoutes()
    {
        if (!app()->routesAreCached()) {

            require $this->path . '/Http/routes.php';
        }
    }

    /**
     * Register the aliases from this module.
     */
    protected function registerAliases()
    {
        $loader = AliasLoader::getInstance();

        if (isset($this->bootstrap->aliases)) {

            foreach ($this->bootstrap->aliases as $aliasName => $aliasClass) {
                $loader->alias($aliasName, $aliasClass);
            }
        }

    }

    /**
     * Register the service providers from this module.
     */
    protected function registerProviders()
    {
        if (isset($this->bootstrap->providers)) {

            foreach ($this->bootstrap->providers as $provider) {

                $this->app->register($provider);
            }
        }
    }

    /**
     * Register the files from this module.
     */
    protected function registerFiles()
    {
        if (isset($this->bootstrap->files)) {

            foreach ($this->bootstrap->files as $file) {

                include $this->path . '/' . $file;
            }
        }
    }

    /**
     * Register the module commands.
     */
    protected function registerCommands()
    {

        $console = $this->app->make('Illuminate\Contracts\Console\Kernel');

        if (isset($this->bootstrap->commands)) {

            foreach ($this->bootstrap->commands as $command) {

                $console->addCommand($command);
            }
        }


    }

    /**
     * Handle call __toString.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getStudlyName();
    }

    /**
     * Determine whether the given status same with the current module status.
     *
     * @param $status
     *
     * @return bool
     */
    public function isStatus($status)
    {
        return $this->get('active', 0) === $status;
    }

    /**
     * Determine whether the current module activated.
     *
     * @return bool
     */
    public function enabled()
    {
        return $this->active();
    }

    /**
     * Alternate for "enabled" method.
     *
     * @return bool
     */
    public function active()
    {
        return $this->isStatus(1);
    }

    /**
     * Determine whether the current module not activated.
     *
     * @return bool
     */
    public function notActive()
    {
        return !$this->active();
    }

    /**
     * Alias for "notActive" method.
     *
     * @return bool
     */
    public function disabled()
    {
        return !$this->enabled();
    }

    /**
     * Set active state for current module.
     *
     * @param $active
     *
     * @return bool
     */
    public function setActive($active)
    {
        return $this->json()->set('active', $active)->save();
    }

    /**
     * Disable the current module.
     *
     * @return bool
     */
    public function disable()
    {
        $this->app['events']->fire('module.disabling', [$this]);

        $this->setActive(0);

        $this->app['events']->fire('module.disabled', [$this]);
    }

    /**
     * Enable the current module.
     */
    public function enable()
    {
        $this->app['events']->fire('module.enabling', [$this]);

        $this->setActive(1);

        $this->app['events']->fire('module.enabled', [$this]);
    }

    /**
     * Delete the current module.
     *
     * @return bool
     */
    public function delete()
    {
        return $this->json()->getFilesystem()->deleteDirectory($this->getPath());
    }

    /**
     * Get extra path.
     *
     * @param $path
     *
     * @return string
     */
    public function getExtraPath($path)
    {
        return $this->getPath() . '/' . $path;
    }

    /**
     * Handle call to __get method.
     *
     * @param $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        return $this->get($key);
    }
}
