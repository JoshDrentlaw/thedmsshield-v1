<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Invites;
use App\Models\Debug;

class MessagesController extends Controller
{
    public function mark_message_read(Request $request)
    {
        $unread = $request->post('unread');
        if (is_array($unread)) {
            foreach ($unread as $msg) {
                Message::where(['id' => $msg])->update(['read' => 1]);
            }
        } else {
            Message::where(['id' => $unread])->update(['read' => 1]);
        }
        return ['status' => 200];
    }

    public function mark_message_unread(Request $request)
    {
        $read = $request->post('read');
        if (is_array($read)) {
            foreach ($read as $msg) {
                Message::where(['id' => $msg])->update(['read' => 0]);
            }
        } else {
            Message::where(['id' => $read])->update(['read' => 0]);
        }
        return ['status' => 200];
    }
}