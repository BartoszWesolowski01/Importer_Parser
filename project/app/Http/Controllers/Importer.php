<?php
namespace App\Http\Controllers;

libxml_use_internal_errors(true);
error_reporting(0); 
ini_set('display_errors', 0);

use Illuminate\Http\Request;
use App\Models\Importer_Log;
use App\Models\Work_Order;

class Importer extends Controller
{
    public function showLog(){
        return view("importer", ["Logs"=>Importer_Log::All()]);
    }

    public function importData(Request $request){
        $file = $request->file->path();
        $html = new \DOMDocument();

        $html->loadHTMLFile($file);
    
        $shtml = simplexml_import_dom($html);

        $Ticket = $shtml->xpath('//tr[@class="rgRow" or @class="rgAltRow"]/td/a');
        $Urgancy = $shtml->xpath('//tr[@class="rgRow" or @class="rgAltRow"]/td[4]');
        $RcvdDate = $shtml->xpath('//tr[@class="rgRow" or @class="rgAltRow"]//span[contains(@id,"grdRcvdDate")]');
        $Category = $shtml->xpath('//tr[@class="rgRow" or @class="rgAltRow"]/td[9]');
        $StoreName = $shtml->xpath('//tr[@class="rgRow" or @class="rgAltRow"]/td[11]');
        $StoreName2 = $shtml->xpath('//div[@id="ContentPlaceHolderMain_MainContent_TicketLists_RadPageView2"]//tr[@class="rgRow" or @class="rgAltRow"]/td[8]');
        
        $StoreNames = array_merge($StoreName, $StoreName2);

       $this->handleData($Ticket, $Urgancy, $RcvdDate, $Category, $StoreNames);
    }


    public function handleData($Ticket, $Urgancy, $RcvdDate, $Category, $StoreNames){
        $entities = [];
   
        for($entity = 0; $entity < count($Ticket); $entity++){
            array_push($entities, (["Ticket" => (string)$Ticket[$entity], "EntityID" => str_replace("Ticket.aspx?entityid=", "", (string)$Ticket[$entity]['href']),
                                    "Urgancy" => (string)$Urgancy[$entity], "RcvdDate" => date("Y-m-d", strtotime((string)$RcvdDate[$entity]->span)),
                                    "Category" => (string)$Category[$entity], "StoreName" => (string)$StoreNames[$entity]]));
        }

        $this->addEntitiesToDataBase($entities);
    }

    public function addEntitiesToDataBase($entities){

        $Entries_Processed = 0;
        $Entries_Created = 0;

        foreach($entities as $entity){
            $Work_Order = Work_Order::firstOrCreate([
                'work_order_number' => $entity['Ticket'],
                'external_id' => $entity['EntityID'],
                'priority' => $entity['Urgancy'],
                'received_date' => $entity['RcvdDate'],
                'category' => $entity['Category'],
                'fin_loc' => $entity['StoreName']
            ]);
            

            if($Work_Order->wasRecentlyCreated){
                $Entries_Created++;
                $Entries_Processed++;
            }
            else
                $Entries_Processed++;


            $result = ($Work_Order->wasRecentlyCreated) ? "Created" : "Skipped";

            $this->createCSVReport([$entity['Ticket'], $entity['EntityID'], $entity['Urgancy'], $entity['RcvdDate'], 
                                    $entity['Category'], $entity['StoreName'], $result]);
        }

        $this->makeLog($Entries_Created, $Entries_Processed);
    }

    public function createCSVReport($entitites){

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="Report.csv"');

        $list = array (
            array("Work Order Nmber", "External ID", "Priority", "Received Date", "Category", "Store Name", "Result"),
            $entitites
        );
        
        $fp = fopen('php://output', 'wb');
        foreach ($list as $line) {
            fputcsv($fp, $line, ',');
        }

        fclose($fp);
    }

    public function makeLog($Entries_Created, $Entries_Processed){
      
        Importer_Log::Create([
        'entries_processed' => $Entries_Processed,
        'entries_created' => $Entries_Created
        ]);

        redirect()->route('Importer.showLog');
    }
}
