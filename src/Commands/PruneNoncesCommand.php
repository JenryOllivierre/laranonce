<?php

namespace Laranonce\Commands;

use SplFileInfo;
use Laranonce\Nonce;
use Laranonce\Config;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class PruneNoncesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nonce:prune {option=expired}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prune the nonces from storage';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $option = $this->argument('option');

        if (! in_array($option, ['expired', 'all'])) {
            return $this->error('Invalid option supplied to command...');
        }

        $this->comment('Beginning pruning process...');

        $this->comment($this->pruneDatabase($option) . ' deleted from database...');

        $this->comment($this->pruneFiles($option) . ' deleted from files...');

        $this->info('Nonces pruned successfully!');
    }

    /**
     * Clean up the database.
     *
     * @param  string  $option
     *
     * @return int
     */
    protected function pruneDatabase($option)
    {
        if (! Schema::hasTable(Config::tableName())) {
            return 0;
        }

        if ($option == 'all') {
            return Nonce::getQuery()->delete();
        }

        $date = date('Y-m-d H:i:s', now()->timestamp - Config::lifetime());

        return Nonce::where('updated_at', '<', $date)->delete();
    }

    /**
     * Clean up the files.
     *
     * @param  string  $option
     *
     * @return int
     */
    protected function pruneFiles($option)
    {
        if (! Storage::disk(Config::disk())->exists(Config::directory())) {
            return 0;
        }

        $count = 0;
        $expiryTimestamp = now()->timestamp - Config::lifetime();
        $files = Storage::disk(Config::disk())->files(Config::directory());

        foreach ($files as $relativeFilePath) {

            if ($option == 'all') {
                if (Storage::disk(Config::disk())->delete($relativeFilePath)) {
                    $count++;
                }
                continue;
            }

            if (Storage::disk(Config::disk())->lastModified($relativeFilePath) < $expiryTimestamp) {
                if (Storage::disk(Config::disk())->delete($relativeFilePath)) {
                    $count++;
                }
            }
        }

        return $count;
    }
}
