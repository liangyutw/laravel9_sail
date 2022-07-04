<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Models\Messages;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class ChatController extends Controller
{
    public function sendMessage(Request $request)
    {
        $data = [
            'content' => htmlentities($request->input('message')),
            'username' => Str::random(rand(4, 8)).' '.Str::random(rand(4, 8)),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $insertResult = Messages::insert($data);
        if ($insertResult != true) {
            return false;
        }

        return [
            'content' => htmlspecialchars_decode($data['content']),
            'created_at' => $data['created_at']
        ];
    }

    public function getMessage()
    {
        return collect(Messages::all())->map(function ($item, $key) {
                $item->created_at = Carbon::parse($item->created_at)->format('Y-m-d H:i:s');
                $item->content = htmlspecialchars_decode($item->content);

                return $item;
            })->sortBy(['id', 'desc'])->all();
    }
}
