<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class ComposerInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'composer:install {--optimize : Optimize autoloader during autoload dump}
                            {--no-dev : Disables installation of require-dev packages}
                            {--detailed : Show more details during installation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run composer install command with optional flags';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Running composer install...');

        // Build the command options
        $command = ['composer', 'install'];
        
        if ($this->option('optimize-autoloader')) {
            $command[] = '--optimize-autoloader';
        }
        
        if ($this->option('no-dev')) {
            $command[] = '--no-dev';
        }
        
        if ($this->option('no-interaction')) {
            $command[] = '--no-interaction';
        }

        // Create and run the process
        $process = new Process($command);
        $process->setTimeout(null); // Set timeout to null for long running processes
        
        try {
            $process->mustRun();
            
            if ($this->option('detailed')) {
                $this->line($process->getOutput());
            }
            
            $this->info('Composer install completed successfully!');
            return 0;
        } catch (ProcessFailedException $exception) {
            $this->error('Composer install failed:');
            $this->error($exception->getMessage());
            return 1;
        }
    }
}