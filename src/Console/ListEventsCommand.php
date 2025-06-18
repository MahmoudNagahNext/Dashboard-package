<?php 

namespace nextdev\nextdashboard\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ListEventsCommand extends Command
{
    protected $signature = 'nextdashboard:list-events';
    protected $description = 'List all Events created in the nextdashboard package';

    public function handle()
    {
        $eventsPath = __DIR__ . '/../Events';

        if (!File::exists($eventsPath)) {
            $this->error("Events folder not found at: $eventsPath");
            return 1;
        }

        $files = File::files($eventsPath);

        if (empty($files)) {
            $this->warn('No event files found.');
            return 0;
        }

        $this->info("📦 Events found in nextdashboard package:");

        foreach ($files as $file) {
            $className = pathinfo($file, PATHINFO_FILENAME);
            $this->line(" - " . $className);
        }

        return 0;
    }
}
