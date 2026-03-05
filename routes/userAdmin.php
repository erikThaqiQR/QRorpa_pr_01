<?php
    // Menaxhimi i userave 
    Route::get('/userAdmin', 'UserAdminControler@index')->name('userAd.index');
    Route::post('/userAdminchUS', 'UserAdminControler@changeUR')->name('userAd.changeUR');
    Route::post('/userSetRes', 'UserAdminControler@setRes')->name('userAd.setRes');

    Route::get('/userKuzhinier', 'UserAdminControler@indexKuzhinier')->name('userAd.indexKuzhinier');
    Route::post('/userKuzhinierS', 'UserAdminControler@storeKuzhinier')->name('userAd.storeKuzhinier');
    Route::delete('/userKuzhinierD', 'UserAdminControler@destroyKuzhinier')->name('userAd.destroyKuzhinier');

    Route::get('/userKamarier', 'UserAdminControler@indexKamarier')->name('userAd.indexKamarier');
    Route::post('/userKamarierS', 'UserAdminControler@storeKamarier')->name('userAd.storeKamarier');


    Route::post('saMngUsersSetToUser', 'UserAdminControler@setToUser')->name('saMngUsr.setToUser');

    Route::post('saMngUsersSetToAdmin', 'UserAdminControler@setToAdmin')->name('saMngUsr.setToAdmin');
    Route::post('saMngUsersSetToWaiter', 'UserAdminControler@setToWaiter')->name('saMngUsr.setToWaiter');
    Route::post('saMngUsersSetToCook', 'UserAdminControler@setToCook')->name('saMngUsr.setToCook');
    Route::post('saMngUsersSetToAccountant', 'UserAdminControler@setToAccountant')->name('saMngUsr.setToAccountant');

    Route::post('saMngUsersSetToSuperadmin', 'UserAdminControler@setToSuperadmin')->name('saMngUsr.setToSuperadmin');
    Route::post('saMngUsersSetToContractAgent', 'UserAdminControler@setToContractAgent')->name('saMngUsr.setToContractAgent');
    Route::post('saMngUsersSetToBarbershopAdmin', 'UserAdminControler@setToBarbershopAdminAgent')->name('saMngUsr.setToBarbershopAdminAgent');

    Route::post('saMngUsersSelectThisResForThisAdm', 'UserAdminControler@selectThisResForThisAdm')->name('saMngUsr.selectThisResForThisAdm');
    Route::post('saMngUsersSelectThisBarForThisAdm', 'UserAdminControler@selectThisBarForThisAdm')->name('saMngUsr.selectThisBarForThisAdm');

    Route::post('saMngUsersSetResToAdmExtraAcs', 'UserAdminControler@setResToAdmExtraAcs')->name('saMngUsr.setResToAdmExtraAcs');
    Route::post('saMngUsersRemoveResToAdmExtraAcs', 'UserAdminControler@removeResToAdmExtraAcs')->name('saMngUsr.removeResToAdmExtraAcs');

    Route::post('checkIfAUserExistsWithEmail', 'UserAdminControler@checkIfAUserExistsWithEmail')->name('saMngUsr.checkIfAUserExistsWithEmail');
    Route::post('registerTemUser', 'UserAdminControler@registerTemUser')->name('saMngUsr.registerTemUser');
    Route::post('registerTemUserRes', 'UserAdminControler@registerTemUserRes')->name('saMngUsr.registerTemUserRes');
    Route::get('taAcRegesterConf', 'UserAdminControler@taAcRegesterConfFC')->name('saMngUsr.taAcRegesterConfFC');
    

    //--------------------------------------------------------------------------------------------------------------------
?>