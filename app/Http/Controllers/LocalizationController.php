<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;

class LocalizationController extends Controller
{
    public function index()
    {
        echo App::currentLocale();
        $langId = Session::get('langId');
        $data['lang'] = Language::all();
        $data['post'] = Post::whereRaw('lang_id=?', $langId)->get();
        return view('show', $data);
    }
    public function add_post()
    {
        $data['lang'] = Language::all();
        return view('add', $data);
    }
    public function save_post(Request $req)
    {
        if ($req->hasFile('img')) {
            $image = $req->file('img');
            $imgName = time() . $image->getClientOriginalName();
            $image->move("public/upload/post", $imgName);
        }
        foreach ($req->locale as $key => $l) {
            $lang_id = $req->locale[$key];
            $title = $req->title[$key];
            $post_cat = $req->post_cat[$key];
            $post_desc = $req->post_desc[$key];
            $i = new Post;
            $i->post_title = $title;
            $i->post_cat = $post_cat;
            $i->post_desc = $post_desc;
            $i->post_img = $imgName;
            $i->lang_id = $lang_id;
            $i->save();
        }
        echo "save";
    }
    public function lang(Request $req)
    {
        $langId = $req->lang_id;
        $lang = Language::find($langId);
        App::setLocale($lang->lang);
        Session::put('langId', $langId);
        Session::put('lang', $lang->lang);
    }
    public function show_post()
    {
        $data['post'] = Post::all();
        return view('showPost', $data);
    }
}
