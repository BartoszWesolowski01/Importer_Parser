<?php
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="Import.csv"');
    libxml_use_internal_errors(true);

    $html = new DOMDocument();

    $html->loadHtmlFile('wo_for_parse.html');

    $shtml = simplexml_import_dom($html);

    $items = $shtml->xpath('body/table/tr/td/table/tbody/tr/td/table/tbody/tr/td/table/tbody/tr/td/h3
                            [@id="wo_number" or 
                            @id="po_number" or
                            @id="customer" or 
                            @id="trade"]');

    $sheduled_date = $shtml->xpath('//*[@id="scheduled_date"]');

    $nte = array(floatval(preg_replace('/\D/', '', (string)$shtml->xpath('//*[@id="nte"]')[0])));

    $date = new DateTime($sheduled_date[0]);
    $time = new DateTime($sheduled_date[0]->span);

    $date->setTime($time->format('H'), $time->format('i'));
    $sheduled_date = array($date->format('Y-m-d H:i'));

    $store_id = $shtml->xpath('//*[@id="location_name"]');
                            
    $city = $shtml->xpath('//*[@id="store_id"]');

    $address = (string)$shtml->xpath('//*[@id="location_address"]')[0]->a;

    $street = array(explode("\n", $address)[1]);
    $state_number =  array(preg_replace('/[0-9]+/', '', str_replace($city[0], "", explode("\n", $address)[2])));
    $postal_code = array(filter_var(explode("\n", $address)[2], FILTER_SANITIZE_NUMBER_INT));

    $phone = array(floatval(str_replace("-", "", (string)$shtml->xpath('//*[@id="location_phone"]')[0])));

    $merged_array = array_merge($nte, $sheduled_date, $store_id, $city, $street, $state_number, $postal_code, $phone);
    $items = array_merge($items, $merged_array);

    $trimmed_array = array_map('trim', $items);
    $list = array (
        array("Customer", "Trade", "PO Number", "Tracking Number", "NTE", "Scheduled Date", "Store ID", "City", "Street", "State", "Postal Code", "Phone Number"),
        $trimmed_array
    );
    
    $fp = fopen('php://output', 'wb');
    foreach ($list as $line) {
        fputcsv($fp, $line, ',');
    }
    fclose($fp);
?>