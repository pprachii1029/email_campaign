<?php

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
    return Redirect::to('/verify_email');
});

Auth::routes(['verify' => true]);
 
Route::get('/home', 'ContactController@index')->name('home');

Route::get('/add_contact','ContactController@add_contact_view')->name('add_contact');

Route::post('/add_contact','ContactController@add_contact')->name('add_contact');

Route::get('/add_group',function(){
    return view('add_group');
})->name('add_group');

Route::post('/add_group','ContactController@add_group')->name('add_group');

Route::get('/edit_contact/{contact_id}','ContactController@edit_contact')->name('edit_contact');

Route::post('/update_contact','ContactController@update_contact')->name('update_contact');

Route::get('/view_contact','ContactController@view_contact')->name('view_contact');

Route::post('/save_csv_contacts','ContactController@save_csv_contacts')->name('save_csv_contacts');

Route::get('/unsubscribed_contact','ContactController@unsubscribed_contacts')->name('unsubscribed');

Route::get('/notifications','NotificationController@notifications')->name('notifications');

Route::get('/compose_message','MessageController@compose_message')->name('compose_message');

Route::post('/send_email_to_contact','MessageController@send_email_to_contact')->name('send_email_to_contact');

Route::get('/sent_messages','MessageController@sent_messages')->name('sent_messages');

Route::get('/sent_campaigns','MessageController@sent_campaigns')->name('sent_campaigns');

Route::get('/create_campaign','MessageController@create_campaign')->name('create_campaign');

Route::post('/send_email_in_group','MessageController@send_email_in_group')->name('send_email_in_group');

Route::get('/send_template_email','MessageController@send_template_email')->name('send_template_email');


Route::get('/templates','TemplateController@templates')->name('templates');

Route::get('/add_template','TemplateController@add_template')->name('add_template');

Route::get('/unlayer_template','TemplateController@unlayer_template')->name('unlayer_template');

Route::get('/email_template_list','AjaxController@get_email_templates')->name('email_template_list');

Route::post('/save_template','TemplateController@save_template')->name('save_template');

Route::get('/arrange_template_content','TemplateController@arrange_template_content')->name('arrange_template_content');

Route::get('/preview_template','TemplateController@preview_template')->name('preview_template');

Route::post('/make_video','TemplateController@make_video')->name('make_video');

Route::post('/final_video','TemplateController@final_video')->name('final_video');

Route::get('/suppression','SuppressionController@index')->name('suppression');

Route::get('/add_suppression','SuppressionController@add_suppression')->name('add_suppression');

Route::post('/save_suppression','SuppressionController@save_suppression')->name('save_suppression');
//---
Route::get('/email_drip','TemplateController@email_drip')->name('email_drip');

#--- AJAX HERE ---#
Route::post('/save_email_html','AjaxController@save_email_html')->name('save_email_html');

Route::post('/upload_image' ,'AjaxController@upload_image')->name('upload_image');

Route::post('/delete_email_template','AjaxController@delete_email_template')->name('delete_email_template');

Route::get('/update_email_template','AjaxController@update_email_template')->name('update_email_template');

Route::post('/get_single_email_templates','AjaxController@get_single_email_templates')->name('get_single_email_templates');

Route::post('/view_contacts','AjaxController@view_contacts')->name('view_contacts');

Route::post('/delete_contact','AjaxController@delete_contact')->name('delete_contact');

Route::post('/get_template_detail','AjaxController@get_template_detail')->name('get_template_detail');

Route::post('/delete_template','AjaxController@delete_template')->name('delete_template');

Route::post('/subscribe_unsubscribe','AjaxController@subscribe_unsubscribe')->name('subscribe_unsubscribe');

Route::post('/capture_url','AjaxController@capture_url')->name('capture_url');

Route::post('/append_video','AjaxController@append_video')->name('append_video');

Route::post('/append_url','AjaxController@append_url')->name('append_url');

Route::post('/append_photo','AjaxController@append_photo')->name('append_photo');

Route::post('/append_snapshot','AjaxController@append_snapshot')->name('append_snapshot');

Route::post('/upload_csv','AjaxController@upload_csv')->name('upload_csv');

Route::post('/upload_files','AjaxController@upload_files')->name('upload_files');

Route::post('/upload_capture_url','AjaxController@upload_capture_url')->name('upload_capture_url');

Route::post('/update_video_view','AjaxController@update_video_view')->name('update_video_view');

Route::post('/delete_suppression','AjaxController@delete_suppression')->name('delete_suppression');

Route::post('/upload_blob','AjaxController@upload_blob')->name('upload_blob');

Route::post('/upload_blob_video','AjaxController@upload_blob_video')->name('upload_blob_video');

Route::post('/move_contact','AjaxController@move_contact')->name('move_contact');

Route::post('/change_group','AjaxController@change_group')->name('change_group');

Route::post('/send_message_pop','AjaxController@send_message_pop')->name('send_message_pop');

Route::post('/delete_camp','AjaxController@delete_camp')->name('delete_camp');

Route::post('/delete_message','AjaxController@delete_message')->name('delete_message');

Route::post('preview_with_audio','AjaxController@preview_with_audio')->name('preview_with_audio');

Route::post('update_video_play_timer','AjaxController@update_video_play_timer')->name('update_video_play_timer');

#--- User Side ---#
Route::post('/genrate_video','VideoController@genrate_video')->name('genrate_video');

Route::get('/open_video','VideoController@open_video')->name('open_video');

Route::get('/open_automation_video','VideoController@open_automation_video')->name('open_automation_video');
#--- testing ---#
Route::get('/test','TemplateController@test')->name('test');


// Automation routes 
Route::post('/create_automation_email','AutomationEmailController@create_automation_email')->name('create_automation_email');

Route::post('/create_automation_groups','AutomationEmailController@create_automation_groups')->name('create_automation_groups');

Route::get('/edit_email_drip' ,'AutomationEmailController@edit_email_drip')->name('edit_email_drip');

Route::post('/start_automation' ,'AutomationEmailController@start_automation')->name('start_automation');

Route::post('/re_save_automation' ,'AutomationEmailController@re_save_automation')->name('re_save_automation');

Route::post('/save_and_start' ,'AutomationEmailController@save_and_start')->name('save_and_start');

Route::post('/pause_automation' ,'AutomationEmailController@pause_automation')->name('pause_automation');

Route::post('/delete_whole_automation' ,'AutomationEmailController@delete_whole_automation')->name('delete_whole_automation');

// cron routes 
Route::get('/create_automation_video','CronController@create_automation_video')->name('create_automation_video');
Route::get('/test_automation_video','CronController@test_automation_video')->name('test_automation_video');
Route::get('/send_automation_email','CronController@send_automation_email')->name('send_automation_email');


//nyals routes
Route::group(['middleware' => ['auth']], function() {
    Route::get('/verify_email' ,'AjaxController@nylas_verify_email')->name('verify_email');
});

Route::post('/oauth_authorize' ,'AjaxController@oauth_authorize')->name('oauth_authorize');

Route::get('/oauth_token','AjaxController@oauth_token')->name('oauth_token');

Route::post('/revoke_session','AjaxController@revoke_session')->name('revoke_session');

Route::post('/set_default','AjaxController@set_default')->name('set_default');

//Route::post('/send_message','AjaxController@send_message')->name('send_message');