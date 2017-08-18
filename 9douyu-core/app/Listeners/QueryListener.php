<?php
namespace App\Listeners;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\Log;
use DateTime;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
class QueryListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    /**
     * Handle the event.
     *
     * @param  QueryExecuted $event
     * @return void
     */
    public function handle(QueryExecuted $query)
    {
        //if ('local' === env('APP_ENV', 'production') || 'testing' === env('APP_ENV', 'production')) {
            $params = $query->bindings;
            foreach ($params as $index => $param) {
                if ($param instanceof DateTime) {
                    $params[$index] = $param->format('Y-m-d H:i:s');
                }
            }
            $sql = str_replace("?", "'%s'", str_replace("%", "'%%'", $query->sql) );
            array_unshift($params, $sql);
            Log::info(call_user_func_array('sprintf', $params));
        //}
    }
}