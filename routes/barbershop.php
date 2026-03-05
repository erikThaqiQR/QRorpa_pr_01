<?php

//Barbershops Superadmin

Route::get('barbershop', 'BarberShopController@index')->name('barbershops.index');
Route::get('barbershopBarbershops', 'BarberShopController@indexBarbershops')->name('barbershops.indexBarbershops');
Route::get('barbershopBarbershopsOne', 'BarberShopController@indexBarbershopsOne')->name('barbershops.indexBarbershopsOne');

Route::get('barbershopServicesIndex', 'BarberShopController@servicesIndex')->name('barbershops.servicesIndex');
Route::get('barbershopServicesSelBar', 'BarberShopController@servicesSelBar')->name('barbershops.servicesSelBar');
Route::get('barbershopServicesCategory', 'BarberShopController@servicesCategory')->name('barbershops.servicesCategory');
Route::get('barbershopServicesType', 'BarberShopController@servicesType')->name('barbershops.servicesType');
Route::get('barbershopServicesExtra', 'BarberShopController@servicesExtra')->name('barbershops.servicesExtra');
Route::get('barbershopServicesService', 'BarberShopController@servicesService')->name('barbershops.servicesService');
Route::get('barbershopbannerSAPage', 'BarberShopController@indexBannerSA')->name('barbershops.bannerSAPage');

Route::post('barbershopFetchTheWorkers', 'BarberShopController@fetchtheWorkers')->name('barbershops.fetchtheWorkers');
Route::post('barbershopFetchTheWorkerTermins', 'BarberShopController@fetchtheWorkerTermins')->name('barbershops.fetchtheWorkerTermins');
Route::post('barbershopWorkerTerminCheckValidity', 'BarberShopController@workerTerminsChValidity')->name('barbershops.workerTerminsChValidity');


    // Barbershop superadmin
Route::post('barbershopSetBarLogo', 'BarberShopController@setBarLogo')->name('barbershops.setBarLogo');

Route::post('barbershopSetWorkingH', 'BarberShopController@setWorkingHAll')->name('barbershops.setWorkingH');

Route::post('barbershopSetBarMap', 'BarberShopController@setBarMap')->name('barbershops.setBarMap');

Route::post('barbershopSetBarDesc', 'BarberShopController@setBarDesc')->name('barbershops.setBarDesc');

Route::post('barbershopUpdateBarAddress', 'BarberShopController@updateBarAddress')->name('barbershops.updateBarAddress');
Route::post('barbershopBarRemoveGoogleMap', 'BarberShopController@BarRemoveGoogleMap')->name('barbershops.BarRemoveGoogleMap');


Route::post('addBarbershop', 'BarberShopController@store')->name('barbershops.addBarbershop');

Route::post('BarCategoryStore', 'BarbershopCategoryController@store')->name('barCategory.BCStore');
Route::post('BarCategoryDelete', 'BarbershopCategoryController@destroy')->name('barCategory.BCDelete');
Route::post('BarCategoryUpdate', 'BarbershopCategoryController@update')->name('barCategory.BCUpdate');

Route::post('BarTypeStore', 'BarbershopTypeController@store')->name('barType.BTStore');
Route::post('BarTypeDelete', 'BarbershopTypeController@destroy')->name('barType.BTDelete');
Route::post('BarTypeUpdate', 'BarbershopTypeController@update')->name('barType.BTUpdate');

Route::post('BarExtraStore', 'BarbershopExtraController@store')->name('barExtra.BEStore');
Route::post('BarExtraUpdate', 'BarbershopExtraController@update')->name('barExtra.BEUpdate');
Route::post('BarExtraDelete', 'BarbershopExtraController@destroy')->name('barExtra.BEDelete');

// Service
Route::post('BarServiceStore', 'BarbershopServiceController@store')->name('barService.BSStore');
Route::post('BarServiceEdit', 'BarbershopServiceController@edit')->name('barService.BSEdit');
Route::post('BarServiceConfNumberBar', 'BarbershopServiceController@confNrBar')->name('barService.confNumberBar');
Route::post('BarServiceConfCodeBar', 'BarbershopServiceController@confCodeBar')->name('barService.confCodeBar');

    // accept,decline Services
Route::post('BarServiceDeclineBarSerRec', 'BarbershopServiceController@declineBarSerRec')->name('barService.declineBarSerRec');
Route::post('BarServiceAcceptBarSerRec', 'BarbershopServiceController@acceptBarSerRec')->name('barService.acceptBarSerRec');
    // accept,decline Services by EMAIL
Route::get('BarServiceDeclineBarSerRecEmail', 'BarbershopServiceController@declineBarSerRecEmail')->name('barService.declineBarSerRecEmail');
Route::get('BarServiceAcceptBarSerRecEmail', 'BarbershopServiceController@acceptBarSerRecEmail')->name('barService.acceptBarSerRecEmail');
Route::get('BarServiceBarSerRecEmailInfoPage', 'BarbershopServiceController@BarSerRecEmailInfoPage')->name('barService.barSerRecEmailInfoPage');

    // Search function on services
Route::post('BarServiceSearchBarServices', 'BarbershopServiceController@searchBarServices')->name('barService.searchBarServices');

    // Recomendet Service
Route::post('BarServiceRecomendetSerStores', 'BarbershopServiceController@recomenderSerStore')->name('barService.recomenderSerStore');
Route::post('BarServiceRecomendetSerUpdate', 'BarbershopServiceController@recomenderSerUpdate')->name('barService.recomenderSerUpdate');
Route::post('BarServiceRecomendetGetSerPrice', 'BarbershopServiceController@recomenderGetSerPrice')->name('barService.recomenderGetSerPrice');
Route::post('BarServiceRecomendetGetSerPrice2', 'BarbershopServiceController@recomenderGetSerPrice2')->name('barService.recomenderGetSerPrice2');
Route::post('BarServiceRecomendetUpOne', 'BarbershopServiceController@recomenderUpOne')->name('barService.recomenderUpOne');
Route::post('BarServiceRecomendetDownOne', 'BarbershopServiceController@recomenderDownOne')->name('barService.recomenderDownOne');
Route::post('BarServiceRecomendetDelete', 'BarbershopServiceController@recomendetDelete')->name('barService.recomendetDelete');
//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

// Barbershop Users SA
Route::get('barUsers', 'BarberShopController@indexUser')->name('barbershopsUser.index');
Route::post('barUsersChRole', 'BarberShopController@changeRoleUser')->name('barbershopsUser.chRole');

Route::post('barUserstoRes', 'BarberShopController@UserToRes')->name('barbershopsUser.toRes');
Route::post('barUserstoBar', 'BarberShopController@UserToBar')->name('barbershopsUser.toBar');

//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------


// Barbershop Admin 
Route::get('barAdminStat', 'BarbershopAdminController@indexStatistics')->name('barAdmin.indexStatistics');
Route::get('dashboardBar', 'BarbershopAdminController@indexReservierung')->name('barAdmin.indexReservierung');
Route::get('barAdminWorker', 'BarbershopAdminController@indexWorker')->name('barAdmin.indexWorker');
Route::get('barAdmIndexAllConfirmedRez', 'BarbershopAdminController@indexAllConfirmedRez')->name('barAdmin.indexAllConfirmedRez');
Route::get('barAdmRecomendetSer', 'BarbershopAdminController@indexRecomendetSer')->name('barAdmin.indexRecomendetSer');
Route::get('barAdmCuponsMng', 'BarbershopAdminController@indexCuponMng')->name('barAdmin.indexCuponMng');
Route::get('barAdmAddRezervationAdminPage', 'BarbershopAdminController@addReservationAdminPage')->name('barAdmin.addReservationAdminPage');

    // Admin register a reservation "BARBERSHOP"
Route::post('barAdmSetRezFetchWorkers', 'BarbershopAdminController@setRezFetchWorkers')->name('barAdmin.setRezFetchWorkers');
Route::post('barAdmSetRezFetchWorkerTers', 'BarbershopAdminController@setRezFetchWorkerTers')->name('barAdmin.setRezFetchWorkerTers');
Route::post('barAdmSetRezsaveTheRezervation', 'BarbershopAdminController@setRezSaveTheRezervation')->name('barAdmin.setRezSaveTheRezervation');

Route::get('barAdmShowReservationsByMonth', 'BarbershopAdminController@showReservationsByMonth')->name('barAdmin.showReservationsByMonth');
Route::get('barAdmGenerateBarSerOrderReceipt', 'BarbershopAdminController@generateBarSerOrderReceipt')->name('barAdmin.generateBarSerOrderReceipt');




Route::post('barAdminAddWorker', 'BarbershopAdminController@addWorker')->name('barAdmin.addWorker');
Route::post('barAdminGetWorkerDayTermins', 'BarbershopAdminController@getWorkerDayTermins')->name('barAdmin.getWorkerDayTermins');
Route::post('barAdminDeleteWorker', 'BarbershopAdminController@deleteWorker')->name('barAdmin.deleteWorker');
Route::post('barAdminDeleteWorkerTermin', 'BarbershopAdminController@deleteWorkerTermin')->name('barAdmin.deleteWorkerTermin');

Route::post('barAdminWorkerCategorySetDel', 'BarbershopAdminController@workerCategorySetDel')->name('barAdmin.workerCategorySetDel');

Route::post('barAdminWorkerDayChngStatus', 'BarbershopAdminController@workerDayChngStatus')->name('barAdmin.workerDayChngStatus');

//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------


// Barbershop Menu

Route::post('TerminStore', 'CartController@storeBarbershop')->name('cart.storeBar');
Route::post('TerminDelete', 'CartController@destroyBarbershop')->name('cart.deleteBar');

Route::post('TabOrderStatusChange', 'CartController@chTabOrderStatus')->name('cart.chStatTabOrder');
Route::post('TabOrderStatusChangeDeConfirm', 'CartController@chTabOrderStatusDeConfirm')->name('cart.chStatTabOrderDeConfirm');

Route::post('TabOrderStatusChangeDelete', 'CartController@chTabOrderStatusDelete')->name('cart.chStatTabOrderDelete');
Route::post('barDestroyTheCart', 'CartController@emptyTheCart')->name('cart.barDestroyTheCart');

Route::post('checkProdsReadyFromCook', 'CartController@checkProdsReadyFromCook')->name('cart.checkProdsReadyFromCook');
//--------------------------------------------------------------------------------------------------------------------

// Barbershop banner
Route::post('BarbershopBannerStore', 'RestaurantCoversController@storeBarbershop')->name('restaurantCovers.storeBarbershop');
Route::post('BarbershopBannerEdit', 'RestaurantCoversController@editBarbershop')->name('restaurantCovers.editBarbershop');
Route::post('BarbershopBannerDelete', 'RestaurantCoversController@deleteBarbershop')->name('restaurantCovers.deleteBarbershop');
//--------------------------------------------------------------------------------------------------------------------

// Barbershop Ratings 
Route::get('barbershopRatingsSA', 'RestaurantRatingsController@BarbershopRatingSA')->name('restaurantRatings.RatingSA');
Route::post('barbershopRatingsStore', 'RestaurantRatingsController@storeBarbershopR')->name('restaurantRatings.storeBarbershopR');
Route::post('barbershopRatingsVerify', 'RestaurantRatingsController@verifyBarbershopR')->name('restaurantRatings.verifyBarbershopR');
Route::post('barbershopRatingsDelete', 'RestaurantRatingsController@deleteBarbershopR')->name('restaurantRatings.deleteBarbershopR');
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------



// Coupons 

Route::post('cuponsSaveBarCupon', 'CuponController@storeBar')->name('cupons.saveBarCupon');
Route::post('cuponsChCuponSBar', 'CuponController@chCuponStatusBar')->name('cupons.chCuponSBar');
Route::post('cuponsDeleteCuponBar', 'CuponController@deleteCuponBar')->name('cupons.deleteCuponBar');
Route::post('cuponsEditCuponBar', 'CuponController@editCuponBar')->name('cupons.editCuponBar');
Route::post('cuponsCheckCuponBar', 'CuponController@checkCuponBar')->name('cupons.checkCuponBar');


//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
