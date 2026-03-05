<?php
// Takeaway 
Route::get('Takeaway', 'TakeawayController@index')->name('takeaway.index');
Route::post('TakeawayAddOne', 'TakeawayController@addOne')->name('takeaway.addOne');
Route::post('TakeawayAll', 'TakeawayController@addAll')->name('takeaway.addAll');
Route::post('TakeawayRemoveOne', 'TakeawayController@destroy')->name('takeaway.destroy');
Route::post('TakeawayRemoveAll', 'TakeawayController@destroyAll')->name('takeaway.destroyAll');
Route::post('TakeawayUpdateTA', 'TakeawayController@update')->name('takeaway.edit');
Route::post('TakeawayaddToRec', 'TakeawayController@addToRec')->name('takeaway.addToRec');
Route::post('TakeawayremoveRec', 'TakeawayController@removeRec')->name('takeaway.removeRec');
Route::post('TakeawayMinusOneRec', 'TakeawayController@minusOneRec')->name('takeaway.minusOneRec');
Route::post('TakeawayPlusOneRec', 'TakeawayController@plusOneRec')->name('takeaway.plusOneRec');
Route::post('TakeawayStoreNew', 'TakeawayController@store')->name('takeaway.store');
Route::post('TakeawayscheduleSet', 'TakeawayController@scheduleSet')->name('takeaway.scheduleSet');

Route::post('TakeawayChangeCategoryOrder', 'TakeawayController@changeCategoryOrder')->name('takeaway.changeCategoryOrder');
Route::post('TakeawayChangeProductOrder', 'TakeawayController@changeProductOrder')->name('takeaway.changeProductOrder');

Route::get('TakeawayOpenSortingTel', 'TakeawayController@openSortingTel')->name('takeaway.openSortingTel');

Route::post('checkTakeawayOrderCodeValidation', 'TakeawayController@checkTakeawayOrderCodeValidation')->name('takeaway.checkTakeawayOrderCodeValidation');
Route::post('checkTakeawayOrderCodeValidation2', 'TakeawayController@checkTakeawayOrderCodeValidation2')->name('takeaway.checkTakeawayOrderCodeValidation2');

Route::post('takeawayCashPayOrdersPhNr', 'TakeawayController@sendPhoneNrTACashPay')->name('takeaway.sendPhoneNrTACashPay');
Route::post('takeawayCashPayOrders', 'TakeawayController@closeTheOrder')->name('takeaway.closeTheOrder');


// pay using Auth::user() phone number 
Route::post('takeawayCashPayTAUseUserPNumber', 'TakeawayController@cashPayTAUseUserPNumber')->name('takeaway.cashPayTAUseUserPNumber');


//--------------------------------------------------------------------------------------------------------------------
