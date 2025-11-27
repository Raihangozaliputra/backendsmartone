<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\FaceEmbedding;
use Carbon\Carbon;

class CleanFaceEmbeddings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'face:clean {--days=30 : Number of days to keep face embeddings}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up old face embeddings that are no longer needed';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $days = $this->option('days');
        $cutoffDate = Carbon::now()->subDays($days);

        $deletedCount = FaceEmbedding::where('created_at', '<', $cutoffDate)->delete();

        $this->info("Deleted {$deletedCount} face embeddings older than {$days} days.");

        return 0;
    }
}