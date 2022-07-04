<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use stream_socket_server;
use stream_socket_accept;
use Exception;
use Laravel\Octane\Facades\Octane;
use App\Models\User;
use App\Models\Messages;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SwooleController extends Controller
{

    public function insertUsers()
    {
        $insertData = [];
        for($i=0; $i <= 9999; $i++) {
            $insertData[$i] = [
                'name' => Str::random(10),
                'email' => Str::random(10).'@'.Str::random(5).'.'.Str::random(3),
                'password' => rand(1,99999)
            ];
        }

        User::insert($insertData);

        return true;
    }

    public function insertMessage()
    {
        $insertData = [];
        for($i=0; $i <= 9999; $i++) {
            $random = Carbon::today()->subDays(rand(0, 365))->subHours(rand(0, 24))->subMinutes(rand(0, 59))->subSeconds(rand(0, 59))->format('Y-m-d H:i:s');
            $insertData[$i] = [
                'user_id' => rand(1,999),
                'subject' => Str::random(10),
                'content' => Str::random(50),
                'created_at' => $random,
                'updated_at' => $random,
            ];
        }

        Messages::insert($insertData);

        return true;

    }

    public function test()
    {
        $result = Octane::concurrently([
            function () {
                    User::All();
                    return 'swoole-1-'.date('Y-m-d H:i:s');
            },
            function () {
                    Messages::All();
                    return 'swoole-2-'.date('Y-m-d H:i:s');
            },
        ]);

        return $result;
    }
}
