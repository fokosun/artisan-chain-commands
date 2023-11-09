<?php

namespace Fokosun\ArtisanChainCommands\Console;

use Illuminate\Console\Command;

class ChainCommands extends Command
{
    protected $signature = 'chain-commands {commands}';

    protected $description = 'Run multiple artisan commands all at once with a very short, simple and sweet syntax.';

    protected array $toBeIgnored = [
        'chain-commands' => 'Not allowed',
        'db' => "This opens a REPL (Read, Evaluate, Print and Loop) environment.\nYou should not run this command with chain commands.",
        'docs' => "This opens the Laravel documentation page in a browser window.\nYou should not run this command with chain commands.",
        'docs 1' => "This opens the Laravel documentation page in a browser window.\nYou should not run this command with chain commands.",
        'docs 2' => "This opens the Laravel documentation page in a browser window.\nYou should not run this command with chain commands.",
        'docs -- search query' => "This opens the Laravel documentation page in a browser window.\nYou should not run this command with chain commands.",
        'docs -- search query here' => "This opens the Laravel documentation page in a browser window.\nYou should not run this command with chain commands.",
        'help' => "Displays help for a command.\nYou should not run this command with chain commands.",
        'serve' => 'Not allowed',
        'test' => 'Not allowed.',
        'tinker' => "This opens a REPL (Read, Evaluate, Print and Loop) environment.\nYou should not run this command with chain commands.",
        'vendor:publish'  => "This is an interactive command.\nYou should not run this command with chain commands.",
        'schedule:work' => "Starts the schedule worker.\nYou should not run this command with chain commands.",
        'queue:work' => "Starts processing jobs on the queue as a daemon.\nYou should not run this command with chain commands.",
        'queue:restart' => "Restarts queue worker daemons after their current job.\nYou should not run this command with chain commands.",
        'queue:listen' => "Listens to a given queue.\nYou should not run this command with chain commands."
    ];

    protected array $shortHandCmds = [
        'clear:ccv' => ['config:clear', 'cache:clear', 'view:clear'],
        'clear:*' => ['config:clear', 'cache:clear', 'view:clear','event:clear', 'optimize:clear', 'queue:clear'],
        'db:rms' => ['migrate:refresh', 'migrate', 'db:seed'],
        'show:ignored' => ['*']
    ];

    protected array $status = [
        1 => 'failed',
        0 => 'success'
    ];

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $commands = collect(explode(',', $this->argument('commands')));
        $showIgnoredList = false;

        $toIgnore = $commands->filter(function($cmd){
            return in_array($cmd, array_keys($this->toBeIgnored));
        });

        $commands = $commands->filter(function ($cmd) {
            return !in_array($cmd, array_keys($this->toBeIgnored));
        });

        $commands = collect(array_unique(array_values($commands->toArray())));

        $rows = [];

        $this->alert("ARTISAN CHAIN COMMANDS v1.0.0");

        $commands->each(function ($cmd) use(&$rows, &$showIgnoredList) {
            if (trim($cmd) != "") {
                $output = null;
                $retVal = null;
                $comment = trim($cmd);

                if (isset($this->shortHandCmds[$cmd])) {
                    if (trim($cmd) != 'show:ignored') {
                        foreach ($this->shortHandCmds[$cmd] as $h) {
                            exec('php artisan ' . $h, $output, $retVal);
                        }
                        $comment = json_encode($this->shortHandCmds[$cmd]);
                    } else {
                        $showIgnoredList = true;
                    }
                } else {
                    exec('php artisan ' . $cmd, $output, $retVal);
                }

                $output = $this->formatOutput((array)$output);

                if (!$showIgnoredList) {
                    $status = ($retVal == 1) ? '<error>' . $this->status[$retVal] . '</error>' : '<info>' . $this->status[$retVal] . '</info>';

                    $rows[] = [trim($cmd), $comment, $status, $output];
                }
            }
        });

        if ($toIgnore->isNotEmpty()) {
            $values = $toIgnore->values()->toArray();
            collect($this->toBeIgnored)->map(function ($msg, $cmd) use(&$rows, $values) {
                if (in_array($cmd, $values)) {
                    $rows[] = [
                        trim($cmd), '-', '<error>ignored</error>', $msg
                    ];
                }
            });
        }

        if ($rows) {
            $this->table(['Command', '', 'Status', 'Output'], $rows);
        }

        $this->info("Display a list of commands that are not  supported.");
        if ($showIgnoredList) {
            $rows = [];
            foreach($this->toBeIgnored as $key => $row) {
                $rows[] = [$key, $row];
            }
            $this->table(['Command', 'Info'], $rows);
        }
    }

    private function formatOutput(array $output): string
    {
        $str = "";
        $items = collect($output);

        $res = $items->filter(function ($item) {
            return $item != "";
        });

        $res->map(function ($e) use(&$str) {
            $str .= trim($e) . "\n";
        });

        return $str;
    }
}
