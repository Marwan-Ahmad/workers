<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Pluralizer;

class CreateServiceClass extends FileFactoryCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {classname}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this command for create service class pattern';

    /**
     * Execute the console command.
     */

    function setstubname(): string
    {
        return "servicepattern";
    }
    function setfilepath(): string
    {
        return "App\\Services\\";
    }
    function setsufix(): string
    {
        return "Service";
    }
    function handle()
    {
        parent::handle();
        $this->info("Service class created successfuly");
    }
}