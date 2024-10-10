<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Pluralizer;

abstract class FileFactoryCommand extends Command

{


    /**
     * Summary of file
     *
     * first think create function construct for filesystem injection(depedancy injection)
     * then create function for change the name of file to single class name and the first char is uppar case
     * then create a fun for make diractory for file and give the owner permission for read and write (0777)
     * now the file we create stub: we need to replace the variable in it like $NAME we make for that stubvar and to access to the file we make stubpath
     * in the stubcontent fun we will give it stubpath and stubvar to replace the var and the content
     * then we create the getpath fun to get the path of file to do the operation on him
     *  in the end ::::::::::-||
     * ----------------------- in the function handle we overreide the functions we created befoar
     *
     * 1-get the path by =>getpath()
     * 2- give the path to makedir() to give the permission and create the file
     * 3-chack if the file exist befoar or not
     * 4-replace the variable for this file by =>stubcontent(stubpath and stubvar)
     * 5-use function file->put to put the file in path with his content
     * 6- return a message for user to end the operation
     */
    protected $file;

    public function __construct(Filesystem $file)
    {
        parent::__construct();
        $this->file = $file;
    }

    abstract function setstubname(): string;
    abstract function setfilepath(): string;
    abstract function setsufix(): string;

    public function singleClassName($name)
    {
        return ucwords(Pluralizer::singular($name));
    }

    public function makedir($path)
    {
        $this->file->makeDirectory($path, 0777, true, true);
        return $path;
    }
    public function stubpath()
    {
        $stubname = $this->setstubname();
        return __DIR__ . "/../../../stubs/{$stubname}.stub";
    }

    public function stubvar()
    {
        return [
            'NAME' => $this->singleClassName($this->argument('classname'))
        ];
    }

    public function stubcontent($stubpath, $stubvar)
    {
        $content = file_get_contents($stubpath);

        foreach ($stubvar as $search => $name) {
            $contents = str_replace('$' . $search, $name, $content);
        }
        return $contents;
    }



    public function getpath()
    {
        $filepath = $this->setfilepath();
        $sufix = $this->setsufix();
        return base_path($filepath) . $this->singleClassName($this->argument('classname')) . "{$sufix}.php";
    }
    public function handle()
    {

        $path = $this->getpath();

        $this->makedir(dirname($path));

        if ($this->file->exists($path)) {
            $this->info("this file is exist befoar");
        }

        $content = $this->stubcontent($this->stubpath(), $this->stubvar());
        $this->file->put($path, $content);
        $this->info("this file has been created");
    }
}