<?php

namespace App\Http\Controllers\Ajax;

use App\Models\Message;
use App\Models\User;
use Auth;
use App\Events\MessageCreated;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    public $public_capsule_id;
    public function index(Request $request) {// capsule_idごとにメッセージを取得

        //file_put_contents("test.txt", var_export($request, true));
        $capsule_id = $request->capsule_id;
        $message = Message::where('capsule_id',$capsule_id) -> orderBy('id', 'desc')->get();

        return $message;
    }

    public function create(Request $request) { // メッセージを登録

        //   public/text.txtに受け取ったデータを転記
        //file_put_contents("test.txt", var_export($request['message'], true));
        // $x = var_export($request['params.message'], true);
        // $y = var_export($request['params.capsule_id'], true);

        $message = $request['message'];
        // file_put_contents("test.txt", var_export($message, true));
        $capsule_id = $request['capsule_id'];
        //ログインしているユーザーのIDを取得
        $i_am = Auth::id();
        //ログインしているユーザーIDを取得
        $i_user_id = User::find($i_am)->id;
        file_put_contents("test.txt", var_export($message, true));
        $message = Message::create([
            'comment_user' => $i_user_id,
            'capsule_id' => $capsule_id,
            'message' => $message,
        ]);
        //データベース登録
        // $message = new Mssage;
        // $message -> comment_user = $i_user_id;
        // $message -> capsule_id = $request -> capsule_id;
        // $message -> body = $request -> message;
        // $message -> save();
        event(new MessageCreated($message));
    }
}