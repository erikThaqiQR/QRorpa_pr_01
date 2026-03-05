<?php
    // Superadmin access manager controller
    Route::get('SAaccessMngOpenPage', 'SuperadminAccessMngController@index')->name('SAaccessMng.index');
    Route::post('SAaccessMngFetchAdmins', 'SuperadminAccessMngController@fetchAdmins')->name('SAaccessMng.fetchAdmins');
    Route::post('SAaccessMngFetchAccess', 'SuperadminAccessMngController@fetchAccess')->name('SAaccessMng.fetchAccess');
    Route::post('SAaccessMngRegDelAccess', 'SuperadminAccessMngController@regDelAccess')->name('SAaccessMng.regDelAccess');
    Route::post('SAaccessMngChangeValidity', 'SuperadminAccessMngController@changeValidity')->name('SAaccessMng.changeValidity');

    Route::post('SAaccessMngRegAllAccess', 'SuperadminAccessMngController@regAllAccess')->name('SAaccessMng.regAllAccess');
    Route::post('SAaccessMngDelAllAccess', 'SuperadminAccessMngController@delAllAccess')->name('SAaccessMng.delAllAccess');
    // -----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    
    // SA Statisctics 
    Route::get('SAStatistics', 'SAStatisticsController@index')->name('saStatistics.index');
    Route::get('SAStatisticsRes', 'SAStatisticsController@indexRes')->name('saStatistics.indexRes');
    Route::post('SAStatisticsEinloggenClicksOne', 'SAStatisticsController@einloggenClicksOne')->name('saStatistics.einloggenClicksOne');
    Route::post('SAStatisticsRegisterClicksOne', 'SAStatisticsController@registerClicksOne')->name('saStatistics.registerClicksOne');
    Route::post('SAStatisticsSAPageOpenOne', 'SAStatisticsController@SAPageOpenOne')->name('saStatistics.SAPageOpenOne');
    Route::post('SAStatisticsAPageOpenOne', 'SAStatisticsController@APageOpenOne')->name('saStatistics.APageOpenOne');
    Route::post('SAStatisticsWaiterCallsOpenOne', 'SAStatisticsController@WaiterCallsOpenOne')->name('saStatistics.WaiterCallsOpenOne');
    Route::post('SAStatisticsCartOpenOne', 'SAStatisticsController@CartOpenOne')->name('saStatistics.CartOpenOne');
    Route::post('SAStatisticsMyOrdersOpenOne', 'SAStatisticsController@MyOrdersOpenOne')->name('saStatistics.MyOrdersOpenOne');
    Route::post('SAStatisticsTrackOrderOpenOne', 'SAStatisticsController@TrackOrderOpenOne')->name('saStatistics.TrackOrderOpenOne');
    Route::post('SAStatisticsCovid19OpenOne', 'SAStatisticsController@Covid19OpenOne')->name('saStatistics.Covid19OpenOne');
    Route::post('SAStatisticsProfileOpenOne', 'SAStatisticsController@ProfileOpenOne')->name('saStatistics.ProfileOpenOne');

    Route::post('SAStatisticsBannerClickOne', 'SAStatisticsController@BannerClickOne')->name('saStatistics.BannerClickOne');
    Route::post('SAStatisticsBannerClickLinkOne', 'SAStatisticsController@BannerClickLinkOne')->name('saStatistics.BannerClickLinkOne');
    //--------------------------------------------------------------------------------------------------------------------
?>