<?php

namespace App\Console\Commands\Test;

use App\Concerns\Commands\GeneratesModuleFiles;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:test-command')]
#[Description('Command description')]
class TestCommand extends Command
{
    use GeneratesModuleFiles;
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->setup('TestModule', 'Shared/Folder/TestModel');

        $this->writeFile(
            stub: 'test/test',
            path: 'Controllers',
            label: 'Folders/TestController',
            labelPrefix: 'Controller',
        );

        dd($this->toString());
    }
}
