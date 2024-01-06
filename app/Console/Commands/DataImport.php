<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DataImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:data {date=текущая : Дата начала в формате ДД.ММ.ГГГГ} {days=1 : Количество дней}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Импорт данных
    {date : Дата начала в формате ДД.ММ.ГГГГ}
                        {days : Количество дней}';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $import = new \App\Services\ImportDataService($this->argument('date'),$this->argument('days'));
        $import->start();
    }
}
