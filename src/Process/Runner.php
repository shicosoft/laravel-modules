<?php

namespace Koyeo\Modules\Process;

use Koyeo\Modules\Contracts\RunableInterface;
use Koyeo\Modules\Repository;

class Runner implements RunableInterface
{
    /**
     * The module instance.
     *
     * @var \Koyeo\Modules\Repository
     */
    protected $module;

    /**
     * The constructor.
     *
     * @param \Koyeo\Modules\Repository $module
     */
    public function __construct(Repository $module)
    {
        $this->module = $module;
    }

    /**
     * Run the given command.
     *
     * @param string $command
     */
    public function run($command)
    {
        passthru($command);
    }
}
