<?php

use Illuminate\Database\Seeder;
use App\LogEntry;
use App\Service;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        /* Сгенерируем 100 записей в логах (таблица "log_entries") и 100 имён процессов (таблица "services") */
        $faker = Faker\Factory::create();

        /* Сгенерируем данные для таблицы services */
        for($i = 0; $i < 100; $i++){
            $fakerName = $faker->unique()->word;
            Service::updateOrCreate(
                [
                    'name' => $fakerName
                ],
                [
                    'name' => $fakerName,
                ]
            );
        }

        /* Теперь сгенерируем сами записи логов */

        $services = Service::select('id', 'name')->get()->keyBy('id')->toArray();

        $timeStamp = 1487082894; // 14/02/2017 14:34:54 GMT
        for($i = 0; $i < 100; $i++){
            $userId = $faker->unique()->bothify('#?######-#??#-#?##-?#?#-?#??######?#');
            $userName = $faker->unique()->name;
            $internalIp = $faker->localIpv4;
            $timeStamp+=mt_rand(30000, 50000);
            $appVersion = $faker->regexify('[0-1]\.[0-9]\.[1-9]');
            $serviceIds = $faker->unique()->randomElements(array_keys($services), $count = mt_rand(1, 10));

            LogEntry::updateOrCreate(
                [
                    'user_id' => $userId
                ],
                [
                    'user_id' => $userId,
                    'user_name' => $userName,
                    'app_version' => $appVersion,
                    'internal_ip' => $internalIp,
                    'created_at' => $timeStamp,
                ]
            )->services()->sync($serviceIds);
        }

        # Фикс на изменение данных в pivot-таблице "log_entry_service", т.к. через модель не поменять
        # (ибо модели для pivot не предусматривается
        $logEntries= LogEntry::select()->orderBy('id', 'desc')->get()->keyBy('id')->toArray();
        foreach($logEntries as $logEntry){
            DB::update('update log_entry_service set created_at= ? where log_entry_id = ?',
                [$logEntry['created_at'],$logEntry['id']]);
        }

    }
}
