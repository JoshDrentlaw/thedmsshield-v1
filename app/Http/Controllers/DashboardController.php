<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use App\DM;
use App\Map;
use App\Message;
use App\Invites;
use App\Debug;
use Illuminate\Support\Facades\Redirect;
use JD\Cloudder\Facades\Cloudder;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user_id = auth()->user()->id;
        $user = User::find($user_id);
        $dm = $user->dm;
        $maps = $dm->maps;
        return view('pages.dashboard', compact('maps', 'user', 'dm'));
    }

    public function message($id)
    {
        $message = Message::find($id);
        if ($message->read === 0) {
            Message::where('id', $message->id)->update(['read' => 1]);
        }
        return view('pages.message', compact('message'));
    }

    public function update(Request $request, $id, $type) {
        switch ($type) {
            case 'bio':
                $status = User::where('id', $id)->update(['bio' => $request->post('bio')]) ? 200 : 500;
                return ['status' => $status, 'message' => 'Bio saved.'];
            case 'avatar':
                return self::uploadAvatar($this, $request, $id);
            default:
                return false;
        }
    }

    public function player_search(Request $request) {
        $search = $request->post('search');
        $id = $request->post('id');
        $users = User::select('id', 'name as text', 'bio', 'avatar_url_small')->where([
            ['id', '!=', $id],
            ['id', 'like', "%$search%"]
        ])->orWhere([
            ['id', '!=', $id],
            ['name', 'like', "%$search%"]
        ])->get();
        echo json_encode(['results' => $users]);
        exit;
    }

    public static function uploadAvatar($con, $request, $id) {
        if ($request->hasFile('avatar')) {
            $con->validate($request,[
                'avatar' => 'required|mimes:jpeg,jpg,png|between:1, 1000'
            ]);
            $user = User::find($id);
            if ($user->avatar_public_id) {
                Cloudder::destroyImages([$user->avatar_public_id]);
            } else {
                $avatar_public_id = "thedmsshield.com/avatars/".$user->name."-".$user->id;
                User::where('id', $id)->update(compact('avatar_public_id'));
            }
            $filename = $request->file('avatar')->path();
            Cloudder::upload($filename, $user->avatar_public_id);
            $avatar_url = Cloudder::secureShow($user->avatar_public_id, ['width' => 180, 'height' => 180, 'crop' => 'scale', 'format' => 'jpg']);
            $avatar_url_small = Cloudder::secureShow($user->avatar_public_id, ['width' => 64, 'height' => 64, 'crop' => 'scale', 'format' => 'jpg']);
            User::where('id', $id)->update(compact('avatar_url', 'avatar_url_small'));
            return ['status' => 200, 'avatar_url' => $avatar_url, 'message' => 'Avatar updated'];
        } else {
            return ['status' => 500, 'request' => $request];
        }
    }

    public function get_pending_players(Request $request) {
        $map = Map::find($request->post('id'));
        $players = $map->pending_players;
        $html = view('components.pending-invites', compact('players'))->render();
        return compact('html');
    }

    public function send_player_invite(Request $request) {
        $map_id = $request->post('id');
        $map = Map::find($map_id);
        $dm = $map->dm;
        $player_id = $request->post('playerId');
        // NEW MAP INVITE
        $invite = new Invites;
        $invite->from_id = $dm->id;
        $invite->to_id = $player_id;
        $invite->map_id = $map_id;
        // NEW MESSAGE
        $message = new Message;
        $message->from_id = $dm->id;
        $message->to_id = $player_id;
        $message->title = "New map invite";
        $message->body = "{$dm->user->name} has invited you to join {$map->map_name}.";
        $message->message_type = 'invite';
        // ASSIGN ID'S
        $message->save();
        $invite->message_id = $message->id;
        $invite->save();
        $message->update(['map_request_id' => $invite->id]);
        // GET PENDING PLAYERS COMPONENT
        $players = $map->pending_players;
        $html = view('components.pending-invites', compact('players'))->render();
        $msg = "Player invite sent";
        return compact('html', 'msg');
    }

    public function accept_map_invite(Request $request)
    {
        Invites::find($request->post('id'))->update(['accepted' => 1]);
        $msg = 'Map invite accepts!';
        return compact('msg');
    }

    public function deny_map_invite(Request $request)
    {}
}
