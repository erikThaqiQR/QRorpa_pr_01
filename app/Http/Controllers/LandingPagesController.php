<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingPagesController extends Controller{
    public function amTischBestellen_index(){ return view('LPages.am_tisch_bestellen.index'); }
    public function amTischBestellen_bar(){ return view('LPages.am_tisch_bestellen.bar'); }
    public function amTischBestellen_disco(){ return view('LPages.am_tisch_bestellen.disco'); }
    public function amTischBestellen_restaurant(){ return view('LPages.am_tisch_bestellen.restaurant'); }
    public function amTischBestellen_und_bezahlen(){ return view('LPages.am_tisch_bestellen.und_bezahlen'); }
    public function amTischBestellen_und_bezahlen_bar(){ return view('LPages.am_tisch_bestellen.und_bezahlen_bar'); }
    public function amTischBestellen_und_bezahlen_disco(){ return view('LPages.am_tisch_bestellen.und_bezahlen_disco'); }
    public function amTischBestellen_und_bezahlen_restaurant(){ return view('LPages.am_tisch_bestellen.und_bezahlen_restaurant'); }

    public function amTischBezahlen_index(){ return view('LPages.am_tisch_bezahlen.index'); }
    public function amTischBezahlen_bar(){ return view('LPages.am_tisch_bezahlen.bar'); }
    public function amTischBezahlen_disco(){ return view('LPages.am_tisch_bezahlen.disco'); }
    public function amTischBezahlen_restaurant(){ return view('LPages.am_tisch_bezahlen.restaurant'); }

    public function essen_abholen(){ return view('LPages.essen.abholen'); }
    public function essen_bestellen(){ return view('LPages.essen.bestellen'); }
    public function essen_delivery(){ return view('LPages.essen.delivery'); }
    public function essen_gratis_lieferung(){ return view('LPages.essen.gratis_lieferung'); }
    public function essen_liefern(){ return view('LPages.essen.liefern'); }
    public function essen_schnelle_lieferung(){ return view('LPages.essen.schnelle_lieferung'); }
    public function essen_takeaway(){ return view('LPages.essen.takeaway'); }
    public function essen_take_out(){ return view('LPages.essen.take_out'); }

    public function getraenke_abholen(){ return view('LPages.getraenke.abholen'); }
    public function getraenke_bestellen(){ return view('LPages.getraenke.bestellen'); }
    public function getraenke_delivery(){ return view('LPages.getraenke.delivery'); }
    public function getraenke_gratis_lieferung(){ return view('LPages.getraenke.gratis_lieferung'); }
    public function getraenke_liefern(){ return view('LPages.getraenke.liefern'); }
    public function getraenke_schnelle_lieferung(){ return view('LPages.getraenke.schnelle_lieferung'); }
    public function getraenke_takeaway(){ return view('LPages.getraenke.takeaway'); }
    public function getraenke_take_out(){ return view('LPages.getraenke.take_out'); }

    public function kontaktlos_bestellen_index(){ return view('LPages.kontaktlos_bestellen.index'); }
    public function kontaktlos_bestellen_bar(){ return view('LPages.kontaktlos_bestellen.bar'); }
    public function kontaktlos_bestellen_disco(){ return view('LPages.kontaktlos_bestellen.disco'); }
    public function kontaktlos_bestellen_restaurant(){ return view('LPages.kontaktlos_bestellen.restaurant'); }
    public function kontaktlos_bestellen2_index(){ return view('LPages.kontaktlos_bestellen.index2'); }
    public function kontaktlos_bestellen2_bar(){ return view('LPages.kontaktlos_bestellen.bar2'); }
    public function kontaktlos_bestellen2_disco(){ return view('LPages.kontaktlos_bestellen.disco2'); }
    public function kontaktlos_bestellen2_gastronomie(){ return view('LPages.kontaktlos_bestellen.gastronomie'); }
    public function kontaktlos_bestellen2_restaurant(){ return view('LPages.kontaktlos_bestellen.restaurant2'); }

    public function kontaktlos_bezahlen_bar(){ return view('LPages.kontaktlos_bezahlen.bar'); }
    public function kontaktlos_bezahlen_disco(){ return view('LPages.kontaktlos_bezahlen.disco'); }
    public function kontaktlos_bezahlen_restaurant(){ return view('LPages.kontaktlos_bezahlen.restaurant'); }

    public function qrcode_bestellen(){ return view('LPages.qrcode.bestellen'); }
    public function qrcode_bestellen_bar(){ return view('LPages.qrcode.bestellen_bar'); }
    public function qrcode_bestellen_disco(){ return view('LPages.qrcode.bestellen_disco'); }
    public function qrcode_bestellen_restaurant(){ return view('LPages.qrcode.bestellen_restaurant'); }
    public function qrcode_bezahlen(){ return view('LPages.qrcode.bezahlen'); }
    public function qrcode_bezahlen_bar(){ return view('LPages.qrcode.bezahlen_bar'); }
    public function qrcode_bezahlen_disco(){ return view('LPages.qrcode.bezahlen_disco'); }
    public function qrcode_bezahlen_restaurant(){ return view('LPages.qrcode.bezahlen_restaurant'); }

    public function qrScan_index(){ return view('LPages.qrcode.qrScan_index'); }
    public function qrScan_bar(){ return view('LPages.qrcode.qrScan_bar'); }
    public function qrScan_disco(){ return view('LPages.qrcode.qrScan_disco'); }
    public function qrScan_restaurant(){ return view('LPages.qrcode.qrScan_restaurant'); }

    public function scanOr_index(){ return view('LPages.scan_order_pay.index'); }
    public function scanOr_bar(){ return view('LPages.scan_order_pay.bar'); }
    public function scanOr_disco(){ return view('LPages.scan_order_pay.disco'); }
    public function scanOr_hotel(){ return view('LPages.scan_order_pay.hotel'); }
    public function scanOr_restaurant(){ return view('LPages.scan_order_pay.restaurant'); }

    public function trinken_abholen(){ return view('LPages.trinken.abholen'); }
    public function trinken_bestellen(){ return view('LPages.trinken.bestellen'); }
    public function trinken_delivery(){ return view('LPages.trinken.delivery'); }
    public function trinken_gratis_lieferung(){ return view('LPages.trinken.gratis_lieferung'); }
    public function trinken_liefern(){ return view('LPages.trinken.liefern'); }
    public function trinken_schnelle_lieferung(){ return view('LPages.trinken.schnelle_lieferung'); }
    public function trinken_takeaway(){ return view('LPages.trinken.takeaway'); }
    public function trinken_take_out(){ return view('LPages.trinken.take_out'); }

    public function admin_benutzung(){ return view('LPages.admin_benutzung'); }
    public function contact_process(){ return view('LPages.contact_process'); }
    public function kunden_benutzung(){ return view('LPages.kunden_benutzung'); }
    public function kunden_benutzung_barber(){ return view('LPages.kunden_benutzung_barber'); }
    public function multi(){ return view('LPages.multi'); }

    
    
}
