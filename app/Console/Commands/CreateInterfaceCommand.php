<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateInterfaceCommand extends FileFactoryCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:interface {classname}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */


    function setstubname(): string
    {
        return "interface";
    }
    function setfilepath(): string
    {
        return "App\\Interfaces\\";
    }
    function setsufix(): string
    {
        return "Interface";
    }

    // function handle()
    // {
    //     parent::handle();
    //     $this->info("Interface class created successfuly");
    // }
}