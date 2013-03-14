<?php 
global $wpdb,$this_user;

$list = new Ibro_Table_Entries;

$cols = array();
$cols['id']   = array('App.',1);
$cols['agentName'] = array('Lname',1);
$cols['address1'] = array('Fname',1);
$cols['address2']  = array('Country',1);
$cols['city']   = array('Region',1);
$cols['postcode'] = array('Assessor',1,1);
$cols['phoneNo'] = array('Approval',1,1);

$cols['latitude']    = array('R',1,1);
$cols['longitude']      = array('RC',1,1);
$cols['isActive']    = array('Rev.',1);

$list->setOrdering();        
$list->setColumns($cols);
$list->setLimit(5);

//pre_dump($list);

 $query = sprintf("select * from wp_agent %s",$list->sql->limit);
 $count= "select count(1) from wp_agent";

$data      = $wpdb->get_results( $query);

//pre_dump($data);
 $data_count = $wpdb->get_var( $count);




$list->prepare_items($data,$data_count,5);


$args = argsArr(array('s'));


echo $list->printStyles('table ',array(
    '.column-Address1' => 'width:5%!important;line-height:19px',
    '.column-firstname'=> 'width:15%!important;line-height:19px;',
    '.column-model'  => 'width:20%!important',
    '.column-edit'   => 'width:5%!important',
    '.column-view'  => 'width:5%!important',
    '.column-rc'  => 'width:5%!important',
    '.column-rank'  => 'width:5%!important',
    '.column-assessor'  => 'width:10%!important',
    '.column-region'  => 'width:5%!important',
    '.column-revs'  => 'width:5%!important',
    '.column-tools'  => 'width:5%!important',
    '.column-approval'  => 'width:5%!important',
));

?>




  <form class="navbar-form pull-left" method="get" action="/wp-admin/admin.php?page=application-entries">
      <div class="input-append">
          <input id="searchableField" placeholder="search by lname:smith, fname:john etc" class="span6" size="16" name="s" type="text" value="<?php echo $s; ?>">
          <button class="btn " type="submit">Search</button>
          <?php echo generateHiddenFields($args,array('s'));?>
      </div>
  </form>
  <form class="navbar-form pull-right" method="post" action="/wp-admin/admin.php?page=application-entries">
      <button class="btn" type="submit">Download Entries</button>
      <input type="hidden" name="smrtrDownloadCSV" value="true"/>
      <input type="hidden" name="smrtrDownloadType" value="application_entries"/>
      <input type="hidden" name="application_entries">
  </form>




<?php $list->display(); ?>