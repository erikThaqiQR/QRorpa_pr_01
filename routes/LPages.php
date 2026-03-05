<?php
    use Illuminate\Support\Facades\Route;
    // am-tisch-bestellen
        Route::get('LandingPages/am-tisch-bestellen', 'LandingPagesController@amTischBestellen_index')->name('atb.index');
        Route::get('LandingPages/am-tisch-bestellen-bar', 'LandingPagesController@amTischBestellen_bar')->name('atb.bar');
        Route::get('LandingPages/am-tisch-bestellen-disco', 'LandingPagesController@amTischBestellen_disco')->name('atb.disco');
        Route::get('LandingPages/am-tisch-bestellen-restaurant', 'LandingPagesController@amTischBestellen_restaurant')->name('atb.restaurant');
        Route::get('LandingPages/am-tisch-bestellen-und-bezahlen', 'LandingPagesController@amTischBestellen_und_bezahlen')->name('atb.und_bezahlen');
        Route::get('LandingPages/am-tisch-bestellen-und-bezahlen-bar', 'LandingPagesController@amTischBestellen_und_bezahlen_bar')->name('atb.und_bezahlen_bar');
        Route::get('LandingPages/am-tisch-bestellen-und-bezahlen-disco', 'LandingPagesController@amTischBestellen_und_bezahlen_disco')->name('atb.und_bezahlen_disco');
        Route::get('LandingPages/am-tisch-bestellen-und-bezahlen-restaurant', 'LandingPagesController@amTischBestellen_und_bezahlen_restaurant')->name('atb.und_bezahlen_restaurant');

    // am-tisch-bezahlen
        Route::get('LandingPages/am-tisch-bezahlen', 'LandingPagesController@amTischBezahlen_index')->name('atb2.index');
        Route::get('LandingPages/am-tisch-bezahlen-bar', 'LandingPagesController@amTischBezahlen_bar')->name('atb2.bar');
        Route::get('LandingPages/am-tisch-bezahlen-disco', 'LandingPagesController@amTischBezahlen_disco')->name('atb2.disco');
        Route::get('LandingPages/am-tisch-bezahlen-restaurant', 'LandingPagesController@amTischBezahlen_restaurant')->name('atb2.restaurant');

    // essen
        Route::get('LandingPages/essen-abholen', 'LandingPagesController@essen_abholen')->name('essen.abholen');
        Route::get('LandingPages/essen-bestellen', 'LandingPagesController@essen_bestellen')->name('essen.bestellen');
        Route::get('LandingPages/essen-delivery', 'LandingPagesController@essen_delivery')->name('essen.delivery');
        Route::get('LandingPages/essen-gratis-lieferung', 'LandingPagesController@essen_gratis_lieferung')->name('essen.gratis_lieferung');
        Route::get('LandingPages/essen-liefern', 'LandingPagesController@essen_liefern')->name('essen.liefern');
        Route::get('LandingPages/essen-schnelle-lieferung', 'LandingPagesController@essen_schnelle_lieferung')->name('essen.schnelle_lieferung');
        Route::get('LandingPages/essen-takeaway', 'LandingPagesController@essen_takeaway')->name('essen.takeaway');
        Route::get('LandingPages/essen-take-out', 'LandingPagesController@essen_take_out')->name('essen.take_out');

    // getraenke
        Route::get('LandingPages/getraenke-abholen', 'LandingPagesController@getraenke_abholen')->name('getraenke.abholen');
        Route::get('LandingPages/getraenke-bestellen', 'LandingPagesController@getraenke_bestellen')->name('getraenke.bestellen');
        Route::get('LandingPages/getraenke-delivery', 'LandingPagesController@getraenke_delivery')->name('getraenke.delivery');
        Route::get('LandingPages/getraenke-gratis-lieferung', 'LandingPagesController@getraenke_gratis_lieferung')->name('getraenke.gratis_lieferung');
        Route::get('LandingPages/getraenke-liefern', 'LandingPagesController@getraenke_liefern')->name('getraenke.liefern');
        Route::get('LandingPages/getraenke-schnelle-lieferung', 'LandingPagesController@getraenke_schnelle_lieferung')->name('getraenke.schnelle_lieferung');
        Route::get('LandingPages/getraenke-takeaway', 'LandingPagesController@getraenke_takeaway')->name('getraenke.takeaway');
        Route::get('LandingPages/getraenke-take-out', 'LandingPagesController@getraenke_take_out')->name('getraenke.take_out');

    // kontaktlos-bestellen
        Route::get('LandingPages/kontaktlos-bestellen', 'LandingPagesController@kontaktlos_bestellen_index')->name('kontaktlosBestellen.index');
        Route::get('LandingPages/kontaktlos-bestellen-bar', 'LandingPagesController@kontaktlos_bestellen_bar')->name('kontaktlosBestellen.bar');
        Route::get('LandingPages/kontaktlos-bestellen-disco', 'LandingPagesController@kontaktlos_bestellen_disco')->name('kontaktlosBestellen.disco');
        Route::get('LandingPages/kontaktlos-bestellen-restaurant', 'LandingPagesController@kontaktlos_bestellen_restaurant')->name('kontaktlosBestellen.restaurant');
        Route::get('LandingPages/kontaktlos-bestellen-und-bezahlen', 'LandingPagesController@kontaktlos_bestellen2_index')->name('kontaktlosBestellen2.index');
        Route::get('LandingPages/kontaktlos-bestellen-und-bezahlen-bar', 'LandingPagesController@kontaktlos_bestellen2_bar')->name('kontaktlosBestellen2.bar');
        Route::get('LandingPages/kontaktlos-bestellen-und-bezahlen-disco', 'LandingPagesController@kontaktlos_bestellen2_disco')->name('kontaktlosBestellen2.disco');
        Route::get('LandingPages/kontaktlos-bestellen-und-bezahlen-gastronomie', 'LandingPagesController@kontaktlos_bestellen2_gastronomie')->name('kontaktlosBestellen2.gastronomie');
        Route::get('LandingPages/kontaktlos-bestellen-und-bezahlen-restaurant', 'LandingPagesController@kontaktlos_bestellen2_restaurant')->name('kontaktlosBestellen2.restaurant');

    // kontaktlos-bezahlen
        Route::get('LandingPages/kontaktlos-bezahlen-bar', 'LandingPagesController@kontaktlos_bezahlen_bar')->name('kontaktlosBezahlen.bar');
        Route::get('LandingPages/kontaktlos-bezahlen-disco', 'LandingPagesController@kontaktlos_bezahlen_disco')->name('kontaktlosBezahlen.disco');
        Route::get('LandingPages/kontaktlos-bezahlen-restaurant', 'LandingPagesController@kontaktlos_bezahlen_restaurant')->name('kontaktlosBezahlen.restaurant');

    // qrcode
        Route::get('LandingPages/qrcode-bestellen', 'LandingPagesController@qrcode_bestellen')->name('qrcode.bestellen');
        Route::get('LandingPages/qrcode-bestellen-bar', 'LandingPagesController@qrcode_bestellen_bar')->name('qrcode.bestellen_bar');
        Route::get('LandingPages/qrcode-bestellen-disco', 'LandingPagesController@qrcode_bestellen_disco')->name('qrcode.bestellen_disco');
        Route::get('LandingPages/qrcode-bestellen-restaurant', 'LandingPagesController@qrcode_bestellen_restaurant')->name('qrcode.bestellen_restaurant');
        Route::get('LandingPages/qrcode-bezahlen', 'LandingPagesController@qrcode_bezahlen')->name('qrcode.bezahlen');
        Route::get('LandingPages/qrcode-bezahlen-bar', 'LandingPagesController@qrcode_bezahlen_bar')->name('qrcode.bezahlen_bar');
        Route::get('LandingPages/qrcode-bezahlen-disco', 'LandingPagesController@qrcode_bezahlen_disco')->name('qrcode.bezahlen_disco');
        Route::get('LandingPages/qrcode-bezahlen-restaurant', 'LandingPagesController@qrcode_bezahlen_restaurant')->name('qrcode.bezahlen_restaurant');
    // qrcode-scannen
        Route::get('LandingPages/qrcode-scannen-bestellen-und-bezahlen', 'LandingPagesController@qrScan_index')->name('qrScan.index');
        Route::get('LandingPages/qrcode-scannen-bestellen-und-bezahlen-bar', 'LandingPagesController@qrScan_bar')->name('qrScan.bar');
        Route::get('LandingPages/qrcode-scannen-bestellen-und-bezahlen-disco', 'LandingPagesController@qrScan_disco')->name('qrScan.disco');
        Route::get('LandingPages/qrcode-scannen-bestellen-und-bezahlen-restaurant', 'LandingPagesController@qrScan_restaurant')->name('qrScan.restaurant');

    // scan-order-pay
        Route::get('LandingPages/scan-order-pay', 'LandingPagesController@scanOr_index')->name('scanOr.index');
        Route::get('LandingPages/scan-order-pay-bar', 'LandingPagesController@scanOr_bar')->name('scanOr.bar');
        Route::get('LandingPages/scan-order-pay-disco', 'LandingPagesController@scanOr_disco')->name('scanOr.disco');
        Route::get('LandingPages/scan-order-pay-hotel', 'LandingPagesController@scanOr_hotel')->name('scanOr.hotel');
        Route::get('LandingPages/scan-order-pay-restaurant', 'LandingPagesController@scanOr_restaurant')->name('scanOr.restaurant');

    // trinken
        Route::get('LandingPages/trinken-abholen', 'LandingPagesController@trinken_abholen')->name('trinken.abholen');
        Route::get('LandingPages/trinken-bestellen', 'LandingPagesController@trinken_bestellen')->name('trinken.bestellen');
        Route::get('LandingPages/trinken-delivery', 'LandingPagesController@trinken_delivery')->name('trinken.delivery');
        Route::get('LandingPages/trinken-gratis-lieferung', 'LandingPagesController@trinken_gratis_lieferung')->name('trinken.gratis_lieferung');
        Route::get('LandingPages/trinken-liefern', 'LandingPagesController@trinken_liefern')->name('trinken.liefern');
        Route::get('LandingPages/trinken-schnelle-lieferung', 'LandingPagesController@trinken_schnelle_lieferung')->name('trinken.schnelle_lieferung');
        Route::get('LandingPages/trinken-takeaway', 'LandingPagesController@trinken_takeaway')->name('trinken.takeaway');
        Route::get('LandingPages/trinken-take-out', 'LandingPagesController@trinken_take_out')->name('trinken.take_out');

    // the tutorial pages 
        Route::get('LandingPages/admin-benutzung', 'LandingPagesController@admin_benutzung')->name('tuto.admin_benutzung');
        Route::get('LandingPages/contact-process', 'LandingPagesController@contact_process')->name('tuto.contact_process');
        Route::get('LandingPages/kunden-benutzung', 'LandingPagesController@kunden_benutzung')->name('tuto.kunden_benutzung');
        Route::get('LandingPages/kunden-benutzung-barber', 'LandingPagesController@kunden_benutzung_barber')->name('tuto.kunden_benutzung_barber');
        Route::get('LandingPages/multi', 'LandingPagesController@multi')->name('tuto.multi');
        
        
?>

