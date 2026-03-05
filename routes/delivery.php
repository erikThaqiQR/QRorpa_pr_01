<?php

// Delivery
Route::get('DeliveryAP', 'DeliveryController@index')->name('delivery.index');
Route::get('Delivery', 'DeliveryController@indexClient')->name('delivery.indexClient');

Route::post('DeliveryStoreNew', 'DeliveryController@store')->name('delivery.store');
Route::post('DeliveryAddOne', 'DeliveryController@addOne')->name('delivery.addOne');
Route::post('DeliveryAll', 'DeliveryController@addAll')->name('delivery.addAll');
Route::post('DeliveryaddToRec', 'DeliveryController@addToRec')->name('delivery.addToRec');
Route::post('DeliveryRemoveOne', 'DeliveryController@destroy')->name('delivery.destroy');
Route::post('DeliveryRemoveAll', 'DeliveryController@destroyAll')->name('delivery.destroyAll');
Route::post('DeliveryscheduleSet', 'DeliveryController@scheduleSet')->name('delivery.scheduleSet');
Route::post('DeliveryUpdateTA', 'DeliveryController@update')->name('delivery.edit');

Route::post('DeliveryAddPlz', 'DeliveryController@addPlzDel')->name('delivery.addPlzDel');
Route::post('DeliveryDeletePlz', 'DeliveryController@deletePlzDel')->name('delivery.deletePlzDel');
Route::post('DeliveryChngAcPlz', 'DeliveryController@chngAcPlzDel')->name('delivery.chngAcPlzDel');
Route::post('DeliveryAllTimePlzDel', 'DeliveryController@allTimePlzDel')->name('delivery.allTimePlzDel');

Route::post('DeliveryremoveRec', 'DeliveryController@removeRec')->name('delivery.removeRec');
Route::post('DeliveryPlusOneRec', 'DeliveryController@plusOneRec')->name('delivery.plusOneRec');
Route::post('DeliveryMinusOneRec', 'DeliveryController@minusOneRec')->name('delivery.minusOneRec');


Route::post('DeliveryChangeCategoryOrder', 'DeliveryController@changeCategoryOrder')->name('delivery.changeCategoryOrder');
Route::post('DeliveryChangeProductOrder', 'DeliveryController@changeProductOrder')->name('delivery.changeProductOrder');

Route::get('DeliveryOpenSortingTel', 'DeliveryController@openSortingTel')->name('delivery.openSortingTel');


Route::post('DeliverySendPhoneNrTACashPay', 'DeliveryController@sendPhoneNrTACashPay')->name('delivery.sendPhoneNrTACashPay');
Route::post('DeliveryCashPayOrders', 'DeliveryController@closeTheOrderCash')->name('delivery.closeTheOrderCash');

Route::post('DeliveryCashPayTAUseUserPNumber', 'DeliveryController@cashPayTAUseUserPNumber')->name('delivery.cashPayTAUseUserPNumber');

Route::post('DeliveryCheckPLZOnCartFromUser', 'DeliveryController@deliveryCheckPLZOnCartFromUser')->name('delivery.deliveryCheckPLZOnCartFromUser');

Route::post('DeliveryAddPlzChargePerPriceRange', 'DeliveryController@addPlzChargePerPriceRange')->name('delivery.addPlzChargePerPriceRange');

// ---------------------------------------------------------------------------------------------------------------------------------------------------------------------
