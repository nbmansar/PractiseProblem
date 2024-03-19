
<?php
class Collection_ManageCollection_Model extends Vtiger_Base_Model {

        public function getModule() {
                return $this->get('module');
        }

        public function getInformation($request){
                global $adb,$current_user,$extNumber;
                if($extNumber){
                        $extNumber = $extNumber."*";
                }else{
                        $extNumber = "0399*";
                }
                $searchParams=self::searchParams($request);
                if($request->get('is_export')==''){
                        $pagenumber=$request->get('page');
                        $pagelimit=10;
                        if(empty($pagenumber))
                        {
                                $pagenumber=0;
                        }
                        else
                        {
                                $pagenumber=($pagenumber-1)*$pagelimit;
                        }
                        $limitation="limit ".$pagenumber.",".$pagelimit."";
                }

                $userModel=Users_Record_Model::getInstanceById($current_user->id,'Users');
                $extn[] =str_replace($extNumber,'',$userModel->get('phone_crm_extension'));
                $extn[] =str_replace($extNumber,'', $userModel->get('phone_crm_extension2'));
                $extn[] =str_replace($extNumber,'', $userModel->get('phone_crm_extension3'));
                $extension = array_filter($extn);


                $moduleName = 'Accounts';
                $instance = CRMEntity::getInstance($moduleName);
                $nonAdminQuery = $instance->getNonAdminAccessControlQueryWhereCond($moduleName,$current_user);


                if($current_user->is_admin != 'on'){
                        $nonAdminQuery = str_replace('vtiger_crmentity','crm',$nonAdminQuery);
                        $nonAdminQuery = str_replace('vtiger_account','va',$nonAdminQuery);

                        $NonAdmin = $nonAdminQuery;
                }

                if($request->get('sort_type')==''){
                        if($request->get('sort_status') =='create'){
                                $sortCond="order by vc.start_date desc";
                        }elseif($request->get('sort_status') =='last'){
                                $sortCond="order by vc.last_reject_date desc";
                        }
                }else{
                        if($request->get('sort_column')=='users'){
                                $sortCond="order by usr.first_name asc,usr.last_name " .$request->get('sort_type') ;
                        }else if($request->get('sort_column')=='collection_company'){
                                $sortCond="order by vc.".$request->get('sort_column')." ".$request->get('sort_type');
                        }else{
                                $sortCond="order by ".$request->get('sort_column')." ".$request->get('sort_type');
                        }
                }
                $CUJoin = "";
                $Col_From_Join = "FROM collection_dues vc";
                if($request->get('collection_status') == 'charge off'){
                        $CUJoin = "LEFT JOIN Collection_updates cu ON cu.mid = vc.MID";
                        $Col_From_Join = " FROM (SELECT max(cd.DID) as 'did' FROM collection_dues cd where cd.deleted = '0' group by cd.MID ) a inner join collection_dues vc on a.did = vc.DID";
                }
                if($request->get('collection_api_status')=='submitted'){
                        $res_join="LEFT JOIN collection_response_details rd ON vc.mid=rd.mid AND rd.collection_status='success'";
                }

                        $sql="SELECT vc.MID, sum(vc.amount) as total_amount,vas.cf_1729 AS 'merchant_legal_name', collection_balances.updated_balance,
                        va.accountname AS 'Merchant_DBA_Name',
                        vas.cf_1723 AS 'Street_Address',va.phone,
                        vas.cf_1725 AS City, vas.cf_1733 AS State, vas .cf_1721 AS ZIP,vas.cf_780 as contact_name,
                        va.phone, va.fax, va.email1,
                        vas.cf_780 AS 'Names',va.ssn,vc.last_reject_date,va.last_reject_date as account_last_reject_date,
                        va.service_type,
                        vc.notes,
                        va.last_trans_date,
                        min(vc.start_date) AS created_date,vc.collection_company,
                        va.accountid as 'record_id',va.collection_api_status,
                        vc.payment_status,CONCAT (vtiger_users.first_name,' ', vtiger_users.last_name,' (',vtiger_users.agent_code,') ') AS 'assigned_to',
                        cf_816 AS 'processor',collection_balances.collection_status as collection_status, MAX(vc.DID) AS did,cf_1709 as 'approved_date'
                        FROM collection_dues vc
                        LEFT JOIN vtiger_accountscf vas ON vc.MID= vas.cf_1757
                        LEFT JOIN vtiger_account va ON va.accountid= vas.accountid
                        LEFT JOIN vtiger_crmentity crm ON crm.crmid=va.accountid
                        LEFT JOIN vtiger_users ON vtiger_users.id=crm.smownerid
                        left join vtiger_user2role as u2r on u2r.userid=vtiger_users.id
                        LEFT JOIN collection_balances ON vc.mid = collection_balances.mid
                        LEFT JOIN collection_response_details rd ON vc.mid=rd.mid AND rd.collection_status='success'
                        WHERE vc.deleted='0' AND vc.`charge_off`!='Y'   AND vc.mid!='' $searchParams $NonAdmin
                        GROUP BY vc.MID $sortCond";
                $rs= $adb->pquery($sql);
                $CollectionReportData = Array();
                if($adb->num_rows($rs)>0){
                        while( $data=$adb->fetchByAssoc($rs)){

                                        $ticket_details=self::CheckTicket($data['record_id']);
                                        $data['close_mid']=self::ClosedMidCheck($data['record_id']);
                                        $comment=self::GetCommentContent($data['record_id']);
                                        $data['commentcontent']=$comment['commentcontent'];
                                        $data['cmt_user']=$comment['cmt_user'];
                                        $data['createdtime']=$comment['createdtime'];
                                        $cmt = html_entity_decode($data['commentcontent'], ENT_QUOTES);
                                        $data['Merchant_DBA_Name'] = $data['merchant_dba_name'];
                                       $data['MID'] = $data['mid'];
                                        if($cmt){
                                                $full = $cmt;
                                                $shortt =substr($full,0,40);
                                                $cmtlength=strlen($full);
                                                if($cmtlength >40 && stripos($full, '<a') === false){
                                                        $shortt = $shortt."...";
                                                }else{
                                                        $shortt = $full;
                                                }
                                        }else{
                                                $full = '';
                                                $shortt = '';
                                        }

                                        $data['commentcontent']=array('full'=>$full,'shortt'=>$shortt);


                                        if(strtolower($data['service_type'])=='ms_block'){
                                                if( strtolower($data['processor']) == 'firstdata'){
                                                        $last_reject_date=$data['account_last_reject_date'];
                                                        $data['last_reject_date']=($last_reject_date ? date('m/d/Y', strtotime($last_reject_date)) : '');

                                                }elseif(strtolower($data['processor']) == 'tsys'){
                                                        $last_reject_date=$data['account_last_reject_date'];
                                                        $data['last_reject_date']=($last_reject_date ? date('m/d/Y', strtotime($last_reject_date)) : '');
                                                }

                                        }else if ($data['service_type']=='mca_block'){
                                                $data['last_reject_date']= ($data['pmtdate'] ? date('m/d/Y', strtotime($data['pmtdate'])) : '');
                                        }
                                        $data['approved_date']= ($data['approved_date'] ? date('m/d/Y', strtotime($data['approved_date'])) : '');



                                        $ticket_status=$ticket_details['ticket_status'];
                                        if($ticket_details['status']){
                                                $data['ticketid']=$ticket_details['data'][0]['ticketid'];
                                                $data['completed_percent']=$ticket_details['data'][0]['completed_percent'];
                                                $data['recent_ticket_on'] = $ticket_details['data'][0]['createdtime'];
                                        }else{
                                                $data['completed_percent']=0;
                                                $data['recent_ticket_on'] = '';

                                        }

                                        $data['created_date']= ($data['created_date'] ? date('m/d/Y', strtotime($data['created_date'])) : '');



                                        $data['ticket_status']=$ticket_status;

                                        if($ticket_status=='open'){
                                                $openRecord[]=$data;
                                                $open_account[] = $data['record_id'];

                                        }else{
                                                $closedRecord[]=$data;
                                                $close_account[] = $data['record_id'];

                                        }
                                        $status=true;
                        }
                }else{
                        $CollectionReportData='';
                        $status=false;
                        $CollectionReportData=array('message'=>'No Record Found');
                }
                if($request->get('ticket_status')=='Open'){
                        $CollectionReportData= $openRecord;
                        $total_account= $open_account;
                }else if($request->get('ticket_status')=='Closed'){
                        $CollectionReportData= $closedRecord;
                        $total_account= $close_account;

                }else{
                        $CollectionReportData= array();$total_account= array();
                        if(is_array($openRecord))
                                $CollectionReportData= array_merge($CollectionReportData,$openRecord);
                        $total_account= array_merge($total_account,$open_account);
                        if(is_array($closedRecord))
                                $CollectionReportData= array_merge($CollectionReportData,$closedRecord);
                        $total_account= array_merge($total_account,$close_account);

                }

                /* if($request->get('sort_type')==''){
                        $CollectionReportData  =  Vtiger_CommonActions_Action::array_sort_on_key($CollectionReportData,'completed_percent',SORT_ASC);
                    }
                */
                /*foreach($CollectionReportData as $key=>$value){
                        if($value['last_reject_date'] != ''){
                                $CollectionReportData[$key]['last_reject_date'] = date('Y-m-d', strtotime($value['last_reject_date']));
                        }
                        if($value['created_date'] != ''){
                                $CollectionReportData[$key]['created_date'] = date('Y-m-d', strtotime($value['created_date']));
                        }
                }

                if($request->get('sort_status') =='create'){
                        $CollectionReportData = self::array_sort_onDate_key($CollectionReportData,'created_date',SORT_DESC);
                }elseif($request->get('sort_status') =='last'){
                        $CollectionReportData = self::array_sort_onDate_key($CollectionReportData,'last_reject_date',SORT_DESC);
                }

                foreach($CollectionReportData as $key=>$value){
                        if($value['last_reject_date'] != ''){
                                $CollectionReportData[$key]['last_reject_date'] = date('m/d/Y', strtotime($value['last_reject_date']));
                        }
                        if($value['created_date'] != ''){
                                $CollectionReportData[$key]['created_date'] = date('m/d/Y', strtotime($value['created_date']));
                        }
                }*/

                $user = $_SESSION['AUTHUSERID'];
                unset($_REQUEST['module']);
                unset($_REQUEST['view']);
                unset($_REQUEST['mode']);
                unset($_REQUEST['relmodule']);
                $filter_condition=json_encode($_REQUEST);
                $exportHistory ="insert into collection_search_history (search_by,search_date,filter_condition) values('$user',now(),'$filter_condition')";
                $adb->pquery($exportHistory);


                return  ['content'=>$CollectionReportData,
                        'status'=>$status,
                        'extension'=>$extension,
                        'totalData'=>count($CollectionReportData),
                        'total_account' => json_encode($total_account)

                ];
        }



        public function  GetCommentContent($accountid){
                global $adb;
                $sql="SELECT cmt.commentcontent,cmt.related_to, CONCAT(cmt_user.first_name,' ',cmt_user.last_name) AS cmt_user,crm_cmt.createdtime
                        FROM vtiger_modcomments AS cmt
                        INNER JOIN vtiger_crmentity AS crm_cmt ON crm_cmt.crmid=cmt.modcommentsid
                        INNER JOIN vtiger_users AS cmt_user ON cmt_user.id=crm_cmt.smcreatorid
                        WHERE crm_cmt.deleted=0 and related_to='$accountid'
                        ORDER BY cmt.modcommentsid Desc limit 1";
                $recordRs = $adb->pquery($sql) ;
                if($adb->num_rows($recordRs) > 0){
                        $row=$adb->fetchByAssoc($recordRs);
                        $status=$row;
                }else{
                        $status='';
                }
                return $status;

        }


         public  function ClosedMidCheck($accountid){
                global $adb;

                $sql="select vt.ticketid,vt.status as status from vtiger_troubletickets vt
                        left join vtiger_crmentity crm on crm.crmid=vt.ticketid
                        where crm.deleted=0 and vt.reasonforcall='Close Mid' and vt.parent_id='$accountid' order by crm.createdtime desc limit 1";
                $recordRs = $adb->pquery($sql) ;
                if($adb->num_rows($recordRs) > 0 ){
                        $row=$adb->fetchByAssoc($recordRs);
                          $ticketid= $row['ticketid'];
                          $ticketstatus= $row['status'];
                          $result=array('ticketid'=>$ticketid,'status'=>$ticketstatus);
                }else{
                        $result='no';
                }
                return $result;

        }


        public function getExportCumulativeTicketData($accountid){
                require_once("libraries/PHPExcel/PHPExcel.php");

                global $adb,$current_user;
                $accountid = implode(',',$accountid);
                $ticket_sql = "SELECT va.accountname ,va2.cf_1757, vt.status,vt.total_amount,concat(vu.first_name,' ',vu.last_name) as 'created by',vc.createdtime as 'createdtime',
                        CASE
                                WHEN cast(vt.closed_by AS UNSIGNED) THEN concat(vu2.first_name,' ',vu2.last_name)
                                ELSE vt.closed_by
                                END as 'closed_by',
                        vt.ticket_closed_on as 'ticket_closed_on'
                                FROM vtiger_troubletickets vt
                                inner join vtiger_crmentity vc on vc.crmid = vt.ticketid
                                inner join vtiger_users vu on vu.id = vc.smcreatorid
                                left join vtiger_users vu2 on vt.closed_by = vu2.id
                                inner join vtiger_account va on va.accountid = vt.parent_id
                                inner join vtiger_accountscf va2 on va2.accountid = va.accountid
                                WHERE vt.reasonforcall = 'Collections' and va.accountid IN ($accountid) and vc.deleted = 0 ORDER by vc.modifiedtime DESC";
                $ticket_res = $adb->pquery($ticket_sql);
                while($ticket_rs = $adb->fetchByAssoc($ticket_res)){
                        if($ticket_rs['createdtime'] != ''){
                                $ticket_rs['createdtime'] = PHPExcel_Shared_Date::stringToExcel(date('Y-m-d H:i:s',strtotime($ticket_rs['createdtime'])));
                        }
                        if($ticket_rs['ticket_closed_on'] != ''){
                                $ticket_rs['ticket_closed_on'] = PHPExcel_Shared_Date::stringToExcel(date('Y-m-d H:i:s',strtotime($ticket_rs['ticket_closed_on'])));
                        }
                        $Data[] = $ticket_rs;
                }
                $Header = array('DBA NAME','MID','TICKET STATUS','COLLECTION AMOUNT','CREATED BY','CREATED ON','CLOSED BY','CLOSED ON');
                $Content['header'] = $Header;
                $Content['data'] = $Data;
                return $Content;
        }
        public function getExportCumulativeTicket($accountid){
                global $root_directory ,$site_URL;
                require_once 'libraries/PHPExcel/PHPExcel.php';
                require_once ('libraries/PHPExcel/export_files.php');

                $Data = self::getExportCumulativeTicketData($accountid);
                $php_excel_data['Header']= array_values($Data['header']);
                $php_excel_data['File_Data']=$Data['data'];
                $php_excel_data['File_Name']='Cumulative_Ticket_Report';
                $php_excel_data['Export_Type']='xls';
                $php_excel_data['Cell_Format'] = array('MID' => '#','COLLECTION AMOUNT'=> '[$$-409]#,##0.00;[RED]-[$$-409]#,##0.00','CREATED ON' => 'mm/dd/yyyy h:mm AM/PM','CLOSED ON' => 'mm/dd/yyyy h:mm AM/PM');
                $php_excel_data['Xls_Autofilter']=1;
                $php_excel_data['Header_Color']='b9f7ef';
                $php_excel_data['DirectDownload']='';

                $fileurl=Export_File::Export($php_excel_data);
                return 'downloader.php?force='.$fileurl;

        }
        public function GetCollectionAchCsv($request){

                global $root_directory ,$site_URL;

                require_once 'libraries/PHPExcel/PHPExcel.php';
                require_once ('libraries/PHPExcel/export_files.php');

                $Data=self::CollectionAchData( $request ) ;

                $php_excel_data['Header']= $Data['header'];
                $php_excel_data['File_Data']=$Data['data'];
                $php_excel_data['File_Name']='Ach_Collection';
                $php_excel_data['Export_Type']='csv';
                $php_excel_data['Xls_Autofilter']=1;
                $php_excel_data['Header_Color']='b9f7ef';
                $php_excel_data['DirectDownload']='';

                $fileurl=Export_File::Export($php_excel_data);
                return 'downloader.php?force='.$fileurl;

        }

        public function CollectionAchData($request){

                global $adb;
                $sql="SELECT vc.MID, sum(vc.amount) as amount,vas.cf_1898 AS 'routing_transit_number', vas.cf_1900 as 'account_number' ,vas.cf_2042 as account_type,
                        va.accountname
                        FROM collection_dues vc
                                LEFT JOIN vtiger_accountscf vas ON vc.MID= vas.cf_1757
                                LEFT JOIN vtiger_account va ON va.accountid= vas.accountid
                                LEFT JOIN vtiger_crmentity crm ON crm.crmid=va.accountid
                                LEFT JOIN collection_balances ON vc.mid = collection_balances.mid
                                LEFT JOIN vtiger_mca ON vtiger_mca.mcaid=vc.MID
                                LEFT JOIN vtiger_mcacf ON vtiger_mcacf.mcaid=vtiger_mca.mcaid
                                LEFT JOIN vtiger_crmentity crm_mca ON crm_mca.crmid=vtiger_mca.mcaid
                                LEFT JOIN vtiger_users vuser ON vuser.id=crm_mca.smownerid
                                WHERE vc.deleted='0' and va.service_type='ms_block' AND vc.mid!='' and  vc.status not in ('settled','paid off','completed','closed')  $NonAdmin
                                GROUP BY vc.MID ";

                $rs= $adb->pquery($sql);
                $count=0;
                while($row=$adb->fetchByAssoc($rs)){

                        $account_value=array('Checking'=>'1' ,'Savings'=>'2','Loan'=>'3');
                        $row['account_type']= $account_value[$row['account_type']];


                        $content[$count]['Recipient name']=str_replace('&#039;',"/'/",html_entity_decode($row['accountname']));

                        $content[$count]['Routing Transit Number']=$row['routing_transit_number'];
                        $content[$count]['Account Number']=$row['account_number'];
                        $content[$count]['AccountType']= $row['account_type'];
                        $content[$count]['Amount']=number_format($row['amount'],2);
                        $data[$count]=array_values($content[$count]);
                        $count++;
                }
                $headers = array_keys ( $content[0]  ) ;

                return array('header'=>$headers,'data'=>$data);



        }

        public function CheckTicket($accountid){

                global $adb;
                $sql="SELECT vt.status,vt.ticketid,ttodo.completed_percent,crm.createdtime
                        FROM vtiger_troubletickets vt
                        LEFT JOIN vtiger_account va ON va.accountid=vt.parent_id
                        LEFT JOIN vtiger_crmentity crm ON crm.crmid=vt.ticketid
                        LEFT JOIN ticket_todolist AS ttodo ON ttodo.ticketid=vt.ticketid
                        WHERE  vt.reasonforcall='Collections' AND crm.deleted=0 AND vt.parent_id='$accountid'
                        order by vt.ticketid desc limit 1;";
                $rs=$adb->pquery($sql);
                if($adb->num_rows($rs)>0){
                        while($row=$adb->fetchByAssoc($rs)){
                                 if(strtolower($row['status'])=='open'){
                                         $ticket_status='open';
                                 }else if(strtolower($row['status'])=='closed'){
                                         $ticket_status='closed';

                                 }

                                 $data[]=$row;
                        }
                        $status=true;
                }else{
                        $status=false;
                        $ticket_status='No';
                }
                return [
                        'status'=>$status,
                        'ticket_status'=>$ticket_status,
                        'data'=>$data
                ];

        }


        public function CheckHavingTicket($accountid){
                global $adb;

              $sql="select * from vtiger_troubletickets vt
                        left join vtiger_account va on va.accountid=vt.parent_id
                        left join vtiger_crmentity crm on crm.crmid=vt.ticketid
                        where vt.`status`='Open' and vt.reasonforcall='Collections' and crm.deleted=0 and vt.parent_id='$accountid' limit 1;";
                $Rs=$adb->pquery($sql);
                if($adb->num_rows($Rs)>0){
                        $status='yes';
                }else{
                        $status ='no';
                }
                return $status ;



        }

          public   function getMerchantHistory($accountid){
                global $adb;

                $gHistorySqlMerchant="select * from collection_manage_history where record='".$accountid."' order by id desc";
                $gHistoryRs=$adb->pquery($gHistorySqlMerchant);
                if($adb->num_rows($gHistoryRs)>0){
                      $colorPicker=array('1'=>'#00bfff','2'=>'#F08080','3'=>'#F08080','4'=>'#DAA520');
                        while($row=$adb->fetchByAssoc($gHistoryRs)){
                                $content[$row['id']]['record']=$row['record'];
                                $content[$row['id']]['createdon']=$row['createdon'];
                                $content[$row['id']]['whodid']=getUserFullName($row['whodid']);
                                $content[$row['id']]['history_type_id']=self::getHistoryType($row['history_type_id']);
                                $content[$row['id']]['historycontent']=$row['historycontent'];
                                $content[$row['id']]['color']=$colorPicker[$row['history_type_id']];
                        }
                        $status='true';
                        $msg=$content;
                }else{
                        $status='false';
                        $msg='No History';
                }
                return [
                        'status'=>$status,
                        'msg'=>$msg
                ];
        }

         function  getHistoryType($id){
           global $adb;

           $sql="select history_type from collection_history_type where id='$id'";
            $Rs=$adb->pquery($sql);
            $row=$adb->fetchByAssoc($Rs);
              return $row['history_type'];


           }

       public function SendBulkMail($request){

            global $adb;
              $MailTo=$request->get('type');
              $data=self::GetCollectionTicketDetails($MailTo);
              if($data['status']){
                        $currentUserModel = Users_Record_Model::getCurrentUserModel();
                        $current_user_id=$currentUserModel->getId();
                                $whodid=$current_user_id;
                         unset($data['status']);
                       foreach($data as $k => $content){
                                $mid = $content['mid'];
                                $accountid = $content['accountid'];
                                $ins = "insert into collectionMailbulk_request (mid,accountid,whodid,sent) values ('$mid','$accountid','$whodid','0')";
                                $adb->pquery($ins);
                        }
                        $result['status']=true;
                        $result['message']='Bulk Mail Request Updated Mail Will Sent to all';

               }else{
                             $result['status']='false';
                          if($data['message'])
                             $result['message']=$data['message'];
                           else
                             $result['message']='Something Went Wrong!';
                }
           return $result;
          }

       public function SendBulkSms($request){
               global $adb;
               $type=$request->get('type');
               $data=self::SmsCollectionTicketDetails($type);
               unset($data['status']);
              $sql="select message from sms_templates where templateid='".$request->get('template_id')."'";
               $rs=$adb->pquery($sql);
               if($adb->num_rows($rs)){
                       $row=$adb->fetchByAssoc($rs);
                       $message_content=$row['message'];
               }
               $currentUserModel = Users_Record_Model::getCurrentUserModel();
               $current_user_id=$currentUserModel->getId();

               $source_module = 'accounts';
               foreach($data as $k =>$recordId){
                       $message = $message_content;

                       $recordModel =Vtiger_Record_Model::getInstanceById($recordId['account']);
                       if($recordModel->get('cf_776')!=''){
                               $phone=$recordModel->get('cf_776');
                       }else if($recordModel->get('phone')!=''){
                               $phone=$recordModel->get('phone');
                       }
                       $moduleFields = $recordModel->entity->column_fields;

                       $collectionDue = Accounts_Record_Model::getCollectionDue($recordId['account']);
                       $collection_due= formatDollars($collectionDue);
                       foreach($moduleFields as $fieldName => $fieldValue){
                               $fieldName =  "$$source_module-$fieldName$";
                               $message = str_replace($fieldName, trim($fieldValue),$message);
                       }

                       $message =str_replace('$colldue$',$collection_due,$message);
                       if($phone!='' && $phone!='000-000-0000'){
                               $Content[$recordId['account']]['phone']=$phone;
                               $Content[$recordId['account']]['message']=$message;
                               $Content[$recordId['account']]['ticket']=$recordId['ticket'];
                       }else{
                               self::recordHistory($recordId['account'],'3','Message Not Send Please Check Merchant Phone Number');

                       }

               }
               foreach ($Content as $record_id =>$msgcontent){
                       $toNumber = array();
                       $message = $msgcontent['message'];
                       $toNumber[$record_id][] = $msgcontent['phone'];
                       $crmIds = array($record_id);

                       $toNumbers = mysql_real_escape_string(json_encode($toNumber));
                       $recordIds = mysql_real_escape_string(json_encode($crmIds));
                       $ticketid=$msgcontent['ticket'];
                       $sql="delete from collection_sms_bulkrequest where ticketid='$ticketid'";
                             $adb->pquery($sql);

                       $ins = "insert into collection_sms_bulkrequest (message,toNumbers,userid,ticketid,recordids,moduleName) values ('$message','$toNumbers','$current_user_id','$ticketid','$recordIds','Accounts')";
                       $adb->pquery($ins);

               }
               $result['status']=true;
               $result['message']='Bulk Sms Request Updated. Sms Will Sent to all';
               return $result;


       }

              public function CollectionTodoCheckOff($ticketid,$accountid,$todoComments = false){
                                       global $adb;
                                       global $current_user;
                                       $user=$current_user->user_name;

                                               $content=self::getCollectionTodoContent($ticketid);
                                               $cdate=$adb->formatDate(date('Y-m-d H:i:s'),true);
                                                $date= DateTimeField::convertToUserTimeZone($cdate)->format('m/d/Y H:i:s');
                                                       $ques=self::question('Collections');
                                               if($content!=''){
                                                       #$decodedQuestion=json_decode(html_entity_decode($content),true);
                                                        $decodedQuestion = Vtiger_CommonModel_Model::TicketTodoList($ticketid);
                                                       $ques_count=0;
                                                       foreach($ques as $quesId=>$quesContent){
                                                               if (strpos(strtolower($quesContent['question']), 'sms merchant') !== false) {
                                                                       $quesContent['ques']=$quesContent['question'];
                                                                       $quesContent['check']='checked';
                                                                       $quesContent['answer']=$todoComments.' Messaged By '.$user.' On Date '.$date;
                                                                       $comment[]=$quesContent['question']." Answer: Messaged By ".$user." On Date '.$date";

                                                               }else{
                                                                       $quesContent['ques']=$quesContent['question'];
                                                                       $quesContent['check']=$decodedQuestion[$quesId]['check'];
                                                                       $quesContent['answer']=$decodedQuestion[$quesId]['answer'];
                                                               }
                                                             if($quesContent!='')
                                                               $decodedQuestion[$quesId]=$quesContent;

                                                           $ques_count++;
                                                       }
                                                            $content=json_encode($decodedQuestion);
                                                       $updateSql="update ticket_todolist set content='".$content."',modified_on='".$cdate."' where  ticketid='".$ticketid."'";
                                                       $adb->pquery($updateSql);


                                                    $SaveTodoList = Vtiger_CommonModel_Model::SaveTodoList($ticketid, $decodedQuestion);
                                                     self::UpdateTodoPercent($ticketid,$ques_count);
                                                     self::CollectionSmsLog($accountid);
                                                     self::recordHistory($accountid,'3','Collection Sms Send To Merchant');

                                               }else{
                                                       $ques=self::question('Collections');
                                                       $ques_count=0;
                                                       foreach($ques as $ques_id=>$questContent){
                                                              if (stripos(strtolower($questContent['question']), 'sms merchant') !== false) {
                                                                       $todoContent['ques']=$questContent['question'];
                                                                       $todoContent['check']='checked';
                                                                       $todoContent['answer']=$todoComments.' Messaged By '.$user.' On Date '.$date;
                                                                       $comment[]=$questContent['question']." Answer: Messaged By ".$user." On Date '.$date";
                                                               }else{
                                                                       $todoContent['ques']=$questContent['question'];
                                                                       $todoContent['check']='off';
                                                                       $todoContent['answer']="";
                                                               }
                                                               $decodedQuestion[$ques_id]=$todoContent;
                                                          $ques_count++;
                                                       }
                                                       $content=json_encode($decodedQuestion);
                                                       $rfcid=self::GetRfcid();
                                                       $insertSql="insert into ticket_todolist (`content`,`ticketid`,`rfcid`,`whodid`,`created_on`,`modified_on`) values('".$content."','".$ticketid."','".$rfcid."','1','".$cdate."','".$cdate."')";
                                                       $adb->pquery($insertSql);

                                                       $SaveTodoList = Vtiger_CommonModel_Model::SaveTodoList($ticketid, $decodedQuestion);
                                                       self::CollectionSmsLog($accountid);
                                                       self::UpdateTodoPercent($ticketid,$ques_count);
                                                       self:: recordHistory($accountid,'3','Collection Sms Send To Merchant');
                                               }
                                               if(is_array($comment)) {
                                                       $commentContent=implode(" / ",$comment);
                                               }

                                               $record=$ticketid;
                                               $owner=$_SESSION['AUTHUSERID'];
                                               if(!empty($commentContent)){
                                                       Collection_CollectionReport_Model::saveTodoToCommentSection($commentContent,$record,$owner);
                                               }


                               }

              public function GetRfcid(){

                      global $adb;


                      $sql="Select reasonforcallid from vtiger_reasonforcall  where reasonforcall=?";
                      $sr = $adb->pquery($sql, array('Collections'));
                      while($gps_main = $adb->fetchByAssoc($sr)){
                              $reasonforcall=$gps_main['reasonforcallid'];
                      }
                      return $reasonforcall;



              }


              public function CollectionSmsLog($accountid){

                      global $adb;
                      global $current_user;
                      $user=$current_user->id;
                      $sql="select * from collection_sms_log where recordid='$accountid'";
                      $rs=$adb->pquery($sql);
                      if($adb->num_rows($rs)>0){
                              $query="update collection_sms_log set whodid='$user',send_on=now() where mid='$mid'";
                      }else{

                              $query ="insert into collection_sms_log (recordid,whodid,send_on) values('$accountid','$user',now())";
                      }
                      $adb->pquery($query);


              }


              public function UpdateTodoPercent($ticketid,$count){
                      global $adb;
                      $query="select * from ticket_todolist where ticketid='".$ticketid."'";
                      $rs=$adb->pquery($query);
                      if($adb->num_rows($rs)>0){
                              $row=  $adb->fetchByAssoc($rs);
                              $success=0;
                              #$decodedQuestion=json_decode(html_entity_decode($row['content']),true);
                              $decodedQuestion = Vtiger_CommonModel_Model::TicketTodoList($row['ticketid']);
                              foreach($decodedQuestion as $ques_id=>$questContent){
                                      if($questContent['check']=='checked'){
                                              $success= $success+1;
                                      }
                              }
                              $successRate=($success/$count)*100;
                              $sql="update ticket_todolist set completed_percent='".number_format($successRate,2)."' where ticketid='".$ticketid."'";
                              $adb->pquery($sql);

                      }
              }


              public function question($rfc){
                      global $adb;
                      $getQuestion="select tc.question,tc.id
                              from ticket_checklist as tc inner join vtiger_reasonforcall as rfc on rfc.reasonforcallid=tc.tcktype where rfc.reasonforcall='$rfc' and tc.is_active='1' and tc.is_deleted=0;";
                      $q=$adb->pquery($getQuestion);
                      while($r=$adb->fetchByAssoc($q))
                      {
                              $row[$r['id']]=$r;
                      }
                      return $row;
              }


              public function getCollectionTodoContent($ticketid){
                      global $adb;
                      $query="select * from ticket_todolist where ticketid='".$ticketid."'";
                      $rs=$adb->pquery($query);
                      if($adb->num_rows($rs)>0){
                              $row=  $adb->fetchByAssoc($rs);
                              return $row['content'];
                      }
              }




       public function GetCollectionTicketDetails($type){
               global $adb;

               if($type!='all'){
                       $con=" and acf.cf_1757 not in (select mid from collection_mail_log where mid!='')";
               }else{
                       $con='';
               }
               $sql=  "SELECT acf.cf_1757 as mid,ac.accountid from vtiger_troubletickets AS tt
                       INNER JOIN vtiger_crmentity AS crm ON crm.crmid=tt.ticketid
                       left join vtiger_account as ac on ac.accountid=tt.parent_id
                       left join vtiger_accountscf as acf on acf.accountid=ac.accountid
                       WHERE crm.deleted=0 AND tt.reasonforcall='Collections' and tt.parent_id!='' and tt.`status`='Open' and acf.cf_1757!='' $con ORDER BY tt.ticketid DESC";
               $rs=$adb->pquery($sql);
               $content=array();
               if($adb->num_rows($rs)){
                       while($data=$adb->fetchByAssoc($rs)){
                               $content['status']=true;
                               if($data['accountid']!='' && $data['mid']!=''){
                                       $content[$data['mid']]['mid']=$data['mid'];
                                       $content[$data['mid']]['accountid']=$data['accountid'];
                               }

                       }
               }else{
                     if($type!='all')
                     $content['message']='No Open Ticket Avaiable  To Mail';
                      $content['status']=false;
                }

                return $content;


       }

      public function SmsCollectionTicketDetails($type){
         global $adb;

            if($type!='all'){
               $con="and ac.accountid not in (select recordid from collection_sms_log where recordid!='')";

              }else{
               $con='';
            }

             $sql=  "SELECT acf.cf_1757 as mid,ac.accountid,ac.phone,tt.ticketid from vtiger_troubletickets AS tt
                       INNER JOIN vtiger_crmentity AS crm ON crm.crmid=tt.ticketid
                       left join vtiger_account as ac on ac.accountid=tt.parent_id
                       left join vtiger_accountscf as acf on acf.accountid=ac.accountid
                       WHERE crm.deleted=0 AND tt.reasonforcall='Collections' and tt.parent_id!='' and tt.`status`='Open' and acf.cf_1757!='' $con ORDER BY tt.ticketid DESC";
               $rs=$adb->pquery($sql);
               $content=array();
               if($adb->num_rows($rs)){
                       while($data=$adb->fetchByAssoc($rs)){
                               if($data['accountid']!=''){
                                       $content[$data['accountid']]['account']=$data['accountid'];
                                       $content[$data['accountid']]['ticket']=$data['ticketid'];
                               }
                               $content['status']=true;

                       }
               }else{
                      $content['status']=false;
                }

                return $content;

        }

        public function SmsMerchantDetails($request){

           global $adb;
                $sql="SELECT tt.ticketid,tt.`status`,ac.accountname,crm.deleted,acf.cf_1757 as mid,ml.send_on ,ml.whodid from vtiger_troubletickets AS tt
                    INNER JOIN vtiger_crmentity AS crm ON crm.crmid=tt.ticketid
                     left join vtiger_account as ac on ac.accountid=tt.parent_id
                     left join vtiger_accountscf as acf on acf.accountid=ac.accountid
                     left join collection_sms_log as ml on ml.recordid=ac.accountid
                 WHERE crm.deleted=0 AND tt.reasonforcall='Collections' and tt.parent_id!='' and tt.`status`='Open' and acf.cf_1757!='' ORDER BY tt.ticketid DESC";
                $rs=$adb->pquery($sql);
            if($adb->num_rows($rs)){
               while($data=$adb->fetchByAssoc($rs)){
                    $content['status']=true ;
                     $total[]=$data;
                     if($data['whodid']!=''){
                      $data['whodid']= getUserFullName($data['whodid']);
                       $content['Messaged'][]=$data;
                     }else{
                      $content['NotMessaged'][]=$data;
                     }

                }
                  $content['Total_messaged'] =count($content['Messaged']);
                  $content['Total_NotMessaged'] =count($content['NotMessaged']);
                  $content['Total'] =count($total);

             }else{
                    $content['status']=false ;

              }
              return $content;

         }




        public function OpenCollectionTicket($request){

           global $adb;
                $sql="SELECT tt.ticketid,tt.`status`,ac.accountname,crm.deleted,acf.cf_1757 as mid,ml.mail_on ,ml.whodid from vtiger_troubletickets AS tt
                    INNER JOIN vtiger_crmentity AS crm ON crm.crmid=tt.ticketid
                     left join vtiger_account as ac on ac.accountid=tt.parent_id
                     left join vtiger_accountscf as acf on acf.accountid=ac.accountid
                     left join collection_mail_log as ml on ml.mid=acf.cf_1757
                 WHERE crm.deleted=0 AND tt.reasonforcall='Collections' and tt.parent_id!='' and tt.`status`='Open' and acf.cf_1757!='' ORDER BY tt.ticketid DESC";
                $rs=$adb->pquery($sql);
            if($adb->num_rows($rs)){
               while($data=$adb->fetchByAssoc($rs)){
                    $content['status']=true ;
                     $total[]=$data;
                   $data['mail_on']= date('m/d/Y H:i:s',strtotime($data['mail_on']));
                     if($data['whodid']!=''){
                      $data['whodid']= getUserFullName($data['whodid']);
                       $content['Mailed'][]=$data;
                     }else{
                      $content['NotMailed'][]=$data;
                     }

                }
                  $content['Total_mailed'] =count($content['Mailed']);
                  $content['Total_NotMailed'] =count($content['NotMailed']);
                  $content['Total'] =count($total);

             }else{
                    $content['status']=false ;

              }
              return $content;

         }
        public  function searchParams($request){

                if($request->get("service_type") == 'all' || $request->get('service_type')==''){
                        $where.= "";
                }else {
                        $where.= ' and va.service_type= "'.$request->get("service_type").'"';
                }
                if(strtolower($request->get('collection_api_status'))!='submitted'){
                        if(($request->get("collection_status") == 'all' || $request->get('collection_status')=='') ){
                                $where.= " and  collection_balances.collection_status not in ('settled','paid off','completed','closed' ,'charge off','chargeoff')";
                        }
                        else{
                                $where.= ' and collection_balances.collection_status="'.$request->get('collection_status').'"';
                        }
                }

             $roles=$request->get('role');

                $profile_user = Users_Record_Model::getCurrentUserModel();

                        if(in_array('all',$roles)){
                                $where.="";
                        }
                        else if($request->get('role')!='' ){
                                $role=implode("','",$request->get('role'));
                                $where.=" and (u2r.roleid in ('".$role."') or mu2r.roleid in ('".$role."')) ";
                        }

                        $user=$request->get('user');

                        if($request->get('user')!='' && $request->get('user')!='null'){
                                $user=implode("','",$request->get('user'));
                                $where.=" and (vtiger_users.id in ('".$user."'))";
                        }
                        if($request->get('collection_status') != 'charge off'){
                                $where.=" and charge_off='N'";
                        }

                if($request->get("collection_company") == 'all' || $request->get('collection_company')==''){
                        $where.= "";
                }
                else{
                        $where.= ' and vc.collection_company="'.$request->get('collection_company').'"';
                }

                $paid=explode('-',$request->get('created_date'));
                $paid_f = date('Y-m-d',strtotime($paid[0]));
                $paid_t = date('Y-m-d',strtotime($paid[1]));


                 if($request->get('created_date')){
                         if(strtolower($request->get('collection_api_status'))!='submitted'){
                                 $paid=explode('-',$request->get('created_date'));

                                 $paid_f = date('Y-m-d',strtotime($paid[0]));
                                 $paid_t = date('Y-m-d',strtotime($paid[1]));
                                 $where.=" and date(vc.start_date) between '$paid_f' and '$paid_t'";
                         }

                  }



                if($request->get('mid')!=''){
                        $where.= 'and vas.cf_1757 ="'.$request->get("mid").'"';
                }

                 if(strtolower($request->get('collection_api_status'))=='submitted' || strtolower($request->get('collection_api_status'))=='all'){
                        if($request->get('collection_api_status')!='all'){
                                $where.= " and date(rd.created_on) BETWEEN '$paid_f' AND '$paid_t' AND (rd.collection_id!='' || rd.collection_id=0)";
                        }
                        if($request->get('api_user') && $request->get('api_user')!='all'){
                                $api_user=$request->get('api_user');
                                $where.=" and rd.client_id='$api_user'";
                        }
                }


                $banktype = $request->get('banktype');
                foreach($banktype as $key=>$value){
                        if($value == 'all'){
                                $fdr = 'yes';
                                $tsys5563 = 'yes';
                                $tsys9261 = 'yes';
                        }elseif($value == 'fdr'){
                                $fdr = 'yes';
                        }elseif($value == '5563'){
                                $tsys = 'yes';
                                $tsys5563 = 'yes';
                        }elseif($value == '9261'){
                                $tsys = 'yes';
                                $tsys9261 = 'yes';
                        }
                }

                if($tsys5563 == 'yes' && $tsys9261 == 'yes'){
                        #$where.=" OR va.bank_for_boarding IN ('5563','9261')";
                        if($fdr == 'yes'){
                                                 $where.=" AND cf_816 IN ('FirstData','TSYS')";
                                        }else{
                                                $where.=" AND cf_816 = 'TSYS'";
                         }

                }elseif($tsys5563 == 'yes' && $tsys9261 == ''){
                        if($fdr == 'yes'){
                                $where.="AND (va.bank_for_boarding='5563' OR va.bank_for_boarding IS NULL )";
                        }else{
                                $where.= " AND va.bank_for_boarding='5563'";
                        }
                }elseif($tsys5563 == '' && $tsys9261 == 'yes'){
                        if($fdr == 'yes'){
                                $where.="AND (va.bank_for_boarding='9261' OR va.bank_for_boarding IS NULL )";
                        }else{
                                $where.= " AND va.bank_for_boarding='9261'";
                        }

                }elseif($tsys5563 == '' && $tsys9261 == '' && $fdr =='yes'){
                        $where.=" AND cf_816 = 'FirstData'";
                }else{

                }
                return $where;
        }


        public function getTodoCompletePercentage($accountid){
                global $adb;
                $sql="SELECT tt.ticketid,ttodo.completed_percent FROM vtiger_troubletickets AS tt INNER JOIN vtiger_crmentity AS crm ON crm.crmid=tt.ticketid LEFT JOIN ticket_todolist AS ttodo ON ttodo.ticketid=tt.ticketid WHERE crm.deleted=0 AND tt.reasonforcall='Collections' and tt.parent_id='".$accountid."' ORDER BY tt.ticketid DESC";
                $rs=$adb->pquery($sql);
                if($adb->num_rows($rs)>0){
                        while($row=$adb->fetchByAssoc($rs)){
                                $data[]=$row;
                        }
                        $status=true;
                }else{
                        $status=false;
                }
                return [
                        'status'=>$status,
                        'data'=>$data
                ];

        }

        public static function getCollectionTodoList($request){
                global $adb;
                $getTicketId="SELECT tt.ticketid,tt.ticket_no,tt.status,acc.accountname,accscf.cf_1757 as `mid` FROM vtiger_troubletickets as tt inner join vtiger_crmentity as crm on crm.crmid=tt.ticketid inner join vtiger_account as acc on acc.accountid=tt.parent_id inner join vtiger_accountscf as accscf on accscf.accountid=acc.accountid WHERE tt.reasonforcall='Collections'  and crm.deleted='0' and tt.parent_id='".$request->get('accountid')."' ORDER BY ticketid DESC limit 1";
                $rs=$adb->pquery($getTicketId);
                if($adb->num_rows($rs)>0){
                        while($data=$adb->fetchByAssoc($rs)){
                                $request->set('record',$data['ticketid']);
                                $request->set('rfc','Collections');
                                $content=HelpDesk_Record_Model::getTodoList($request);
                                $status='true';
                                $ticket=$data['ticketid'];
                                $ticket_status=$data['status'];
                                $minfo['merchant']['mid']=$data['mid'];
                                $minfo['merchant']['name']=$data['accountname'];
                                $minfo['merchant']['ticket_no']=$data['ticket_no'];
                        }
                }else{
                        $status='false';
                        $content='';
                        $ticket='';
                        $minfo='';
                }
                $todoTask=self::getTodoCompletePercentage($request->get('accountid'));
                if($todoTask['status']){
                        $TodoPercentage=$todoTask['data'][0]['completed_percent'];
                }
                return [
                        'status'=>$status,
                        'content'=>$content,
                        'ticketid'=>$ticket,
                        'ticket_status'=>$ticket_status,
                        'todopercentage'=>$TodoPercentage,
                        'accountid'=>$request->get('accountid'),
                        'minfo'=>$minfo
                ];
        }



        public function SaveComments($request){
                $content=$request->get('comment');
                $record=$request->get('recordid');

                $owner=$_SESSION['AUTHUSERID'];
                $ModComments = Vtiger_Record_Model::getCleanInstance('ModComments');
                $ModComments->set('commentcontent',$content);
                $ModComments->set('related_to',$record);
                $ModComments->set('assigned_user_id', $owner);
                $ModComments->set('userid', $owner);
                $ModComments->set('comment_method','6');
                $ModComments->save();
                $historyContent=getUserFullName($_SESSION['AUTHUSERID'])." posted ".$content;
                self::recordHistory($record,'1',$historyContent);

        }

        public function recordHistory($record,$history_type,$history_content){
                global $adb,$current_user;

                $date=$adb->formatDate(date('Y-m-d H:i:s'),true);
                $insertSql="insert into collection_manage_history (`record`,`historycontent`,`history_type_id`,`whodid`,`createdon`) values('".$record."','".$history_content."','".$history_type."','".$current_user->id."','".$date."')";
                $adb->pquery($insertSql);
        }



        public function filters(){

                $profile_user = Users_Record_Model::getCurrentUserModel();
                $service_block=$profile_user->get('service_block');
                if (strpos($service_block, ',') !== false) {
                        $ser_arr=split(",",$service_block);
                        $service['ms_block']=$ser_arr[0];
                        $service['mca_block']=$ser_arr[1];
                }else{
                        if($service_block=='ms'){
                                $service['ms_block']=$service_block;

                        }else{
                                $service['ms_block']=$service_block;
                        }

                }


                $currentUser = Users_Record_Model::getCurrentUserModel();
                $accessibleUsers = $currentUser->getAccessibleUsers();

                $agentcodelist = $currentUser->getAgentCode();

                foreach($accessibleUsers as $id=> $val){
                        $agentcode = $agentcodelist[$id];
                        $agent[$id] = $val.' ( '.$agentcode.' )';
                }

                $allRoles = Settings_Roles_Record_Model::getAll();
                $CollectionCompany = Vtiger_CommonModel_Model::getCollectionCompanies();
                     $oxygen_credentials=Collection_Record_Model::getOxygenCredentials();
                return [
                        'roles'=>$allRoles,
                        'CollectionCompany'=>$CollectionCompany,
                        'service'=>$service,
                        'agent'=>$agent,
                        'api_user'=>$oxygen_credentials,
                ];

        }



        public function array_sort_onDate_key($array, $on, $order){

                function compareByTimeStamp($time1, $time2)
                {
                        if (strtotime($time1) < strtotime($time2))
                                return 1;
                        else if (strtotime($time1) > strtotime($time2))
                                return -1;
                        else
                                return 0;
                }

                $new_array = array();
                $sortable_array = array();
                if(count($array) > 0){
                        foreach($array as $k => $v){
                                if(is_array($v)){
                                        foreach($v as $k2 => $v2){
                                                if($k2 == $on){
                                                        $sortable_array[$k] = $v2;
                                                }
                                        }
                                }else{
                                        $sortable_array[$k] = $v;
                                }
                        }
                        switch($order){
                                case SORT_ASC:
                                        asort($sortable_array);
                                        break;
                                case SORT_DESC:
                                        # arsort($sortable_array);
                                        uasort($sortable_array, "compareByTimeStamp");
                                        break;
                        }
                        foreach($sortable_array as $k => $v){
                                $new_array[$k] = $array[$k];
                        }
                }
                return $new_array;
        }



}

?>