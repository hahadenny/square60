<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Helpers\Common\FileLogger;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrapThree();

        DB::listen(function ($query) {
            /*var_dump([
                $query->sql,
                $query->bindings,
                $query->time
            ]);*/

            $bindings = $query->bindings;
            $query = $query->sql;

            foreach ($bindings as $i => $binding)
            {
                if ($binding instanceof \DateTime)
                {
                    $bindings[$i] = $binding->format('\'Y-m-d H:i:s\'');
                }
                else if (is_string($binding))
                {
                    $bindings[$i] = "'$binding'";
                }
            }
            
            // Insert bindings into query
            $query = str_replace(array('%', '?'), array('%%', '%s'), $query);
            $query = vsprintf($query, $bindings);

            //echo $query.';<br><br>';
            //Log::debug($query . ";");

            $level = 'DEBUG';
            $logger = new FileLogger('query.log', $level);
            $logger->addRecord($level, $query);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
