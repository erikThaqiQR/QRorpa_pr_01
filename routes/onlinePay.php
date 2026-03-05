<?php

    // Restaurant Online pay 
    Route::post('onlinePayTakeaway', 'OnlinePaymentsController@payResTakeawayOnline')->name('onlinePay.payResTakeawayOnline');
    Route::post('onlinePaySaferPayQrorpa', 'OnlinePaymentsController@saferPayQrorpa')->name('onlinePay.saferPayQrorpa');
    Route::post('onlinePaySaferPayQrorpa313233', 'OnlinePaymentsController313233@saferPayQrorpa')->name('onlinePay313233.saferPayQrorpa');
    Route::get('onlinePaySaferPayQrorpaRegister', 'OnlinePaymentsController@saferPayQrorpaRegister')->name('onlinePay.saferPayQrorpaRegister');
    //------------------------------------------------------------------------------------------------------------------------------------

    // Takeaway OTHERS online pay 
    Route::post('onlineTakeawayReceivePhNr', 'OnlinePaymentsController@onlineTakeawayReceivePhNr')->name('onlinePayTOthers.onlineTakeawayReceivePhNr');

    // Given Phone number
    Route::post('onlineTakeawayReceiveCode', 'OnlinePaymentsController@onlineTakeawayReceiveCode')->name('onlinePayTOthers.onlineTakeawayReceiveCode');
    Route::post('onlineTakeawayReceiveCode313233', 'OnlinePaymentsController313233@onlineTakeawayReceiveCode')->name('onlinePayTOthers313233.onlineTakeawayReceiveCode');

    Route::get('onlinePaySaferPayQrorpaTakeawayRegister', 'OnlinePaymentsController@saferPayQrorpaTakeawayRegister')->name('onlinePayTOthers.saferPayQrorpaTakeawayRegister');
    // --------------------------------------------------------------------------------------------------------------------------------------------------------------

    // User Phone number
    Route::post('onlineTakeawayUseUsrPNumber', 'OnlinePaymentsController@onlineTakeawayUseUsrPNumber')->name('onlinePayTOthers.onlineTakeawayUseUsrPNumber');
    Route::post('onlineTakeawayUseUsrPNumber313233', 'OnlinePaymentsController313233@onlineTakeawayUseUsrPNumber')->name('onlinePayTOthers313233.onlineTakeawayUseUsrPNumber');
    // --------------------------------------------------------------------------------------------------------------------------------------------------------------

    // payrex
    Route::get('oPayEhcChurTakeawayRegister', 'OnlinePaymentsController@ehcChurTakeawayRegister')->name('onlinePay.ehcChurTakeawayRegister');
    // ------------------------------------------------------------------------------------------------------------------------------------

    // Delivery online pay 
    Route::post('onlineDeliveryReceivePhNr', 'OnlinePaymentsController@onlineDeliveryReceivePhNr')->name('onlinePayDOthers.onlineDeliveryReceivePhNr');
    Route::post('onlineDeliveryReceiveCode', 'OnlinePaymentsController@onlineDeliveryReceiveCode')->name('onlinePayDOthers.onlineDeliveryReceiveCode');
    Route::get('onlinePaySaferPayQrorpaDeliveryRegister', 'OnlinePaymentsController@saferPayQrorpaDeliveryRegister')->name('onlinePayDOthers.saferPayQrorpaDeliveryRegister');
    Route::post('onlineDeliveryUseUsrPNumber', 'OnlinePaymentsController@onlineDeliveryUseUsrPNumber')->name('onlinePayDOthers.onlineDeliveryUseUsrPNumber');
    // User Phone number
    Route::post('onlinePayPayrexxQrorpa', 'OnlinePaymentsController@payrexxQrorpa')->name('onlinePay.payrexxQrorpa');
    // ------------------------------------------------------------------------------------------------------------------------------------

    // MANAGE PAGE SUPERADMIN
    Route::get('oPayMngIndex', 'OnlinePayManagementController@OnlinePayIndex')->name('oPayMng.onlinePayIndex');
    Route::post('oPayMngTransferDone', 'OnlinePayManagementController@TransferDone')->name('oPayMng.TransferDone');
    Route::post('oPayMngTransferDoneAllSel', 'OnlinePayManagementController@TransferDoneAllSel')->name('oPayMng.TransferDoneAllSel');
    // ------------------------------------------------------------------------------------------------------------------------------------

    // Online pay from staf
    Route::post('oPayFStafInitiate', 'OnlinePayFromStafController@OnlinePayInitiate')->name('oPayFStaf.OnlinePayInitiate');
    Route::post('oPayFStafInitiateSelective', 'OnlinePayFromStafController@OnlinePayInitiateSelective')->name('oPayFStaf.OnlinePayInitiateSelective');

    Route::post('oPayFStafInitiateTA', 'OnlinePayFromStafController@OnlinePayInitiateTA')->name('oPayFStaf.OnlinePayInitiateTA');

    Route::get('oPayFStafOpenOrder', 'OnlinePayFromStafController@oPayFStafOpenOrder')->name('oPayFStaf.oPayFStafOpenOrder');

    Route::post('oPayFStafPayAllRes', 'OnlinePayFromStafController@oPayFStafPayAllRes')->name('oPayFStaf.oPayFStafPayAllRes');
    Route::get('oPayFStafPayAllResRegOrd', 'OnlinePayFromStafController@oPayFStafPayAllResRegOrd')->name('oPayFStaf.oPayFStafPayAllResRegOrd');
    Route::get('onlPayClPageFinish', 'OnlinePayFromStafController@onlPayClPageFinish')->name('oPayFStaf.onlPayClPageFinish');

    Route::post('oPayFStafPaySelectedRes', 'OnlinePayFromStafController@oPayFStafPaySelectedRes')->name('oPayFStaf.oPayFStafPaySelectedRes');
    Route::get('oPayFStafPaySelectedResRegOrd', 'OnlinePayFromStafController@oPayFStafPaySelectedResRegOrd')->name('oPayFStaf.oPayFStafPaySelectedResRegOrd');

    Route::post('oPayFStafPayTakeaway', 'OnlinePayFromStafController@oPayFStafPayTakeaway')->name('oPayFStaf.oPayFStafPayTakeaway');
    Route::get('oPayFStafPayTakeawayRegOrd', 'OnlinePayFromStafController@oPayFStafPayTakeawayRegOrd')->name('oPayFStaf.oPayFStafPayTakeawayRegOrd');
    
    // ------------------------------------------------------------------------------------------------------------------------------------

    // Online pay from staf (Res :31 32 33)
    Route::post('oPayFStafPayAllRes313233', 'OnlinePayFromStafController313233@oPayFStafPayAllRes')->name('oPayFStaf.oPayFStafPayAllRes313233');
    Route::get('oPayFStafPayAllResRegOrd313233', 'OnlinePayFromStafController313233@oPayFStafPayAllResRegOrd')->name('oPayFStaf.oPayFStafPayAllResRegOrd313233');
    
    Route::post('oPayFStafPaySelectedRes313233', 'OnlinePayFromStafController313233@oPayFStafPaySelectedRes')->name('oPayFStaf.oPayFStafPaySelectedRes313233');
    Route::get('oPayFStafPaySelectedResRegOrd313233', 'OnlinePayFromStafController313233@oPayFStafPaySelectedResRegOrd')->name('oPayFStaf.oPayFStafPaySelectedResRegOrd313233');
    
    Route::post('oPayFStafPayTakeaway313233', 'OnlinePayFromStafController313233@oPayFStafPayTakeaway')->name('oPayFStaf.oPayFStafPayTakeaway313233');
    Route::get('oPayFStafPayTakeawayRegOrd313233', 'OnlinePayFromStafController313233@oPayFStafPayTakeawayRegOrd')->name('oPayFStaf.oPayFStafPayTakeawayRegOrd313233');
    // ------------------------------------------------------------------------------------------------------------------------------------
?>