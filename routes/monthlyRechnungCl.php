<?php
    use Illuminate\Support\Facades\Route;
    
    Route::get('checkForMSendBill', 'MonthlyRechnungForClController@checkBilSendM')->name('mRechFCl.checkBilSendM');

    Route::post('getClPdfBills', 'MonthlyRechnungForClController@getClPdfBills')->name('mRechFCl.getClPdfBills');
?>