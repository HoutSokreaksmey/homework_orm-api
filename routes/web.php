<?php

use App\Country;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Model\Post;
use App\Photo;
use App\User;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/read', function () {
    $posts = DB::select('select * from posts');
    return view('Posts.index', ['post'=>$posts]);

    // foreach ($posts as $post) {
    //     ehco $post->title;
    // }
});

Route::get('/insertpost', function () {
    DB::insert('insert into posts (title, content) values (?, ?)', ['This is my title', 'This is my content']);
    return view('Posts.insert');
    // return 'Success';
});

Route::get('/update', function () {
    $update = DB::update('update posts set title = "This is title1" where id = ?', [1]);
    // return view('Posts.update');
    return "This is" .$update. "updated"; 
});

Route::get('/delete', function () {
    $delete = DB::delete('delete from posts where id = ?', ['1']);
    return "This is" .$delete. "deleted";
});

Route::get('insert_eloquent', function () {
    $post=new Post;
    // $post->user_id='1';
    $post->title='This is title 3';
    $post->content='This is content 3';
    $post->save();
    return 'Insert Success!';
});

Route::get('update_eloquent', function () {
    $post=Post::find(1);
    $post->user_id='1';
    $post->title='This is title updated 1';
    $post->content='This is content updated 1';
    $post->save();
    // dd($post);
    return 'Updated';    
});

// Mass Assignment = assign via model There are 'fillable' and 'Guarded' 
Route::get('create', function () {
    Post::create(['title'=>'This is title mass assignment','content'=>'This is content mass assignment']);
    return 'Created';
});

Route::get('read_eloquent', function () {
    $posts=Post::all();
    // dd($posts);    
    foreach ($posts as $post) {
        echo $post->id.'.' ." ". $post->title ." ". $post->content . "<br>";
    }
});

// Query find= when not id display error  findorfail = display 404 not found
Route::get('/find', function () {
    // $posts=Post::findorfail(2);
    $posts=Post::find(2);
    return $posts->id .'.'.' '. $posts->title .''. $posts->content;
});

Route::get('/findwhere', function () {
    // $posts=Post::where('id', 2)->orderBy('id','desc')->take(2)->get();
    // foreach ($posts as $post) {
    //     echo $post->title .''. $post->content ."<br>";
    // }
    $posts=Post::where('id', 2)->firstorfail();
    echo $posts->title .' '. $posts->content;
});

Route::get('/update_eloquent_mass', function () {
    // Post::where('id',2)->where('title','This is my title')->update(['title'=>'This title updated','content'=>'This is content updated']);
    Post::where('id',2)->update(['title'=>'This title updated','content'=>'This is content updated']);
    return 'Updated';
});

Route::get('/delete_eloquent', function () {
    // $posts=Post::find(4);
    // $posts->delete();
    Post::find(2)->delete();
    return 'Deleted';
    // return redirect()->route('read_eloquent')->with(['message'=> 'Successfully deleted!!']);
});

Route::get('/softdelete', function () {
    $posts= Post::onlyTrashed()->get();
    // $posts=Post::withTrashed()->where('id', 4)->get();
    foreach ($posts as $post) {
        echo $post->id.'.' ." ". $post->title ." ". $post->content . " ". '|' . $post->deleted_at ."<br>";
    }
});

Route::get('/restore', function () {
    // Post::withTrashed()->restore();
    Post::withTrashed()->where('id', 2)->restore();
    return 'Restored';
});

Route::get('/forcedelete', function () {
    Post::withTrashed()->where('id', 4)->forceDelete();
    return 'Force Deleted';
});

// Eloquent Relationships
// One to Many Realationship
Route::get('/posts', function () {
    $user = User::find(1);
    foreach ($user->posts as $post) {
        echo $post->title ." ". $post->content . "<br>";
    }
});

// One to One relationship
Route::get('user/{id}/post', function ($id) {
    //return User::find($id)->post->content;    //post = function in model user
    // return User::find($id)->post;

    $posts = User::find($id)->post;
    return $posts->title .' '. $posts->content;

    // return User::find($id)->posts->title;
});

Route::get('post/{id}/user', function ($id) {
    // return Post::find($id)->user->name;
    $users = Post::find($id)->user;
    return $users->name .'|'." ". $users->email;
});

// Route::get('user/{id}/role', function ($id) {
//     $user= User::find($id);
//     foreach ($user->roles as $role) {
//         echo  $user->name. ':'." ".'<b>'. $role->name .'</b>';
//     }
// });

// Accessing intermediate table/pivot
Route::get('user/pivot', function() {
    $user = User::find(3);
    // return $user;
    foreach ($user->roles as $role) {
        echo $role->pivot->created_at;
        echo $role->pivot->title;
    }
});

Route::get('user/country', function () {
    $country= Country::find(1);
    // return $country;
    foreach ($country->posts as $post) {
        echo $post->title . '</br>';
    }
});

Route::get('user/photo', function () {
    $user = User::find(1);
    foreach ($user->photos as $photo) {
        echo $photo->path .'</br>';
    }
});

Route::get('post/photos', function () {
    $post = Post::find(1);
    foreach ($post->photos as $photo) {
        echo $photo->path .'</br>';
    }
});

Route::get('photo/{id}/post', function ($id) {
    $photo=Photo::findOrFail($id);
    return $photo->imageable;
});