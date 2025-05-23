<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ShowTableStructure extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'table:structure {table : The name of the table}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show the structure of a database table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $table = $this->argument('table');
        
        if (!Schema::hasTable($table)) {
            $this->error("Table '{$table}' does not exist!");
            return 1;
        }
        
        // Get column information using DB facade
        $columns = DB::select("SHOW COLUMNS FROM {$table}");
        
        // Display column information
        $headers = ['Field', 'Type', 'Null', 'Key', 'Default', 'Extra'];
        $rows = [];
        
        foreach ($columns as $column) {
            $rows[] = [
                $column->Field,
                $column->Type,
                $column->Null,
                $column->Key,
                $column->Default ?? 'NULL',
                $column->Extra
            ];
        }
        
        $this->table($headers, $rows);
        
        return 0;
    }
} 