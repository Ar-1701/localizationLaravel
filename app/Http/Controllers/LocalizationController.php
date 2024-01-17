<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class LocalizationController extends Controller
{
    public function index()
    {
        $langId = Session::get('langId');
        $data['lang'] = $this->language();
        $data['post'] = Post::whereRaw('language_id=?', $langId)->limit(1)->get();
        return view('show', $data);
    }
    // public function add_post(Request $req)
    // {
    //     $id = $req->id;
    //     $lang_id = $req->lang_id;
    //     $post_id = $req->post_id;
    //     $data['singleLang'] = Language::where(['id' => $lang_id])->get();
    //     // echo "<pre>";
    //     // print_r($data['singleLang']);
    //     $data['singleData'] = Post::find($id);
    //     $data['lang'] = Language::all();
    //     return view('add', $data);
    // }
    public function add_post(Request $req)
    {
        $id = $req->id;
        $lang_id = $req->lang_id;
        $post_id = $req->post_id;
        // $data['singleData'] = Post::whereRaw('post_id=?', [$post_id])->get();
        $data['singleData'] = Post::with('language')->whereRaw('post_id=?', [$post_id])->get();
        // echo "<pre>";
        // print_r($d);
        // die;
        $data['lang'] = Language::all();
        return view('add', $data);
    }
    public function save_post(Request $req)
    {
        // echo "<pre>";
        // print_r($req->all());
        // echo "</pre>";

        $id = $req->id;
        $lang_id = $req->locale;
        $title = $req->title;
        $post_cat = $req->post_cat;
        $post_desc = $req->post_desc;
        $u = Post::find($id);
        if ($req->hasFile('img')) {
            $image = $req->file('img');
            $imgName = time() . $image->getClientOriginalName();
            if (isset($u)) {
                File::delete(public_path('upload/post/' . $imgName));
                $image->move("public/upload/post", $imgName);
            }
        } else {
            $imgName = $req->old_img;
        }
        if (isset($u)) {
            foreach ($req->locale as $key => $l) {
                $id = $req->id[$key];
                $title = $req->title[$key];
                $post_cat = $req->post_cat[$key];
                $post_desc = $req->post_desc[$key];
                $i = Post::find($id);
                $i->post_title = $title;
                $i->post_cat = $post_cat;
                $i->post_desc = $post_desc;
                $i->post_img = $imgName;
                $i->save();
            }
            echo "update";
        } else {
            $post_id = "P" . rand(1111, 9999);
            foreach ($req->locale as $key => $l) {
                $l = Language::find($req->locale[$key]);
                $lang_id = $req->locale[$key];
                $title = $req->title[$key];
                $post_cat = $req->post_cat[$key];
                $post_desc = $req->post_desc[$key];
                $i = new Post();
                $i->post_id = $post_id;
                $i->language_id = $lang_id;
                $i->post_title = $title;
                $i->post_cat = $post_cat;
                $i->post_desc = $post_desc;
                $i->post_img = $imgName;
                $l->posts()->save($i);
            }
            $image->move("public/upload/post", $imgName);
            echo "save";
        }
    }
    public function delete(Request $req)
    {
        echo $id = $req->id;
        echo $lang_id = $req->lang_id;
        echo $post_id = $req->post_id;
        $d = Post::whereRaw('post_id=?', $post_id)->delete();
        return redirect('/');
    }
    // public function save_post(Request $req)
    // {
    //     $id = $req->id;
    //     $lang_id = $req->locale;
    //     $title = $req->title;
    //     $post_cat = $req->post_cat;
    //     $post_desc = $req->post_desc;
    //     $u = Post::find($id);
    //     if ($req->hasFile('img')) {
    //         $image = $req->file('img');
    //         $imgName = time() . $image->getClientOriginalName();
    //         if (isset($u)) {
    //             File::delete(public_path('upload/post/' . $imgName));
    //             $image->move("public/upload/post", $imgName);
    //         }
    //     } else {
    //         $imgName = $req->old_img;
    //     }
    //     if (isset($u)) {
    //         foreach ($req->locale as $key => $l) {
    //             $id = $req->id[$key];
    //             $title = $req->title[$key];
    //             $post_cat = $req->post_cat[$key];
    //             $post_desc = $req->post_desc[$key];
    //             $i = Post::find($id);
    //             $i->post_title = $title;
    //             $i->post_cat = $post_cat;
    //             $i->post_desc = $post_desc;
    //             $i->post_img = $imgName;
    //             $i->save();
    //         }
    //         echo "update";
    //     } else {
    //         $post_id = "P" . rand(1111, 9999);
    //         foreach ($req->locale as $key => $l) {
    //             $lang_id = $req->locale[$key];
    //             $title = $req->title[$key];
    //             $post_cat = $req->post_cat[$key];
    //             $post_desc = $req->post_desc[$key];
    //             $i = new Post();
    //             $i->post_id = $post_id;
    //             $i->language_id = $lang_id;
    //             $i->post_title = $title;
    //             $i->post_cat = $post_cat;
    //             $i->post_desc = $post_desc;
    //             $i->post_img = $imgName;
    //             $i->save();
    //         }
    //         $image->move("public/upload/post", $imgName);
    //         echo "save";
    //     }
    // }

    public function lang(Request $req)
    {
        $langId = $req->lang_id;
        $lang = Language::find($langId);
        App::setLocale($lang->lang);
        Session::put('langId', $langId);
        Session::put('lang', $lang->langCode);
    }
    public function language()
    {
        return Language::all();
    }
    public function posts(Post $post)
    {
        $data['lang'] = $this->language();
        $post = Post::whereRaw('language_id=?', Session::get('langId'))->get();
        $all_array = array();
        foreach ($post as $val) {
            $arr['id'] = $val->id;
            $arr['post_id'] = $val->post_id;
            $arr['language_id'] = $val->language_id;
            $arr['post_title'] = $val->post_title;
            $arr['post_cat'] = $val->post_cat;
            $arr['post_desc'] = $val->post_desc;
            $arr['post_img'] = $val->post_img;
            if (!in_array($arr['post_id'], array_column($all_array, 'post_id'))) {
                array_push($all_array, $arr);
            }
        }
        $data['post'] = $all_array;
        return view('post', $data);
    }
    public function loadData(Request $req)
    {
        $limit = 3;
        if (isset($req->page)) {
            $page = $req->page;
        } else {
            $page = 0;
        }
        $post = DB::select('select * from posts limit ? offset ?', [$limit, $page]);
        if (!empty($post)) {
            $str = "";
            foreach ($post as $row) {
                $pid = $row->id;
                $str .= "<div class='column'>
            <div class='card'>                
            <img src='" .  asset("public/upload/post/" . $row->post_img) . "' alt='Jane'>
            <div class='container mt-2'>
            <h2> $row->post_title </h2>
            <p class='title'>  $row->post_cat  </p>
            <p> $row->post_desc </p>
            </div>
            </div>
            </div>";
            }
            $str .= "<div class='col-md-12 text-center mb-2 btnBox'>
             <button class='btn btn-success w-25 text-center loadBtns' id='loadBtn'
            data-id='$pid' onclick='loadBtn()'>Load More</button>
            </div>";
            // $req = ["pid" => $pid, "data" => $str];
            echo $str;
            // echo json_encode($req);
        } else {
            echo "";
        }
    }
}
