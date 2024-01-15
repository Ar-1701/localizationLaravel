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
        echo $locale = App::currentLocale();
        $langId = Session::get('langId');
        $data['lang'] = Language::all();
        $data['post'] = Post::whereRaw('lang_id=?', $langId)->get();
        return view('show', $data);
    }
    public function add_post()
    {
        Session::forget('langId');
        Session::forget('lang');
        $data['lang'] = Language::all();
        return view('add', $data);
    }
    public function save_post(Request $req)
    {
        // echo "<pre>";
        // print_r($req->all());
        // echo "</pre>";
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
    // public function lang(Request $req)
    // {
    //     $langId = $req->lang;
    //     $lang = Language::find($langId);
    //     App::setLocale($lang->lang);
    //     // app()->setLocale($lang->lang);
    //     echo $locale = App::currentLocale();
    //     // if (App::isLocale($locale)) {
    //     //     echo "hiiii";
    //     //     app()->setLocale($locale);
    //     // } else {
    //     //     echo "bye";
    //     //     app()->setLocale($locale);
    //     // }
    //     Session::put('langId', $langId);
    //     Session::put('lang', $lang->lang);
    //     // return redirect()->back();
    // }
    public function greeting(Request $req)
    {
        echo $req->lang;
        App::setLocale($req->lang);
        echo App::getLocale();
        // die;
        $l = Language::where('lang', App::getLocale())->first();
        $data['lang'] = Language::all();
        $data['post'] = Post::whereRaw('lang_id=?', $l->id)->get();
        Session::put('lang', $l->lang);
        Session::put('langId', $l->id);
        return view('show', $data);
    }
}
