<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/redis', function () use ($router) {
    // $redis = app('redis')->connection();
    $users = [
        [ 'firstname' =>'Taylor'],
        [ 'firstname' =>'Taylor1'],
    ];
    
    app('redis')->set('users', json_encode($users));
    $users = app('redis')->get('users');
    // Redis::set('name', 'Taylor');
    // $user = Redis::get('name');

    return [
        'EXISTS' => app('redis')->command('EXISTS', [ 'users' ]),
        'redis' => $users,
    ];
});

$router->get('/icdx/{q_name}', function ($q_name) use ($router) {
    $redis_key = 'icdx:get:'.$q_name.':v04';
    $EXISTS = app('redis')->command('EXISTS', [ $redis_key ]);
    if (!$EXISTS) {
        $total = app('db')->connection()->select("SELECT COUNT(*) AS count FROM icdx");
        if (!isset($q_name)) {
            $q_name = '';
        }
    
        $sql = [
            "SELECT * FROM icdx",
            "WHERE 1 = 1",
        ];
    
        if ($q_name) {
            $sql[] =  "AND name like '%$q_name%'";
        }
    
        $sql = implode(" ", $sql);
        $results = app('db')->select($sql);

        $data = [
            '$sql' => $sql,
            'q_name' => $q_name,
            'total' => $total[0]->count,
            'count_data' => count($results),
            // 'data' => $results,
        ];
        
        app('redis')->set($redis_key, json_encode($data));
        app('redis')->command('EXPIRE', [$redis_key, 10]);
    }
    return app('redis')->get($redis_key);
});
