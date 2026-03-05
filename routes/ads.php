<?php
// AD-s
Route::get('adsModuleSaIndex', 'adsController@index')->name('adsModuleSa.index');
Route::post('adsModuleSaStoreProduct', 'adsController@storeProduct')->name('adsModuleSa.storeProduct');
Route::post('adsModuleSaStoreLink', 'adsController@storeLink')->name('adsModuleSa.storeLink');
Route::post('adsModuleSaStoreInfo', 'adsController@storeInfo')->name('adsModuleSa.storeInfo');
Route::post('adsModuleSaStoreCategory', 'adsController@storeCategory')->name('adsModuleSa.storeCategory');

Route::post('adsModuleSaAdToResAdd', 'adsController@adToResAdd')->name('adsModuleSa.adToResAdd');
Route::post('adsModuleSaAdToResRemove', 'adsController@adToResRemove')->name('adsModuleSa.adToResRemove');

Route::post('adsModuleSaAdDestroy', 'adsController@adDestroy')->name('adsModuleSa.adDestroy');

// get ad
Route::post('adsModuleGetAdsForMenu', 'adsController@getAdsForMenu')->name('adsModuleSa.getAdsForMenu');
Route::post('adsModuleGetAdsForMenuRepeatable', 'adsController@getAdsForMenuRepeatable')->name('adsModuleSa.getAdsForMenuRepeatable');

// get ad DELIVERY
Route::post('adsModuleGetAdsForMenuDelivery', 'adsController@getAdsForMenuDelivery')->name('adsModuleSa.getAdsForMenuDelivery');
Route::post('adsModuleGetAdsForMenuDeliveryRepeatable', 'adsController@getAdsForMenuDeliveryRepeatable')->name('adsModuleSa.getAdsForMenuDeliveryRepeatable');

// get ad TAKEAWAY
Route::post('adsModuleGetAdsForMenuTakeaway', 'adsController@getAdsForMenuTakeaway')->name('adsModuleSa.getAdsForMenuTakeaway');
Route::post('adsModuleGetAdsForMenuTakeawayRepeatable', 'adsController@getAdsForMenuTakeawayRepeatable')->name('adsModuleSa.getAdsForMenuTakeawayRepeatable');

Route::post('adsModuleCheckIfResHasRepeat', 'adsController@checkIfResHasRepeat')->name('adsModuleSa.checkIfResHasRepeat');


Route::post('adsModuleAdToAllResAdd', 'adsController@adToAllResAdd')->name('adsModuleSa.adToAllResAdd');
Route::post('adsModuleAdToAllResRemove', 'adsController@adToAllResRemove')->name('adsModuleSa.adToAllResRemove');

Route::post('adsModuleChangeRepeatableStat', 'adsController@changeRepeatableStat')->name('adsModuleSa.changeRepeatableStat');

Route::post('adsModuleSaveTheadRepeatRestaurants', 'adsController@saveTheadRepeatRestaurants')->name('adsModuleSa.saveTheadRepeatRestaurants');
Route::post('adsModuleDeleteTheadRepeatRestaurants', 'adsController@deleteTheadRepeatRestaurants')->name('adsModuleSa.deleteTheadRepeatRestaurants');

Route::post('adsModuleSaveResGroup', 'adsController@saveResGroup')->name('adsModuleSa.saveResGroup');
Route::post('adsModuleDeleteResGroup', 'adsController@deleteResGroup')->name('adsModuleSa.deleteResGroup');
Route::post('adsModuleResGroupToAdSave', 'adsController@resGroupToAdSave')->name('adsModuleSa.resGroupToAdSave');
Route::post('adsModuleUnsubAdNgaGrupi', 'adsController@unsubAdNgaGrupi')->name('adsModuleSa.unsubAdNgaGrupi');



Route::post('adsModuleChangeTheAdAvailability', 'adsController@changeTheAdAvailability')->name('adsModuleSa.changeTheAdAvailability');

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
?>