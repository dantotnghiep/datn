<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ShowTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:show-table {table}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $table = $this->argument('table');

        try {
            // Hiển thị cấu trúc bảng
            $this->info("Cấu trúc bảng {$table}:");
            $columns = DB::select("SHOW COLUMNS FROM {$table}");
            $headers = ['Field', 'Type', 'Null', 'Key', 'Default', 'Extra'];
            $rows = [];

            foreach ($columns as $column) {
                $rows[] = [
                    $column->Field,
                    $column->Type,
                    $column->Null,
                    $column->Key,
                    $column->Default,
                    $column->Extra
                ];
            }

            $this->table($headers, $rows);

            // Hiển thị dữ liệu mẫu (5 bản ghi đầu tiên)
            $this->info("Dữ liệu mẫu từ bảng {$table} (5 bản ghi đầu tiên):");
            $data = DB::table($table)->limit(5)->get();

            if (count($data) > 0) {
                $dataHeaders = array_keys((array) $data[0]);
                $dataRows = [];

                foreach ($data as $row) {
                    $dataRow = [];
                    foreach ((array) $row as $value) {
                        if (is_null($value)) {
                            $dataRow[] = 'NULL';
                        } else {
                            $dataRow[] = substr((string) $value, 0, 30);
                        }
                    }
                    $dataRows[] = $dataRow;
                }

                $this->table($dataHeaders, $dataRows);
            } else {
                $this->warn("Không có dữ liệu trong bảng {$table}.");
            }

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Lỗi: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
