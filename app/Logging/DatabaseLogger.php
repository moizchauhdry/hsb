<?php

namespace App\Logging;

use Monolog\Logger;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\LogRecord; // LogRecord introduced in Monolog 3.x
use Illuminate\Support\Facades\DB;

class DatabaseLogger
{
    public function __invoke(array $config)
    {
        $logger = new Logger('database');
        $logger->pushHandler(new DatabaseHandler());
        return $logger;
    }
}

class DatabaseHandler extends AbstractProcessingHandler
{
    /**
     * Writes the record down to the log of the implementing handler
     *
     * @param LogRecord $record The record to write (Monolog 3.x uses LogRecord)
     *
     * @return void
     */
    protected function write(LogRecord $record): void
    {
        $user_id = auth()->check() ? auth()->id() : 0;
        $type = $record->context['type'] ?? 'default';
        $import_completed = $record->context['import_completed'] ?? false;

        DB::table('error_logs')->insert([
            'user_id' => $user_id,
            'channel' => $record->channel,
            'type' => $type,
            'import_completed' => $import_completed,
            'message' => $record->message,
            'level' => $record->level->value, // Numeric level of logging (e.g. 100 for DEBUG, 200 for INFO)
            'level_name' => $record->level->name, // Log level as a string (e.g. 'INFO', 'DEBUG')
            'unix_time' => $record->datetime->getTimestamp(), // Datetime object converted to a Unix timestamp
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
