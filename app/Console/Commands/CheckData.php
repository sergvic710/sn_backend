<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:check {date=текущая : Дата начала в формате ДД.ММ.ГГГГ} {sendmail=1 : Отправлять письмо если нет данных}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Проверка данных.   {date : Дата начала в формате ДД.ММ.ГГГГ}
    {sendmail : 1 или 0. Отправлять почту если нет данных. По умолчанию - не отправлять 0}';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
         \App\Services\ImportDataService::checkData($this->argument('date'),$this->argument('sendmail'));
    }
}
