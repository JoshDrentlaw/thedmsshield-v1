<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Player;
use App\Models\Message;
use App\Models\Invites;
use App\Models\Debug;
use App\Models\Map;
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
        $campaigns = $dm->campaigns;
        $player = $user->player;
        $playingIn = $player->campaigns;
        return view('pages.dashboard', compact('campaigns', 'playingIn', 'user', 'dm'));
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
        $users = User::select('id', 'name as text', 'bio', 'avatar_public_id')->where([
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
            $env = env('APP_ENV');
            $username = $user->username;
            $avatar_public_id = "thedmsshield.com/{$env}/users/{$username}/{$username}_avatar";
            User::where('id', $id)->update(compact('avatar_public_id'));
            $filename = $request->file('avatar')->path();
            Cloudder::upload($filename, $user->avatar_public_id);
            return ['status' => 200, 'avatar_public_id' => $avatar_public_id, 'message' => 'Avatar updated'];
        } else {
            return ['status' => 500, 'request' => $request];
        }
    }

    public function get_pending_players(Request $request) {
        $campaign = Campaign::find($request->post('id'));
        $players = $campaign->pending_players;
        $html = view('components.pending-invites', compact('players'))->render();
        return compact('html');
    }

    public function send_player_invite(Request $request) {
        $campaign_id = $request->post('id');
        $campaign = Campaign::find($campaign_id);
        $dm = $campaign->dm;
        $player_id = $request->post('playerId');
        // NEW MAP INVITE
        $invite = new Invites;
        $invite->from_id = $dm->id;
        $invite->to_id = $player_id;
        $invite->campaign_id = $campaign_id;
        $invite->save();
        // NEW MESSAGE
        $message = new Message;
        $message->from_id = $dm->id;
        $message->to_id = $player_id;
        $message->title = "New campaign invite";
        $message->body = "{$dm->user->name} has invited you to join {$campaign->name}.";
        $message->message_type = 'invite';
        // ASSIGN ID'S
        $message->invite_id = $invite->id;
        $message->save();
        $invite->update(['message_id' => $message->id]);
        // GET PENDING PLAYERS COMPONENT
        $players = $campaign->pending_players;
        $html = view('components.pending-invites', compact('players'))->render();
        $msg = "Player invite sent";
        return compact('html', 'msg');
    }

    public function accept_map_invite(Request $request)
    {
        $invite = Invites::find($request->post('id'));
        $invite->update(['accepted' => 1]);
        $campaign_id = $invite->campaign_id;
        $player_id = $invite->to_id;
        $player = Player::find($player_id);
        $player->campaigns()->sync([$campaign_id], false);
        $msg = 'Campaign invite accepted!';
        return compact('msg');
    }

    public function deny_map_invite(Request $request)
    {}
}