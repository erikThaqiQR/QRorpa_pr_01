<?php
    use Illuminate\Support\Facades\Route;

    Route::get('StockMngPage', 'StockMngController@StockMngPage')->name('stock.stockMngPage');

    Route::post('StockMngRegAllCategory', 'StockMngController@StockMngRegAllCategory')->name('stock.StockMngRegAllCategory');
    Route::post('StockMngRegProduct', 'StockMngController@StockMngRegProduct')->name('stock.StockMngRegProduct');
    Route::post('StockMngRegExtra', 'StockMngController@StockMngRegExtra')->name('stock.StockMngRegExtra');

    Route::post('StockMngAddSasiTo', 'StockMngController@StockMngAddSasiTo')->name('stock.StockMngAddSasiTo');
    Route::post('StockMngAddSasiByNrTo', 'StockMngController@StockMngAddSasiByNrTo')->name('stock.StockMngAddSasiByNrTo');

    Route::post('StockMngSavePeriodGrChngs', 'StockMngController@StockMngSavePeriodGrChngs')->name('stock.StockMngSavePeriodGrChngs');

    Route::post('StockMngGetStockInsFromIds', 'StockMngController@StockMngGetStockInsFromIds')->name('stock.StockMngGetStockInsFromIds');
    Route::post('StockMngGetProdNameAndType', 'StockMngController@StockMngGetProdNameAndType')->name('stock.StockMngGetProdNameAndType');
    Route::post('StockMngSetKgLtrToStIns', 'StockMngController@StockMngSetKgLtrToStIns')->name('stock.StockMngSetKgLtrToStIns');
    Route::post('StockMngRemoveKgLtrToStIns', 'StockMngController@StockMngRemoveKgLtrToStIns')->name('stock.StockMngRemoveKgLtrToStIns');
    Route::post('StockMngSaveStockFromKgLtr', 'StockMngController@StockMngSaveStockFromKgLtr')->name('stock.StockMngSaveStockFromKgLtr');

    Route::post('StockMngDeleteSockInsProduct', 'StockMngController@StockMngDeleteSockInsProduct')->name('stock.StockMngDeleteSockInsProduct');
    Route::post('StockMngDeleteSockInsEkstra', 'StockMngController@StockMngDeleteSockInsEkstra')->name('stock.StockMngDeleteSockInsEkstra');
?>