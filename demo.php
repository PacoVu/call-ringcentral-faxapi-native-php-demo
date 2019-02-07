<?php
include('ringcentral.php');

$rc = new RingCentral();

function download_fax_attachment($response){
    global $rc;
    $json = json_decode($response);
    foreach ($json->records as $record){
      if ($record->attachments){
        print ($record->attachments[0]->uri);
        $rc->download_attachment($record->attachments[0]->uri, $record->attachments[0]->id, $record->attachments[0]->contentType);
      }
    }
}

function read_messagestore(){
    global $rc;
    try{
        $endpoint = "/restapi/v1.0/account/~/extension/~/message-store";
        $params = array('dateFrom' => "2019-01-01",
                        'messageType' => "Fax"
                      );
        $rc->get($endpoint, $params, 'download_fax_attachment');
    }catch (Exception $e) {
        print $e->getMessage();
    }
}

read_messagestore();
