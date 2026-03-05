<?php
// Profile
Route::get('/Profile', 'ProfileController@index')->name('profile.index');
Route::post('/ProfilePic', 'ProfileController@setProfilePic')->name('profile.profilePic');

Route::post('/ProfileEmailSendConfCode', 'ProfileController@sendConfCodeEmail')->name('profile.sendConfCodeEmail');
Route::post('/ProfileEmailSave', 'ProfileController@saveNewEmail')->name('profile.saveNewEmail');

Route::post('/ProfilePhoneNrSendConfCode', 'ProfileController@sendConfCodePhoneNr')->name('profile.sendConfCodePhoneNr');
Route::post('/ProfilePhoneNrSave', 'ProfileController@saveNewPhoneNr')->name('profile.saveNewPhoneNr');

Route::post('/ProfilechangePassword', 'ProfileController@changePassword')->name('profile.changePassword');
//--------------------------------------------------------------------------------------------------------------------
?>