<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'RestorantController@indexPP')->name('menu');

Route::post('DeleteTheCart', 'RestorantController@DeleteCartForMe')->name('Res.DeleteTheCart');
Route::post('SetResTypeResCon', 'RestorantController@setResType')->name('Res.setResType');
Route::post('SetSerPayAm', 'RestorantController@setSerPayAm')->name('Res.setSerPayAm');

Route::post('SetNewTimeForTheRes', 'RestorantController@setNewTimeForTheRes')->name('Res.setNewTimeForTheRes');

Route::get('InvalidData', 'RestorantController@openResTNotFound')->name('Res.resTNotFoundPage');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::post('browserCheckI', 'HomeController@browserCheckIncognito')->name('browser.browserCheckIncognito');
Route::post('browserCheckI2', 'HomeController@browserCheckIncognitoIsNot')->name('browser.browserCheckIncognitoIsNot');
Route::get('setNewTvshValuesQrorpa', 'HomeController@setNewTvshValues')->name('home.setNewTvshValues');

Route::get('/scan', 'HomeController@scanQRCodePageOpen')->name('openScanQrCodePage');

Route::get('authFB/facebook', 'FBController@redirectToFacebook');
Route::get('authFB/facebook/callback', 'FBController@handleFacebookCallback');

Route::get('auth/{provider}', 'Auth\LoginController@redirectToProvider');
Route::get('auth/{provider}/callback', 'Auth\LoginController@handleProviderCallback');

Route::get('/SuperAdminContent', 'HomeController@indexConRegUser')->name('homeConRegUser');
Route::get('/SuperAdminContentBox', 'HomeController@indexConRegUserBox')->name('homeConRegUserBox');
Route::get('/SuperAdminContentCat', 'HomeController@indexConRegUserCat')->name('homeConRegUserCat');
Route::get('/SuperAdminContentType', 'HomeController@indexConRegUserType')->name('homeConRegUserType');
Route::get('/SuperAdminContentExtra', 'HomeController@indexConRegUserExtra')->name('homeConRegUserExtra');
Route::get('/SuperAdminContentProduct', 'HomeController@indexConRegUserProduct')->name('homeConRegUserProduct');
Route::get('/SuperAdminContentTable', 'HomeController@indexConRegUserTable')->name('homeConRegUserTable');

Route::get('/SuperAdminRestorantOne', 'HomeController@saRestorantOne')->name('restorantOneSA');

Route::get('auth/{provider}', 'Auth\LoginController@redirectToProvider');
Route::get('auth/{provider}/callback', 'Auth\LoginController@handleProviderCallback');

Route::post('checkForInvalideOrRes', 'HomeController@chkInvalideOrdersRes')->name('home.chkInvalideOrdersRes');

Route::get('refIdFillInScr266', 'HomeController@refIdFillInScr266')->name('home.refIdFillInScr266');

// Res xx xx xx xx firstPage
Route::get('/sportrestaurant-obere-au-abholstationen', 'HomeController@rex31323334FirstPage')->name('home.res31323334FP');
// ------------------------------------------

// Password resset functions 
Route::post('PassResCheckEmail', 'PassResetController@checkEmail')->name('pasreset.chEmail');
Route::post('PassResChngPassF', 'PassResetController@chEmailPass')->name('pasreset.chEmailPass');
Route::get('PassResConfEmail', 'PassResetController@confEmail')->name('pasreset.confEmail');
// -----------------------------------------------------------------------------------------------------------------------------------------------------------------------------

Route::get('/produktet1', 'ProduktController@manageIndex')->name('manageProduktet.index');

Route::get('/Kategorite', 'KategoriController@index')->name('kategorite.index');
Route::post('/Kategorite1', 'KategoriController@store')->name('kategorite.store');
Route::post('/KategoriteAdminP', 'KategoriController@storeAdminP')->name('kategorite.storeAdminP');
Route::delete('/Kategorite2{kat}', 'KategoriController@destroy')->name('kategorite.destroy');
Route::post('/KategoriteDestroyAdminP', 'KategoriController@destroyAdminP')->name('kategorite.destroyAdminP');
Route::post('/Kategorite1{kat}', 'KategoriController@update')->name('kategorite.update');
Route::post('/KategoriteUpdateAdminP{kat}', 'KategoriController@updateAdminP')->name('kategorite.updateAdminP');
Route::post('/KategoriteAVisit', 'KategoriController@addVisit')->name('kategorite.addVisit');


Route::get('/Ekstras', 'EkstraController@index')->name('ekstras.index');
Route::post('/Ekstras1', 'EkstraController@store')->name('ekstras.store');
Route::post('/EkstrasAdminP', 'EkstraController@storeAdminP')->name('ekstras.storeAdminP');
Route::post('/EkstrasAdminPPro', 'EkstraController@storeAdminPPro')->name('ekstras.storeAdminPPro');
Route::delete('/Ekstras/{ext}', 'EkstraController@destroy')->name('ekstras.destroy');
Route::post('/Ekstras/{ext}', 'EkstraController@update')->name('ekstras.update');
Route::post('/setAllExtToThisProdsOnCat', 'EkstraController@setAllExtToThisProdsOnCat')->name('ekstras.setAllExtToThisProdsOnCat');
Route::post('/setAllExtToAllProdsOnCat', 'EkstraController@setAllExtToAllProdsOnCat')->name('ekstras.setAllExtToAllProdsOnCat');

Route::get('/Llojet', 'LlojetProController@index')->name('llojetPro.index');
Route::post('/Llojet1', 'LlojetProController@store')->name('llojetPro.store');
Route::post('/LlojetAdminP', 'LlojetProController@storeAdminP')->name('llojetPro.storeAdminP');
Route::post('/LlojetAdminPPro', 'LlojetProController@storeAdminPPro')->name('llojetPro.storeAdminPPro');
Route::delete('/Llojet/{llpro}', 'LlojetProController@destroy')->name('llojetPro.destroy');
Route::post('/Llojet1/{llpro}', 'LlojetProController@update')->name('llojetPro.update');


Route::get('/produktet', 'ProduktController@index')->name('produktet.index');
Route::post('/produktet1', 'ProduktController@store')->name('produktet.store');
Route::post('/produktetAdminP', 'ProduktController@storeAdminP')->name('produktet.storeAdminP');
Route::post('/produktetEditAdminP', 'ProduktController@editAdminP')->name('produktet.editAdminP');
Route::post('/produktet2/{prod}', 'ProduktController@update')->name('produktet.update');
Route::delete('/produktet/{prod}', 'ProduktController@destroy')->name('produktet.destroy');
Route::post('/produktetDestroyAdminP', 'ProduktController@destroyAdminP')->name('produktet.destroyAdminP');

Route::post('produktetC', 'ProduktController@removeExtFromCart')->name('produktet.CartRe');
Route::post('produktetCDel', 'ProduktController@removeExtFromCartDel')->name('produktet.CartReDel');

Route::post('produktetnewClick', 'ProduktController@newClick')->name('produktet.newClick');

Route::post('produktetUsrPNrStartSession', 'ProduktController@usrPNrStartSession')->name('produktet.usrPNrStartSession');
Route::post('produktetLogoutPNrSessionRemove', 'ProduktController@logoutPNrSessionRemove')->name('produktet.logoutPNrSessionRemove');


Route::post('changeStatus', 'OrdersController@ChangeStatus')->name('order.chStatus');
Route::post('changeStatusAjax', 'OrdersController@ChangeStatusAjax')->name('order.chStatusAjax');
Route::post('changeStatusAjaxCancelOr', 'OrdersController@ChangeStatusAjaxCancelOr')->name('order.chStatusAjaxCancelOr');
Route::post('changeStatus2', 'OrdersController@ChangeStatus02')->name('order.chStatus2');
Route::post('cancelAOrder', 'OrdersController@cancelAOrder')->name('order.cancelAOrder');
Route::post('taOrChngStatus', 'OrdersController@taOrderChangeStatus')->name('order.taOrChngStatus');


Route::get('/order', 'CartController@index')->name('cart');
Route::post('/orderSP1', 'CartController@storeP1')->name('cart.storeP1');
Route::post('/orderSP2', 'CartController@storeP2')->name('cart.storeP2');
Route::post('/orderS', 'CartController@store')->name('cart.store');
Route::post('/orderS2Plus', 'CartController@store2Plus')->name('cart.store2Plus');
Route::post('/orderST', 'CartController@storeTakeaway')->name('cart.storeTakeaway');
Route::post('/order', 'CartController@update')->name('cart.update');
Route::delete('/orderD', 'CartController@destroy')->name('cart.destroy');
Route::delete('/orderDT', 'CartController@destroyTakeaway')->name('cart.destroyTakeaway');
Route::post('/orderDOC', 'CartController@destroyOnlyCart')->name('cart.destroyOnlyCart');
Route::post('/order/switchToSaveForLater/{produkt}', 'CartController@switchToSaveForLater')->name('cart.switchToSaveForLater');
Route::post('cartReturnCartFromCookie', 'CartController@returnCartFromCookie')->name('cart.returnCartFromCookie');

Route::post('checkCartValidity', 'CartController@checkCartValidity')->name('cart.checkCartValidity');
Route::post('emptyTheCart', 'CartController@emptyTheCart')->name('cart.emptyTheCart');

// Return unpaid products
Route::post('returnUnpaid01', 'CartController@returnUnpaid01')->name('cart.returnUnpaid01');
Route::post('returnUnpaid02', 'CartController@returnUnpaid02')->name('cart.returnUnpaid02');





// Register admin products to client
Route::post('registerAdminToClUn', 'CartController@registerAdminToClUn')->name('cart.registerAdminToClUn');
Route::post('registerAdminToClUnFCode', 'CartController@registerAdminToClUnFCode')->name('cart.registerAdminToClUnFCode');



Route::get('/checkout', 'CheckoutController@index')->name('checkout');

Route::get('stripe', 'StripeController@index');
Route::post('storeOrder', 'StripeController@store');

Route::post('confirmNumber', 'ProduktController@confNr')->name('produktet.conf');
Route::post('confirmNumberDe', 'ProduktController@confNrDelivery')->name('produktet.confDel');
Route::post('confirmCode', 'ProduktController@confCode')->name('produktet.confCode');
Route::post('confirmCodeTA', 'ProduktController@confCodeTakeaway')->name('produktet.confCodeTakeaway');
Route::post('confirmCodeDe', 'ProduktController@confCodeDelivery')->name('produktet.confCodeDelivery');

Route::post('closeOrderByCardResClient', 'ProduktController@closeOrderByCardResClient')->name('produktet.closeOrderByCardResClient');


// Restorantet SuperAdminPanel
Route::get('Restorantet', 'RestorantController@index')->name('restorantet.index');
Route::post('Restorantet1', 'RestorantController@store')->name('restorantet.store');
Route::post('RestorantetPP', 'RestorantController@setProfilePic')->name('restorantet.profilePic');
Route::post('RestorantetBP', 'RestorantController@setBackgroundPic')->name('restorantet.backgroundPic');
Route::post('RestorantetRBP', 'RestorantController@removeBackgroundPic')->name('restorantet.RbackgroundPic');


// Working hours
Route::get('RestorantetWH', 'RestorantController@indexWH')->name('restorantet.workingHours');
Route::post('RestorantetOneDayWH', 'RestorantController@setOneDayWH')->name('restorantet.OneDayWH');
Route::post('RestorantetAllWH', 'RestorantController@setAllWH')->name('restorantet.allWH');
Route::post('RestorantetResMap', 'RestorantController@setResMap')->name('restorantet.resMap');
Route::post('RestorantetResDesc', 'RestorantController@setDesc')->name('restorantet.setDesc');

Route::post('RestorantetCashPayClick', 'RestorantController@cashPayClick')->name('restorantet.cashPayClick');
Route::post('RestorantetResOpenCount', 'RestorantController@ResOpenCount')->name('restorantet.ResOpenCount');
Route::post('RescheckGhostForTable', 'RestorantController@checkGhostForTable')->name('restorantet.checkGhostForTable');

// Restorant Content Manager 
Route::post('RestorantetResContentMng', 'RestorantController@ResContentMng')->name('restorantet.SetContentMng');
Route::post('RestorantetUsrRoleSet', 'RestorantController@CMUsrRoleSet')->name('restorantet.CMUsrRoleSet');


//--------------------------------------------------------------------------------------------------------------------

// orders SuperAdminPanel
Route::get('OrdersSa', 'OrdersController@SAindex')->name('ordersSa.index');
//--------------------------------------------------------------------------------------------------------------------

// QR-CODE SuperAdminPanel
Route::get('tables', 'QRCodeController@index')->name('table.index');
Route::post('tables1', 'QRCodeController@store')->name('table.store');
Route::post('tablesTA1', 'QRCodeController@storeTA')->name('table.storeTA');
Route::post('tables1Remove', 'QRCodeController@destroy')->name('table.destroy');
Route::get('tablesCapacity', 'QRCodeController@indexC')->name('table.capacity');
Route::post('tablesCapacitySave', 'QRCodeController@tableCSave')->name('table.capacitySave');
Route::post('tablesRezervationStatus', 'QRCodeController@tableRezStatus')->name('table.rezStatus');
Route::post('tablesStatusSetAP', 'QRCodeController@tableStatusSet')->name('table.tableStatusSet');

//--------------------------------------------------------------------------------------------------------------------

// orders SuperAdminPanel
Route::get('/produktet5', 'ProduktController@SAIndex')->name('SAProduktet.index');
Route::get('/produktet5Boxes', 'ProduktController@SAIndexBoxes')->name('SAProduktet.indexBoxes');
Route::get('/produktet5Category', 'ProduktController@SAIndexCategory')->name('SAProduktet.indexCategory');
Route::get('/produktet5Extra', 'ProduktController@SAIndexExtra')->name('SAProduktet.indexExtra');
Route::get('/produktet5Type', 'ProduktController@SAIndexType')->name('SAProduktet.indexType');
Route::get('/produktet5Product', 'ProduktController@SAIndexProduct')->name('SAProduktet.indexProduct');
//--------------------------------------------------------------------------------------------------------------------


//Ratings
Route::get('ratings', 'RatingController@index')->name('ratings.index');
Route::get('/Ratinratings', 'RatingController@indexRatings')->name('ratings');
Route::post('/RatingStore', 'RatingController@store')->name('ratings.store');
//--------------------------------------------------------------------------------------------------------------------

//RestaurantRatings
/*Route::get('ratings', 'RestaurantRatingsController@index')->name('restaurantRatings.index');
Route::get('/', 'RestaurantRatingsController@indexRatings')->name('restaurantRatings');
Route::post('/', 'RestaurantRatingsController@store')->name('restaurantRatings.store');*/

//Covid-19
Route::get('/covid19', 'CovidController@index')->name('covid');
//--------------------------------------------------------------------------------------------------------------------

Route::get('covids', 'CovidReportController@index')->name('covids.index');
Route::get('covidsAdmin', 'CovidReportController@indexAdmin')->name('covidsAdmin.indexAdmin');
Route::get('/', 'CovidReportController@indexCovids')->name('covids');
Route::post('/covid19', 'CovidReportController@store')->name('covids.store');


// Per demo 
Route::get('/addAllRestaurants', 'ResDemoController@index')->name('addAll.index');
Route::post('/addAllRestaurants1', 'ResDemoController@store')->name('addAll.store');
Route::delete('/addAllRestaurants2', 'ResDemoController@destroyAll')->name('addAll.destroyAll');
Route::get('/generatePDF2/{resId}','ResDemoController@generatePDF2')->name('pdfview');
Route::get('/generatePDF2','ResDemoController@generatePDF22')->name('pdfview2');
Route::get('/generatePDF2All','ResDemoController@generatePDF2All')->name('resDemo.allPdfRes');

Route::get('/generatePDFRestorantOrders','ResDemoController@generatePDFRestorantOrders')->name('resDemo.generatePDFRestorantOrders');

Route::delete('/addAllRestaurantsDelOne', 'ResDemoController@destroyOne')->name('addAll.destroyOne');
/*Route::post('/addAllRestaurants/{resId}', 'ResDemoController@changeStatus')->name('addAll.changeStatus');*/

Route::get('/generatePDF/{id}',array('as'=>'adminInvoice', 'uses'=>'AdminPanelController@generatePDF'));





// Demo restorantet / Faqja e marketingut
Route::get('IndexCRMQ', 'ResDemoController@indexCRM')->name('resDemo.indexCRM');
Route::get('IndexCRMQ2', 'ResDemoController@indexCRM2')->name('resDemo.indexCRM2');
Route::post('saveNewComm', 'ResDemoController@saveNewComCRM')->name('resDemo.saveNewComCRM');
Route::post('emailDateSetCRM', 'ResDemoController@emailDateSet')->name('resDemo.emailDateSet');
Route::post('nrTelDateSetCRM', 'ResDemoController@nrTelDateSet')->name('resDemo.nrTelDateSet');
Route::post('nrTelDateSetCRM2', 'ResDemoController@nrTelDateSet2')->name('resDemo.nrTelDateSet2');
Route::post('emailSaveNewCRM', 'ResDemoController@emailSaveNewCRM')->name('resDemo.emailSaveNewCRM');
Route::post('nrTelSaveNewCRM', 'ResDemoController@nrTelSaveNewCRM')->name('resDemo.nrTelSaveNewCRM');
Route::post('nrTelSaveNewCRM2', 'ResDemoController@nrTelSaveNewCRM2')->name('resDemo.nrTelSaveNewCRM2');
Route::post('sendWebDate', 'ResDemoController@sendWebDate')->name('resDemo.sendWebDate');
Route::post('webSaveNewCRM2', 'ResDemoController@webSaveNewCRM2')->name('resDemo.webSaveNewCRM2');
Route::post('ResDemoCHIsForCA', 'ResDemoController@changeIsForCA')->name('resDemo.chngIsForca');
Route::post('ResDemoCHClAc', 'ResDemoController@changeClAc')->name('resDemo.chngClAc');
Route::post('ResDemoNewBoosName', 'ResDemoController@saveNameBoos')->name('resDemo.saveNameBoos');
Route::post('ResDemoNewBoosSurname', 'ResDemoController@saveSurnameBoos')->name('resDemo.saveSurnameBoos');
//--------------------------------------------------------------------------------------------------------------------


// Track my order
Route::get('/trackMyOrder', 'TrackMyOrderController@index')->name('trackOrder.Home');
Route::post('/trackMyOrderSendReceiptToEmail', 'TrackMyOrderController@sendReceiptToEmail')->name('trackOrder.sendReceiptToEmail');
Route::post('trackMyOrderFetchOrder', 'TrackMyOrderController@getOrderByCode')->name('trackOrder.getOrderByCode');
//--------------------------------------------------------------------------------------------------------------------


// Search Funksion 
Route::post('/SearchFunc', 'SearchController@searchFrom')->name('search.from');
Route::post('/SearchFuncTA', 'SearchController@searchFromTA')->name('search.fromTA');
Route::post('/SearchFuncDE', 'SearchController@searchFromDE')->name('search.fromDE');
Route::post('/SearchFuncCRM', 'SearchController@searchFromCRM')->name('search.fromCRM');
Route::post('/SearchAdminAddOrder', 'SearchController@searchProductsAdminAddOrder')->name('search.searchProductsAdminAddOrder');
//--------------------------------------------------------------------------------------------------------------------



// Piket Page SuperAdmin  
Route::get('/Piket', 'PiketController@index')->name('piket.index');
Route::get('/PiketRes', 'PiketController@indexRes')->name('piket.indexRes');
Route::get('/PiketResOne', 'PiketController@indexResOne')->name('piket.indexResOne');
Route::get('/PiketResOneMY', 'PiketController@indexResOneMY')->name('piket.indexResOneMY');
Route::get('/PiketCli', 'PiketController@indexCli')->name('piket.indexCli');
Route::get('/PiketCliOne', 'PiketController@indexCliOne')->name('piket.indexCliOne');
//--------------------------------------------------------------------------------------------------------------------



//RestaurantRatings
Route::get('restaurantsRatings', 'RestaurantRatingsController@index')->name('restaurantsRatings.index');
Route::get('restaurantsRatings/{id}','RestaurantRatingsController@confirmRating');
Route::post('restaurantRatings', 'RestaurantRatingsController@store')->name('restaurantRatings.store');
//--------------------------------------------------------------------------------------------------------------------



//Send emails controller
Route::post('EmailConfig', 'SendEmailController@send')->name('email.sendConfig');
Route::post('sendMailCRM', 'SendEmailController@sendMailCRM')->name('email.sendMailCRM');
Route::post('sendMailTableRezNotification', 'SendEmailController@sendMailTableRezNotification')->name('email.sendMailTableRezNotification');
//--------------------------------------------------------------------------------------------------------------------


//Free Prods xxx+CHF  
Route::get('freeProducts', 'FreeProductController@index')->name('freeProd.index');
Route::post('freeProductsRP', 'FreeProductController@destroy')->name('freeProd.destroy');
Route::post('freeProductsAP', 'FreeProductController@store')->name('freeProd.store');
Route::post('freeProductsAPExtra', 'FreeProductController@storeExtra')->name('freeProd.storeExtra');
Route::post('freeProductsActiveFree', 'FreeProductController@activeFree')->name('freeProd.activeFree');



Route::post('freeProductsCPF', 'FreeProductController@changePriceFree')->name('freeProd.changePriceFree');
Route::post('freeProductsCTF', 'FreeProductController@changeTextFree')->name('freeProd.changeTextFree');
//--------------------------------------------------------------------------------------------------------------------

// Restricted products 
Route::get('restrictedProducts', 'RestrictProductsController@index')->name('restrictProd.index');
Route::post('restrictedProductsT16', 'RestrictProductsController@restrict16')->name('restrictProd.T16');
Route::post('restrictedProductsT18', 'RestrictProductsController@restrict18')->name('restrictProd.T18');
Route::post('restrictedProductsT0', 'RestrictProductsController@restrict0')->name('restrictProd.T0');
//--------------------------------------------------------------------------------------------------------------------

// Restorant Cover 
Route::get('restaurantCovers', 'RestaurantCoversController@index')->name('restaurantCovers.index');
Route::post('restaurantCovers', 'RestaurantCoversController@store')->name('restaurantCovers.store');
Route::post('restaurantCovers/{id}', 'RestaurantCoversController@update')->name('restaurantCovers.update');
Route::delete('restaurantCovers/{id}', 'RestaurantCoversController@destroy')->name('restaurantCovers.destroy');
Route::get('restaurantCovers/{id}','RestaurantCoversController@activateCover');
//--------------------------------------------------------------------------------------------------------------------

// Cupons
Route::get('cupons', 'CuponController@index')->name('cupons.index');
Route::post('cuponsSaveCupon', 'CuponController@store')->name('cupons.saveCupon');
Route::post('cuponsEditCupon', 'CuponController@update')->name('cupons.EditCupon');
Route::post('cuponsChCuponS', 'CuponController@chCuponStatus')->name('cupons.chCuponS');
Route::post('cuponsDeleteCupon', 'CuponController@destroy')->name('cupons.deleteCupon');
Route::post('cuponsChCupon', 'CuponController@checkCupon')->name('cupons.checkCupon');

Route::post('activateCouponForWheel', 'CuponController@activateCouponForWheel')->name('cupons.activateCouponForWheel');
Route::post('deActivateCouponForWheel', 'CuponController@deActivateCouponForWheel')->name('cupons.deAactivateCouponForWheel');
//--------------------------------------------------------------------------------------------------------------------


// Table Reservation 
Route::get('tabReserAdminIndex', 'TableReservationController@index')->name('TableReservation.ADIndex');
Route::get('tabReserAdminIndexRezList', 'TableReservationController@indexRezList')->name('TableReservation.ADIndexRezList');
Route::post('tabReserStore', 'TableReservationController@store')->name('TableReservation.store');
Route::post('tabReserchangeStatus', 'TableReservationController@chgStatus')->name('TableReservation.chgStatus');

Route::post('tabReserReqCancel', 'TableReservationController@RezReqCancel')->name('TableReservation.RezReqCancel');
Route::post('tabReserReqConfirm', 'TableReservationController@RezReqConfirm')->name('TableReservation.RezReqConfirm');

Route::get('tableRezProcesFromEmailA', 'TableReservationController@processAReservationFromEmailAdmin')->name('TableReservation.tableRezProcesFromEmailA');

Route::get('tableRezIndex', 'TableRezController@index')->name('TableRez.index');

Route::post('regEmForTabRezNotify', 'TableReservationController@regEmForTabRezNotify')->name('TableReservation.regEmForTabRezNotify');
Route::post('delEmForTabRezNotify', 'TableReservationController@delEmForTabRezNotify')->name('TableReservation.delEmForTabRezNotify');

Route::post('saveReservationFromStaf', 'TableReservationController@saveReservationFromStaf')->name('TableReservation.saveReservationFromStaf');
//--------------------------------------------------------------------------------------------------------------------



// Fisrt Page 
Route::get('firstPIndex', 'FirstPageController@index')->name('firstPage.index');
Route::get('firstPQRcodeScanner', 'FirstPageController@iQRcodeScanner')->name('firstPage.qrCodeScanner');
Route::get('firstPWieBenutztMan', 'FirstPageController@iWieBenutztMan')->name('firstPage.wieBenutztMan');
Route::get('firstPTischeReservieren', 'FirstPageController@itischeReservieren')->name('firstPage.tischeReservieren');
Route::get('firstPTidelivery', 'FirstPageController@idelivery')->name('firstPage.delivery');
Route::get('firstPTitakeaway', 'FirstPageController@itakeaway')->name('firstPage.takeaway');
Route::get('firstPTikartenzahlung', 'FirstPageController@ikartenzahlung')->name('firstPage.kartenzahlung');
Route::get('firstPTidatenschutz', 'FirstPageController@idatenschutz')->name('firstPage.datenschutz');

Route::post('firstPSendConFor', 'FirstPageController@SendConFor')->name('firstPage.SendConFor');

Route::get('atenschutzbestimmungen', 'FirstPageController@agbAndPrivtcy')->name('firstPage.agbdatenschutz');
Route::get('impressum', 'FirstPageController@impressum')->name('firstPage.impressum');
Route::get('agb-fuer-kunden', 'FirstPageController@agbFuerKunden')->name('firstPage.agbFuerKunden');
// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------


// Search Bar FP

Route::get('/searchRestaurants/', 'SearchRestaurantsController@search')->name('searchRestaurants');
Route::get('/searchRestaurantsFilter/', 'SearchRestaurantsController@searchFilter')->name('searchRestaurantsFilter');
Route::post('/', 'SearchRestaurantsController@fetch')->name('fetch');
// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------


// Service Request From clients
Route::get('ServiceReqAP', 'ServiceReqCliController@index')->name('SerReqCli.APindex');
Route::post('ServiceReqStore', 'ServiceReqCliController@store')->name('SerReqCli.store');
// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------


// Change tables Client 
    Route::get('ClTableChangeIndexAP', 'TableChangeCLController@indexAP')->name('TabChngCli.indexAP');
    Route::post('ClTableChangeStore', 'TableChangeCLController@store')->name('TabChngCli.store');
    Route::post('ClTableChangeStat01', 'TableChangeCLController@stat01')->name('TabChngCli.stat01');
    Route::post('sendAMSGToAUser', 'TableChangeCLController@MsgToUser')->name('TabChngCli.MsgToUser');
    Route::post('sendAMSGUserToAdminA', 'TableChangeCLController@MsgUserToAdmin')->name('TabChngCli.MsgUserToAdmin');
// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------


// Picture library 
    Route::get('PictureLibSAIndex', 'PicLibController@indexSA')->name('PicLibrary.indexSA');
    Route::post('PictureLibStore', 'PicLibController@store')->name('PicLibrary.store');
    Route::post('PictureLibDelete', 'PicLibController@destroy')->name('PicLibrary.destroy');
    Route::post('PictureLibSearch', 'PicLibController@searchPIcsLib')->name('PicLibrary.search');
// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------



// Notification Routes
    Route::post('notifyCheckUnrespondet', 'notificationController@checkUnrespondet')->name('notify.checkUnrespondet');
    Route::post('notifyCheckUnrespondetSA', 'notificationController@checkUnrespondetSA')->name('notify.checkUnrespondetSA');
    Route::post('notifyMarkRespondRead', 'notificationController@markRespondRead')->name('notify.markRespondRead');
    Route::post('notifyCheckUnrespondetClient', 'notificationController@checkUnrespondetClient')->name('notify.checkUnrespondetClient');
    Route::post('notifyCheckUnrespondetClientCart', 'notificationController@checkUnrespondetClientCart')->name('notify.checkUnrespondetClientCart');

    Route::post('notifyCheckUnrespondetWaiter', 'notificationController@checkUnrespondetWaiter')->name('notify.checkUnrespondetWaiter');
    Route::post('notifyMarkRespondReadWaiter', 'notificationController@markRespondReadWaiter')->name('notify.markRespondReadWaiter');

    Route::post('notifyCheckUnrespondetCook', 'notificationController@checkUnrespondetCook')->name('notify.checkUnrespondetCook');
// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------


// set cookie accept from client session
    Route::post('clAcceptsCookie', 'notificationController@clAcceptsCookie')->name('notify.clAcceptsCookie');
// ---------------------------------------------------------------------------------------------------------------


//Invoices
    Route::get('invoices', 'InvoiceController@SAindex')->name('invoices.SAindex');
    Route::post('invoices/getdata', 'InvoiceController@getData')->name('invoices.getData');
// ---------------------------------------------------------------------------------------------------------------

// Ruleta 
    Route::post('rouletteGetCoupons', 'rouletteController@getCoupons')->name('wheel.getCoupons');
    Route::post('rouletteGetCouponCode', 'rouletteController@getCouponCode')->name('wheel.getCouponCode');
    Route::post('rouletteSetCookie', 'rouletteController@wheelSetCookie')->name('wheel.wheelSetCookie');
// ---------------------------------------------------------------------------------------------------------------

// faqja per rekomandime te restoranteve per kontaktim 
    Route::get('/empfehlungen-formular', 'resContRecommendedController@openFirstPage')->name('resCRecom.openFirstPage');
    Route::post('/empfehlung-formular-Save', 'resContRecommendedController@saveInstance')->name('resCRecom.saveInstance');
// ---------------------------------------------------------------------------------------------------------------

    Route::get('quittungen-bowling', 'HomeController@openRes45BillTabletPage')->name('homeCtrl.openRes45BillTabletPage');


// Clean Invalide TAB 
    Route::get('cleanInvalideTabOnTable', 'HomeController@cleanInvalideTabOnTable')->name('storedProc.cleanInvalideTabOnTable');
// ---------------------------------------------------------------------------------------------------------------
?>