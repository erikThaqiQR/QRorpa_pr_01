<?php
Route::get('AdminSaMSG', 'admToSaMsgController@indexAdminPanel')->name('atsMsg.openPageAdminPanel');
Route::post('AdminSaMsgSaveNewCard', 'admToSaMsgController@ASASaveNewCard')->name('atsMsg.ASASaveNewCard');
Route::post('AdminSaMsgDeleteCard', 'admToSaMsgController@ASADeleteCard')->name('atsMsg.ASADeleteCard');
Route::post('AdminSaMsgSaveNewMesageOnCard', 'admToSaMsgController@ASASaveNewMessageOnCard')->name('atsMsg.ASASaveNewMessageOnCard');
Route::post('AdminSaMsgSaveNewMesageOnCard2', 'admToSaMsgController@ASASaveNewMessageOnCard2')->name('atsMsg.ASASaveNewMessageOnCard2');
Route::post('AdmIReadTheMsg', 'admToSaMsgController@AdmIReadTheMsg')->name('atsMsg.AdmIReadTheMsg');

Route::get('SaAdminMSG', 'admToSaMsgController@indexSuperadminPanel')->name('atsMsg.openPageSuperadminPanel');
Route::post('SaAdminMsgSaveNewCard', 'admToSaMsgController@SAASaveNewCard')->name('atsMsg.SAASaveNewCard');
Route::post('SaAdminMsgSaveNewMesageOnCard', 'admToSaMsgController@SAASaveNewMessageOnCard')->name('atsMsg.SAASaveNewMessageOnCard');
Route::post('SaAdminMsgSaveNewMesageOnCard2', 'admToSaMsgController@SAASaveNewMessageOnCard2')->name('atsMsg.SAASaveNewMessageOnCard2');
Route::post('SAIReadTheMsg', 'admToSaMsgController@SAIReadTheMsg')->name('atsMsg.SAIReadTheMsg');

?>