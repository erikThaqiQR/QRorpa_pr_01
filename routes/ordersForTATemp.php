<?php
    Route::post('/searchProdsForTATemp', 'OrdersTempForTAController@searchProdsForTaTemp')->name('tempTAProds.searchProdsForTaTemp');

    Route::post('/deleteTempOrderOne', 'OrdersTempForTAController@deleteTempOrder')->name('tempTAProds.deleteTempOrder');
    Route::post('/deleteTempOrderAll', 'OrdersTempForTAController@deleteTempOrderAll')->name('tempTAProds.deleteTempOrderAll');

    Route::post('/storeTempOrderTA', 'OrdersTempForTAController@storeTempOrder')->name('tempTAProds.storeTempOrder');
    Route::post('/expressRegTATempProd', 'OrdersTempForTAController@storeExpresWOT')->name('tempTAProds.storeExpresWOT');
    Route::post('/expressRegTATempProdWT', 'OrdersTempForTAController@storeExpresWT')->name('tempTAProds.storeExpresWT');

    Route::post('/fetchOrdersForPayment', 'OrdersTempForTAController@fetchOrderPay')->name('tempTAProds.fetchOrderPay');

    Route::post('/payTempTaWithCash', 'OrdersTempForTAController@payTempTaWithCash')->name('tempTAProds.payTempTaWithCash');
    Route::post('/payTempTaWithCard', 'OrdersTempForTAController@payTempTaWithCard')->name('tempTAProds.payTempTaWithCard');
    
    Route::post('/payTempTaByBillWithData', 'OrdersTempForTAController@payTempTaByBillWithData')->name('tempTAProds.payTempTaByBillWithData');
    Route::post('/payTempTaByBillWithClient', 'OrdersTempForTAController@payTempTaByBillWithClient')->name('tempTAProds.payTempTaByBillWithClient');

?>