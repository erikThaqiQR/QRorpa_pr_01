<?php
    // Restorant Contracts 
    Route::get('/contracts', 'RestaurantOffersController@index')->name('contracts.index');
    Route::get('restaurantOffers', 'RestaurantOffersController@SAindex')->name('restaurantOffers.SAindex');
    Route::get('restaurantOffers/getdata', 'RestaurantOffersController@getData')->name('restaurantOffers.getData');
    Route::get('restaurantOffers/fetchdata', 'RestaurantOffersController@fetchdata')->name('restaurantOffers.fetchdata');
    Route::post('restaurantOffers/updatecontract', 'RestaurantOffersController@updateContract')->name('restaurantOffers.updateContract');
    Route::post('contracts', 'RestaurantOffersController@store')->name('contracts.store')->middleware('auth');
    Route::get('contracts/{id}/print', 'RestaurantOffersController@generateInvoice')->name('emails.contractPdf');
    Route::get('contracts/{id}', 'RestaurantOffersController@sendEmail')->name('sendEmail');
    //------------------------------------------------

    // Restorant Contracts - Tel
    Route::post('contractsTel', 'RestaurantOffersController@storeTel')->name('contracts.storeTel');
    //------------------------------------------------------------------------------------------------



    Route::get('saContractIndex', 'saContractController@index')->name('saContracts.index');
    Route::post('saContractAddNew', 'saContractController@store')->name('saContracts.addNew');
    Route::post('saContractCheckEmailUse', 'saContractController@checkEmailUse')->name('saContracts.checkEmailUse');
    Route::post('saContractSendConToEmail', 'saContractController@sendConToEmail')->name('saContracts.sendConToEmail');

    Route::post('saContractGetPDFcontract', 'saContractController@getPDFcontract')->name('saContracts.getPDFcontract');

?>