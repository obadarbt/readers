<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//UserController
Route::post('/register', 'UserController@register');
Route::post('/login', 'UserController@login');
Route::post('/user_info', 'UserController@user_info');



//SubscriptionController
Route::post('/sub', 'SubscriptionController@sub');
Route::post('/getchannels', 'SubscriptionController@getchannels');
Route::post('/get_info_of_channels', 'SubscriptionController@get_info_of_channels');




//channelController
Route::post('/EditName', 'ChannelController@EditName');
Route::post('/EditDescription', 'ChannelController@EditDescription');
Route::post('/EditPersonal', 'ChannelController@EditPersonal');
Route::post('/Editbackground', 'ChannelController@Editbackground');
Route::post('/search_for_channel', 'ChannelController@search_for_channel');
Route::post('/test', 'ChannelController@test');




//FileController
Route::post('/Upload_file', 'FileController@Upload_file');
Route::post('/all_files', 'FileController@all_files');
Route::post('/delete', 'FileController@delete');
Route::post('/edit_file', 'FileController@edit_file');
Route::post('/all', 'FileController@all');
Route::post('/getfiles', 'FileController@getfiles');
Route::post('/viewfile', 'FileController@viewfile');
Route::post('/suggestedfiles', 'FileController@suggestedfiles');
Route::post('/search_for_file', 'FileController@search_for_file');


//LikeController
Route::post('/like', 'LikeController@like');

//DislikeController
Route::post('/dislike', 'DislikeController@dislike');

//ViewlaterController
Route::post('/view_later', 'ViewlaterController@view_later');
Route::post('/delete_view_later', 'ViewlaterController@delete_view_later');
Route::post('/all_view_later', 'ViewlaterController@all_view_later');


//HistoryController
Route::post('/add_to_history', 'HistoryController@add_to_history');
Route::post('/return_history16', 'HistoryController@return_history16');
Route::post('/return_history_all', 'HistoryController@return_history_all');
Route::post('/delete_history', 'HistoryController@delete_history');

//CommentController
Route::post('/add_comment', 'CommentController@add_comment');
Route::post('/return_all_comments', 'CommentController@return_all_comments');
Route::post('/number_of_comments_for_file', 'CommentController@number_of_comments_for_file');
Route::post('/return_comment', 'CommentController@return_comment');


//ReplyComment
Route::post('/add_reply', 'ReplyComment@add_reply');
Route::post('/return_all_replies', 'ReplyComment@return_all_replies');

//Playlist
Route::post('/create_playlist', 'PlaylistController@create_playlist');
Route::post('/return_all_playlist', 'PlaylistController@return_all_playlist');


