
<!-- new contrect Modal  -->
<div class="modal fade" id="addANewContractModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 style="color:rgb(39,190,175);" class="modal-title" id="exampleModalLabel"><strong>Registrieren Sie einen neuen Vertrag</strong></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <i class="far fa-times-circle"></i> </button>
            </div>
            <div class="flex justify-content-between mt-1">
                <button id="contrV1Btn" onclick="chngContrVer(1)" class="btn shadow-none btn-success" style="margin:0px; width:100%;"><strong>Restaurant</strong></button>
                <!-- <button id="contrV2Btn" onclick="chngContrVer(2)" class="btn shadow-none btn-outline-success" style="margin:0px; width:49%;"><strong>Restaurant</strong></button> -->
            </div>
            <div class="modal-body" id="addANewContractModalBody">
                <form action="{{ route('saContracts.addNew') }}" id="saContractsAddNew" method="post" enctype="multipart/form-data">
                    {{csrf_field()}}

                    <div class="d-flex flex-wrap">
                        <div class="input-group" style="width: 100%;">
                            <div style="width: 30%;" class="input-group-prepend">
                                <span style="width:100%;" class="input-group-text" id="addNCon_name_span">Vorname*</span>
                            </div>
                            <input id="addNCon_name" name="addNCon_name" type="text" class="form-control shadow-none" value="">
                        </div>
                        <div class="input-group mt-1" style="width: 100%;">
                            <div style="width: 30%;" class="input-group-prepend">
                                <span style="width:100%;" class="input-group-text" id="addNCon_lastname_span">Nachname*</span>
                            </div>
                            <input id="addNCon_lastname" name="addNCon_lastname" type="text" class="form-control shadow-none" value="">
                        </div>
                        <div class="input-group mt-1" style="width:100%;">
                            <div style="width: 30%;" class="input-group-prepend">
                                <label style="width:100%;" class="input-group-text" for="addNCon_gender">Geschlecht*</label>
                            </div>
                            <select class="custom-select shadow-none" name="addNCon_gender" id="addNCon_gender" value="">
                                <option value="Herr" selected>Herr</option>
                                <option value="Frau">Frau</option>
                                <!-- <option value="None">None</option> -->
                            </select>
                        </div>
                        <div class="input-group mt-1" style="width: 100%;">
                            <div style="width:30%;" class="input-group-prepend">
                                <span style="width:100%;" class="input-group-text" id="inputGroup-sizing-default">Strasse/Nr*</span>
                            </div>
                            <input id="addNCon_street" name="addNCon_street" type="text" class="form-control shadow-none">
                        </div>
                        <div class="input-group mt-1" style="width: 100%;">
                            <div style="width:30%;" class="input-group-prepend">
                                <span style="width:100%;" class="input-group-text" id="inputGroup-sizing-default">PLZ*</span>
                            </div>
                            <input id="addNCon_plz" name="addNCon_plz" type="number" class="form-control shadow-none">
                        </div>
                        <div class="input-group mt-1" style="width: 100%;">
                            <div style="width:30%;" class="input-group-prepend">
                                <span style="width:100%;" class="input-group-text" id="inputGroup-sizing-default">ORT*</span>
                            </div>
                            <input id="addNCon_ort" name="addNCon_ort" type="text" class="form-control shadow-none">
                        </div>
                        <div class="input-group mt-1" style="width: 100%;">
                            <div style="width: 30%;;" class="input-group-prepend">
                                <span style="width: 100%;" class="input-group-text" id="inputGroup-sizing-default">Firmenname*</span>
                            </div>
                            <input id="addNCon_company" name="addNCon_company" type="text" class="form-control shadow-none">
                        </div>
                        <div class="input-group mt-1" style="width: 100%;">
                            <div style="width: 30%;;" class="input-group-prepend">
                                <span style="width: 100%;" class="input-group-text" id="inputGroup-sizing-default"><i class="fas fa-phone"></i>*</span>
                            </div>
                            <input id="addNCon_phoneNr" name="addNCon_phoneNr" type="number" class="form-control shadow-none">
                        </div>
                        <div class="input-group mt-1" style="width: 100%;">
                            <div style="width: 30%;;" class="input-group-prepend">
                                <span style="width: 100%;" class="input-group-text" id="inputGroup-sizing-default"><i class="fas fa-at"></i>*</span>
                            </div>
                            <input id="addNCon_email" name="addNCon_email" type="text" class="form-control shadow-none">
                        </div>

                        <div class="input-group mt-1" style="width: 100%;">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Kreditkarte*</span>
                            </div>
                            <div class="input-group-prepend">
                                <span class="input-group-text text-right">CH</span>
                            </div>
                            <input id="addNCon_bankNr" name="addNCon_bankNr" type="text" class="form-control shadow-none">
                        </div>
                    </div>

                    <div id="addNCon_error01" class="mt-1 text-center alert alert-danger" style="display:none;">
                        <strong>Bitte stellen Sie sicher, dass Sie alle erforderlichen Daten oben eintragen</strong>
                    </div>

                    <div id="addNCon_error02" class="mt-1 text-center alert alert-danger" style="display:none;">
                        <strong>Diese E-Mail-Adresse ist nicht akzeptabel!</strong>
                    </div>

                    <div id="addNCon_error03" class="mt-1 text-center alert alert-danger" style="display:none;">
                        <strong>Diese Telefonnummer ist nicht akzeptabel !</strong>
                    </div>
                    
                    <hr>

                    <div class="d-flex mt-2">
                        <div class="card" style="width: 100%;">
                            <div class="card-header">
                                <strong>Monatliche Kosten</strong>
                            </div>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex flex-wrap justify-content-between" style="padding:3px;">
                                    <span style="width:100%;" class="badge badge-success">✓ Kontaktlos bestellen und bezahlen</span>
                                    <span style="width:100%;" class="badge badge-success mt-1">✓ Serviceteam über System rufen</span>
                                    <!-- <span style="width:49.5%;" class="badge badge-success mt-1">✓ Covid-19 Kontaktformular</span> -->
                                    <span style="width:100%;" class="badge badge-success mt-1">✓ Empfohlene Produkte</span>
                                    <span style="width:49.5%;" class="badge badge-success mt-1">✓ Produkt Management</span>
                                    <span style="width:49.5%;" class="badge badge-success mt-1">✓ Tischwechsel</span>
                                    <span style="width:49.5%;" class="badge badge-success mt-1">✓ Trinkgeld</span>
                                    <span style="width:49.5%;" class="badge badge-success mt-1">✓ Gratis Produkte anbieten</span>
                                    <!-- <span style="width:49.5%;" class="badge badge-success mt-1">✓ Gratis Produkte</span>
                                    <span style="width:49.5%;" class="badge badge-success mt-1">✓ Gratis Produkte anbieten</span> -->
                                    <span style="width:49.5%;" class="badge badge-success mt-1">✓ Gutscheincode</span>
                                    <span style="width:49.5%;" class="badge badge-success mt-1">✓ Kundenbindung</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex mt-2" id="conDivToAddTables">
                        <div class="input-group" style="width: 100%;">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Tische</span>
                            </div>
                            <input type="text" id="addNCon_tables" name="addNCon_tables" placeholder="z.B.: 1-10,20-25 (1 bis 10 und 20 bis 25)"
                                class="form-control shadow-none" onkeyup="countAddNConTablesAmount(this.value)">
                        </div>
                    </div>

                    <div id="addNCon_error07" class="mt-1 text-center alert alert-danger" style="display:none;">
                        <strong>Das Tabellenformat ist falsch, bitte verwenden Sie nur <i>0123456789 , und -</i></strong>
                    </div>
                    <div id="addNCon_error08" class="mt-1 text-center alert alert-danger" style="display:none;">
                        <strong>Einige Tabellenbereiche stören</strong>
                    </div>

                    <div class="d-flex flex-wrap justify-content-between mt-2">
                        <button type="button" class="btn btn-dark" style="width:22%"><span id="addNCon_tables_amount">0</span> <i class="fas fa-border-all"></i></button>
                        <button type="button" id="addNCon_tables1" onclick="selectTablePayment('0','0','0','0')" style="width:77.5%;" class="btn serviceBtn shadow-none">#</button>
                        <button type="button" id="addNCon_tables2" onclick="selectTablePayment('0','0','0','0')" style="width:49.75%;" class="btn serviceBtn shadow-none mt-1">#</button>
                        <button type="button" id="addNCon_tables3" onclick="selectTablePayment('0','0','0','0')" style="width:49.75%;" class="btn serviceBtn shadow-none mt-1">#</button>

                        <input type="hidden" id="addNCon_tablesCope" name="addNCon_tablesCope" value="0">

                        <input type="hidden" id="addNCon_tablesPerMonth" name="addNCon_tablesPerMonth"  value="0">
                        <input type="hidden" id="addNCon_tablesProvision" name="addNCon_tablesProvision" value="0">
                        <input type="hidden" id="addNCon_tablesFixedPerMonth" name="addNCon_tablesFixedPerMonth" value="0">
                        <input type="hidden" id="addNCon_tablesPercentage" name="addNCon_tablesPercentage" value="0">
                    </div>

                    <div class="d-flex mt-2">
                        <div class="card" style="width: 100%;">
                            <div class="card-header">
                                <strong>Einmalige Kosten</strong> <br>
                                Einrichtung, Flyer, Tischkartenhalter, Schulung
                            </div>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item" style="padding:3px;">
                                    <div class="input-group">
                                        <input onkeyup="changeFlyerCost()" id="addNCon_Einmalige" name="addNCon_Einmalige" type="number" value="0" 
                                            class="form-control shadow-none text-right pr-4" min="0.01" step="0.01" disabled>
                                        <input type="hidden" value="0" id="flyerCost" name="flyerCost">
                                        <div class="input-group-append">
                                            <span class="input-group-text"> CHF </span>
                                        </div>
                                    </div>
                                    <div id="flyerNewVal_error01" class="mt-1 text-center alert alert-danger" style="display:none;">
                                        <strong>Geben Sie bitte einen gültigen Wert ein, sonst wird er als Null registriert!</strong>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex flex-wrap justify-content-between mt-2">
                        <p style="width: 100%; color:rgb(39,190,175);"><strong>Anzahl Mitarbeiter/Konten</strong></p>
                    
                        <div style="width: 100%;" id="addANewContractModalAdminsDiv" class="d-flex flex-wrap justify-content-between">
                        </div>

                        <input type="hidden" name="addNCon_newRegAdmins" id="addNCon_newRegAdmins">
                        <div class="input-group d-flex flex-wrap justify-content-between" style="width: 100%;">
                            <input type="text" style="width:49.75%;" placeholder="Benutzername" id="newRegAdminsUsername" class="form-control shadow-none">
                            <input type="text" style="width:49.75%;" placeholder="Passwort" id="newRegAdminsPassword" class="form-control shadow-none">
                            <input type="text" style="width:80%;" placeholder="E-mail" id="newRegAdminsEmail" class="form-control shadow-none">
                            <div style="width: 19.5%;;" class="input-group-append">
                                <button style="width: 100%;;" class="btn btn-dark" onclick="addNewRegAdmins()" type="button"><i class="fas fa-xl fa-user-plus"></i></button>
                            </div>
                        </div>
                    </div>
                    
                    <div id="addNCon_error05" class="mt-1 text-center alert alert-danger" style="display:none;">
                        <strong>Achten Sie darauf, gültige Daten für den neuen Administrator zu schreiben!</strong>
                    </div>
                    <div id="addNCon_error06" class="mt-1 text-center alert alert-danger" style="display:none;">
                        <strong>Diese E-Mail gehört einem anderen Benutzer, bitte versuchen Sie es mit einer anderen!</strong>
                    </div>

                    <hr>

                    <div class="d-flex flex-wrap justify-content-between mt-2">
                        <p style="width: 100%; color:rgb(39,190,175);"><strong>Extra Services</strong></p>
                        <button type="button" id="addNCon_Takeaway" style="width:25%;" class="btn serviceBtn02">Takeaway</button>
                        <button onclick="selectTakeawayPay('19','1','0','0')" type="button" id="addNCon_Takeaway1" style="width:74.5%;" class="btn serviceBtn">19 <sub>CHF</sub>/Monat +1% provision</button>
                        <button onclick="selectTakeawayPay('0','0','39','0')" type="button" id="addNCon_Takeaway2" style="width:49.75%;" class="btn serviceBtn mt-1">39 <sub>CHF</sub>/Monat</button>
                        <button onclick="selectTakeawayPay('0','0','0','7')" type="button" id="addNCon_Takeaway3" style="width:49.75%;" class="btn serviceBtn mt-1">7%</button>
                        <button type="button" id="addNCon_Delivery"  style="width:25%;" class="btn serviceBtn02 mt-2">Delivery</button>
                        <button onclick="selectDeliveryPay('19','1','0','0')" type="button" id="addNCon_Delivery1" style="width:74.5%;" class="btn serviceBtn mt-2">19 <sub>CHF</sub>/Monat +1% provision</button>
                        <button onclick="selectDeliveryPay('0','0','39','0')" type="button" id="addNCon_Delivery2" style="width:49.75%;" class="btn serviceBtn mt-1">39 <sub>CHF</sub>/Monat</button>
                        <button onclick="selectDeliveryPay('0','0','0','7')" type="button" id="addNCon_Delivery3" style="width:49.75%;" class="btn serviceBtn mt-1">7%</button>

                        <input type="hidden" id="addNCon_TakeawayPerMonth" name="addNCon_TakeawayPerMonth" value="0">
                        <input type="hidden" id="addNCon_TakeawayProvision" name="addNCon_TakeawayProvision" value="0">
                        <input type="hidden" id="addNCon_TakeawayFixedPerMonth" name="addNCon_TakeawayFixedPerMonth" value="0">
                        <input type="hidden" id="addNCon_TakeawayPercentage" name="addNCon_TakeawayPercentage" value="0">

                        <input type="hidden" id="addNCon_DeliveryPerMonth" name="addNCon_DeliveryPerMonth" value="0">
                        <input type="hidden" id="addNCon_DeliveryProvision" name="addNCon_DeliveryProvision" value="0">
                        <input type="hidden" id="addNCon_DeliveryFixedPerMonth" name="addNCon_DeliveryFixedPerMonth" value="0">
                        <input type="hidden" id="addNCon_DeliveryPercentage" name="addNCon_DeliveryPercentage" value="0">

                        <button type="button" id="addNCon_Tischreservierung" onclick="addTischreservierung('29')"  style="width:100%;" class="btn serviceBtn mt-2">Tischreservierung +29 <sub>CHF</sub>/Monat</button>
                        <button type="button" id="addNCon_Warenwirtschaft" onclick="addWarenwirtschaft('49')"  style="width:100%;" class="btn serviceBtn mt-2">Warenwirtschaft +49 <sub>CHF</sub>/Monat</button>
                        <button type="button" id="addNCon_Personalvertretung" onclick="addPersonalvertretung('19')"  style="width:100%;" class="btn serviceBtn mt-2">Personalverwaltung +19 <sub>CHF</sub>/Monat</button>
                    
                        <input type="hidden" id="addNCon_TischreservierungPerMonth" name="addNCon_TischreservierungPerMonth" value="0">
                        <input type="hidden" id="addNCon_WarenwirtschaftPerMonth" name="addNCon_WarenwirtschaftPerMonth" value="0">
                        <input type="hidden" id="addNCon_PersonalvertretungPerMonth" name="addNCon_PersonalvertretungPerMonth" value="0">
                    </div>

                    <hr>

                    <div class="d-flex flex-wrap justify-content-between mt-2">
                        <p style="width: 100%; color:rgb(39,190,175);"><strong>Vertragslaufzeit</strong></p>
                        <button type="button" onclick="selectVertragsaufzeitPay('1','0')" id="addNCon_Vertragsaufzeit1" style="width:49%;" class="btn serviceBtnSelected"> 1 Jahresvertrag <strong>(Kein Rabatt)</strong></button>
                        <button type="button" onclick="selectVertragsaufzeitPay('2','10')" id="addNCon_Vertragsaufzeit2" style="width:49%;" class="btn serviceBtn"> 2 Jahresvertrag <strong>(10% günstiger)</strong></button>
                        <button type="button" onclick="selectVertragsaufzeitPay('3','15')" id="addNCon_Vertragsaufzeit3" style="width:49%;" class="btn mt-2 serviceBtn"> 3 Jahresvertrag <strong>(15% günstiger)</strong></button>
                        <button type="button" onclick="selectVertragsaufzeitPay('5','25')" id="addNCon_Vertragsaufzeit5" style="width:49%;" class="btn mt-2 serviceBtn"> 5 Jahresvertrag <strong>(25% günstiger)</strong></button>

                        <input type="hidden" value="0" id="addNCon_VertragsaufzeitPercentage" name="addNCon_VertragsaufzeitPercentage">
                        <input type="hidden" value="1" id="addNCon_VertragsaufzeitYear" name="addNCon_VertragsaufzeitYear">
                    </div>

                    <hr>

                    <div class="d-flex flex-wrap justify-content-between mt-2">
                        <div class="input-group" style="width: 100%; font-weight:bold;">
                            <div style="width:28%;" class="input-group-prepend">
                                <span style="width: 100%;" class="input-group-text">Pro Monat</span>
                            </div>
                            <input type="number" class="form-control shadow-none text-right pr-3" style="font-weight: bold;" value="0" id="addNCon_totalPerMonth" name="addNCon_totalPerMonth" disabled>
                            <input type="hidden" value="0" id="totalPerMonth" name="totalPerMonth">
                            <div style="width:15%;" class="input-group-append">
                                <span style="width: 100%;" class="input-group-text">CHF</span>
                            </div>
                        </div>
                        <div class="input-group mt-1" style="width: 100%; font-weight:bold; display:none;">
                            <div style="width:28%;" class="input-group-prepend">
                                <span style="width: 100%;" class="input-group-text">Total</span>
                            </div>
                            <input type="number" class="form-control shadow-none text-right pr-3" style="font-weight: bold; " value="0" id="addNCon_total" name="addNCon_total" disabled>
                        
                            <div style="width:15%;" class="input-group-append">
                                <span style="width: 100%;" class="input-group-text">CHF</span>
                            </div>
                        </div>
                        <input type="hidden" value="0" id="total" name="total">

                        <div class="input-group mt-1" style="width: 100%; font-weight:bold;">
                            <div style="width:28%;" class="input-group-prepend">
                                <span style="width: 100%;" class="input-group-text">Am Tisch</span>
                            </div>
                            <input type="number" class="form-control shadow-none text-right pr-3" style="font-weight: bold;" value="0" id="addNCon_tablesPercentageTOT" name="addNCon_tablesPercentageTOT" disabled>
                            <input type="hidden" value="0" id="tablesPercentageTOT" name="tablesPercentageTOT">
                            <div style="width:15%;" class="input-group-append">
                                <span style="width: 100%;" class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="input-group mt-1" style="width: 100%; font-weight:bold;">
                            <div style="width:28%;" class="input-group-prepend">
                                <span style="width: 100%;" class="input-group-text">Takeaway</span>
                            </div>
                            <input type="number" class="form-control shadow-none text-right pr-3" style="font-weight: bold;" value="0" id="addNCon_takeawayPercentageTOT" name="addNCon_takeawayPercentageTOT" disabled>
                            <input type="hidden" value="0" id="takeawayPercentageTOT" name="takeawayPercentageTOT">
                            <div style="width:15%;" class="input-group-append">
                                <span style="width: 100%;" class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="input-group mt-1" style="width: 100%; font-weight:bold;">
                            <div style="width:28%;" class="input-group-prepend">
                                <span style="width: 100%;" class="input-group-text">Delivery</span>
                            </div>
                            <input type="number" class="form-control shadow-none text-right pr-3" style="font-weight: bold;" value="0" id="addNCon_DeliveryPercentageTOT" name="addNCon_DeliveryPercentageTOT" disabled>
                            <input type="hidden" value="0" id="DeliveryPercentageTOT" name="DeliveryPercentageTOT">
                            <div style="width:15%;" class="input-group-append">
                                <span style="width: 100%;" class="input-group-text">%</span>
                            </div>
                        </div>
                    </div>

                    <hr>


                    <div class="d-flex mt-2">
                        <div class="card" style="width: 100%;">
                            <div class="card-header">
                                <strong>Kosten pro Transaktion</strong>
                            </div>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex" style="padding:2px;">
                                    <p style="width:100%; margin:3px;" class="pl-3">SMS-Gateway Bestellungen verifizieren :
                                        <br> <strong>CHF 0.10.- pro SMS</strong>
                                    </p>
                                </li>
                                <!-- <li class="list-group-item d-flex" style="padding:2px;">
                                    <p style="width:100%; margin:3px;" class="pl-3">Online-Zahlungen: Twint
                                        <br> <strong>1.4% + CHF 0.28.- pro Transaktion</strong>
                                    </p>
                                </li>
                                <li class="list-group-item d-flex" style="padding:2px;">
                                    <p style="width:100%; margin:3px;" class="pl-3">Online-Zahlungen: PostFinance Card
                                        <br> <strong>1.9% + CHF 0.28.- pro Transaktion</strong>
                                    </p>
                                </li>
                                <li class="list-group-item d-flex" style="padding:2px;">
                                    <p style="width:100%; margin:3px;" class="pl-3">Online-Zahlungen: PostFinance E-Service
                                        <br> <strong>1.9% + CHF 0.28.- pro Transaktion</strong>
                                    </p>
                                </li>
                                <li class="list-group-item d-flex" style="padding:2px;">
                                    <p style="width:100%; margin:3px;" class="pl-3">Online-Zahlungen: MasterCard
                                        <br> <strong>1.9% + CHF 0.28.- pro Transaktion</strong>
                                    </p>
                                </li>
                                <li class="list-group-item d-flex" style="padding:2px;">
                                    <p style="width:100%; margin:3px;" class="pl-3">Online-Zahlungen: Visa
                                        <br> <strong>1.9% + CHF 0.28.- pro Transaktion</strong>
                                    </p>
                                </li> -->
                            </ul>
                        </div>
                    </div>

                    <hr>
                    
                    <div class="d-flex flex-wrap justify-content-between mt-2">
                        <p style="color: rgb(39,190,175);"><strong> Menükarte/Getränkekarte hochladen: </strong></p>
                        <div style="margin-top:-15px;" class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" onchange="resAddNConMenuFile()" id="addNCon_menuFile" name="addNCon_menuFile">
                                <label id="addNCon_menuFile_label" class="custom-file-label" for="addNCon_menuFile">Datei</label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap justify-content-between mt-2">
                        <p style="color: rgb(39,190,175);"><strong> UID hochladen: </strong></p>
                        <div style="margin-top:-15px;" class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" onchange="resAddNConUIDFile()" id="addNCon_UIDFile" name="addNCon_UIDFile">
                                <label id="addNCon_UIDFile_label" class="custom-file-label" for="addNCon_UIDFile">Datei</label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap justify-content-between mt-2">
                        <p style="color: rgb(39,190,175);"><strong> Pass/ID hochladen: </strong></p>
                        <div style="margin-top:-15px;" class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" onchange="resAddNConPassIDFile()" id="addNCon_PassIDFile" name="addNCon_PassIDFile">
                                <label id="addNCon_PassIDFile_label" class="custom-file-label" for="addNCon_PassIDFile">Datei</label>
                            </div>
                        </div>
                    </div>

                    <script>
                        function resAddNConMenuFile(){
                            var i = $(this).prev('label').clone();
                            var file = $('#addNCon_menuFile')[0].files[0].name;
                            $('#addNCon_menuFile_label').html(file);
                        }
                        function resAddNConUIDFile(){
                            var i = $(this).prev('label').clone();
                            var file = $('#addNCon_UIDFile')[0].files[0].name;
                            $('#addNCon_UIDFile_label').html(file);
                        }
                        function resAddNConPassIDFile(){
                            var i = $(this).prev('label').clone();
                            var file = $('#addNCon_PassIDFile')[0].files[0].name;
                            $('#addNCon_PassIDFile_label').html(file);
                        }
                    </script>

                    <hr>

                    <div class="d-flex flex-wrap justify-content-between mt-2">
                        <div class="form-group" style="width: 100%;">
                            <label for="addNCon_theComment"><strong>Bemerkungen:</strong></label>
                            <textarea class="form-control shadow-none" id="addNCon_theComment" name="addNCon_theComment" rows="3"></textarea>
                        </div>

                        <div style="width: 100%;">

                            <div class="form-check mt-2" style="width: 100%;" onclick="acceptAGBetc()">
                                <input type="checkbox" class="form-check-input" id="addNCon_acceptAGBetc">
                                <label class="form-check-label" for="addNCon_acceptAGBetc">Die Vertragsparteien anerkennen mit ihrer Unterschrift auch die AGB „
                                    Allgemeinen Geschäftsbedingungen“ von Kreative Idee, welche integrierender Bestandteil dieses Vertrages sind und welche ausdrücklich 
                                    zur Kenntnis genommen wurden.
                                </label>
                            </div>
                            
                            <div class="input-group mt-2">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="addNCon_ortOnTheEnd">ORT</span>
                                </div>
                                <input type="text" name="addNCon_ortOnTheEnd" class="form-control shadow-none" >
                            </div>
                            <div class="input-group mt-2">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="addNCon_dateOnTheEnd">Datum</span>
                                </div>
                                <input type="date" name="addNCon_dateOnTheEnd" class="form-control shadow-none" >
                            </div>
                        </div>
                        <div style="width: 100%;" class="mt-2 d-flex justify-content-between" id="signatureDiv">
                 
                            <div class="form-group d-flex flex-wrap justify-content-between" style="width: 100%;">
                           
                                <img src="" id="signaturePrew" alt="Unterschreiben und speichern" style="width: 100%; height: 160px;">
                                <textarea id="signature64" name="signed" style="display: none;" required></textarea>

                                <button type="button" class="btn btn-primary" style="width: 100%;" data-toggle="modal" data-target="#signatureMOdal">
                                    Unterschrift
                                </button>
                            </div>  
                        </div>

                        <div style="width: 100%; display:none;" id="addNCon_error04" class="mt-1 text-center alert alert-danger">
                            <strong>Wir brauchen Ihre Unterschrift!</strong>
                        </div>
                   
                    </div>

                    <hr>

                    <input type="hidden" value="{{Auth::User()->id}}" id="addNCon_fromConMng" name="addNCon_fromConMng">

                    <div class="mt-2">
                        <button type="button" onclick="submitAddNewContractFun()" id="submitAddNewContract" style="font-size: 25px;" class="btn btn-success btn-block p-2" disabled>
                            <strong>Speichern</strong>
                        </button>
                    </div>
                    
                </form>
            </div>
        </div>
    </div>
</div>



<!-- signature Modal  -->
<div class="modal" id="signatureMOdal" tabindex="-1" role="dialog" backdrop="true" aria-labelledby="exampleModalLabel" aria-hidden="true"
    style="background-color: rgba(0, 0, 0, 0.5); padding-top:200px;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 style="color:rgb(39,190,175);" class="modal-title" id="exampleModalLabel">
                    <strong>Schreiben Sie bitte Ihre Unterschrift (<span id="signatureMOdalAttempt"> Versuch 1/5 </span>)</strong>
                </h5>
                <button type="button" class="close" onclick="closesignatureMOdal()"  aria-label="Close"> <i class="far fa-times-circle"></i> </button>
            </div>
            <div class="modal-body text-center d-flex flex-wrap justify-content-between">
                <div style="border:1px solid rgb(72,81,87); width:362px; height:160px;" id="signaturePad"></div>

                <div style="border:1px solid rgb(72,81,87); width:362px; height:160px; display:none;" id="signaturePad2"></div>

                <div style="border:1px solid rgb(72,81,87); width:362px; height:160px; display:none;" id="signaturePad3"></div>

                <div style="border:1px solid rgb(72,81,87); width:362px; height:160px; display:none;" id="signaturePad4"></div>

                <div style="border:1px solid rgb(72,81,87); width:362px; height:160px; display:none;" id="signaturePad5"></div>


                <button type="button" id="clear" class="btn btn-danger mt-2" style="width:45%;">Abbrechen</button>
                <button type="button" id="saveSignature" onclick="clickSaveSignature()" class="btn btn-info mt-2" style="width:45%;"><i class="fas fa-signature mr-2"></i> Sparen</button>
                                   
            </div>
        </div>
    </div>
</div>

