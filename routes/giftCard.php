<?php
    use Illuminate\Support\Facades\Route;

    // GiftCard Management
    Route::get('giftCardMngAdmin', 'GiftCardController@giftCardMngAdmin')->name('giftCard.giftCardMngAdmin');
    Route::post('giftCardRegCashCardPay', 'GiftCardController@giftCardRegCashCardPay')->name('giftCard.giftCardRegCashCardPay');
    Route::post('giftCardRegOnlinePay', 'GiftCardController@giftCardRegOnlinePay')->name('giftCard.giftCardRegOnlinePay');
    Route::post('giftCardRegAufRechnungPay', 'GiftCardController@giftCardRegAufRechnungPay')->name('giftCard.giftCardRegAufRechnungPay');

    Route::post('giftCardValidateTheIdnCode', 'GiftCardController@giftCardValidateTheIdnCode')->name('giftCard.giftCardValidateTheIdnCode');
    Route::post('giftCardValidateTheSumToApplyDisc', 'GiftCardController@giftCardValidateTheSumToApplyDisc')->name('giftCard.giftCardValidateTheSumToApplyDisc');
    Route::post('giftCardValidateTheSumToApplyDiscMax', 'GiftCardController@giftCardValidateTheSumToApplyDiscMax')->name('giftCard.giftCardValidateTheSumToApplyDiscMax');

    Route::post('giftCardDeleteInstance', 'GiftCardController@giftCardDeleteInstance')->name('giftCard.giftCardDeleteInstance');

    Route::get('giftCardOnlinePay', 'GiftCardController@giftCardClientPayOnline')->name('giftCard.giftCardClientPayOnline');
    Route::post('giftCardOnlinePayInitiatePay', 'GiftCardController@giftCardOnlinePayInitiatePay')->name('giftCard.giftCardOnlinePayInitiatePay');
    Route::get('giftCardOnlinePayInitiatePayRegister', 'GiftCardController@giftCardOnlinePayInitiatePayRegister')->name('giftCard.giftCardOnlinePayInitiatePayRegister');
    Route::get('giftCardOnlinePayFinishPage', 'GiftCardController@giftCardOnlinePayFinishPage')->name('giftCard.giftCardOnlinePayFinishPage');

    Route::get('giftCardMngAdminWa', 'GiftCardController@giftCardMngAdminWa')->name('giftCardWa.giftCardMngAdminWa');

    Route::post('giftCardShowExpAndUsed', 'GiftCardController@giftCardShowExpAndUsed')->name('giftCardWa.giftCardShowExpAndUsed');
    Route::post('giftCardShowDeletedIns', 'GiftCardController@giftCardShowDeletedIns')->name('giftCardWa.giftCardShowDeletedIns');

    Route::post('giftCardGetReceipt', 'GiftCardController@giftCardGetReceipt')->name('giftCard.giftCardGetReceipt');
    Route::get('giftCardGetReceiptFQRC', 'GiftCardController@giftCardGetReceiptFQRC')->name('giftCard.giftCardGetReceiptFQRC');

    Route::get('giftCardcheckBalance', 'GiftCardController@giftCardcheckBalance')->name('giftCard.giftCardcheckBalance');

    Route::post('giftCardFetchcheckBalance', 'GiftCardController@giftCardFetchcheckBalance')->name('giftCard.giftCardFetchcheckBalance');

    Route::get('giftCardCreateGCToSell', 'GiftCardController@giftCardCreateGCToSell')->name('giftCard.giftCardCreateGCToSell');

    Route::post('validateGiftCardToSellKartel', 'GiftCardController@validateGiftCardToSellKartel')->name('giftCard.validateGiftCardToSellKartel');

    Route::post('validateGiftCardOnScanToApply', 'GiftCardController@validateGiftCardOnScanToApply')->name('giftCard.validateGiftCardOnScanToApply');

    Route::post('searchGCByCode', 'GiftCardController@searchGCByCode')->name('giftCard.searchGCByCode');
    //--------------------------------------------------------------------------------------------------------------------

    // giftCardCreateGCToSell?hashVal=hPj65fggFG4234FGfBjQasLMk345jGfD24784d&nrOfGC=5
?>