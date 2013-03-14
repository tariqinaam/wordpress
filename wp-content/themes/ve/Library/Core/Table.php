<?php
if ( !class_exists( 'WP_List_Table' ) ) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}
//Rhino_WP_List_Table


class smtrWp_Table_Core extends WP_List_Table
{ 
    var $cols;
    var $ordering;
    var $_actions;
    var $offset;
    var $rpp;
    var $bulkActionsArray = array();
   
    
    function setColumns($cols,$checkbox=false){
        
        foreach($cols as $key => $col){
            
            if(!is_array($col))
                continue;
            
            
            
            if(isset($key)&&is_string($key)){
               
                if($checkbox)
                $this->colsArray['cb'] = '<input type="checkbox"/>';
                
                $this->cols->{$key}->id     = $key;
               
                $this->cols->{$key}->title  = isset($col[0])?$col[0]:'undefined';
                
                if(isset($col[1]))
                $this->cols->{$key}->sort   = true;
                
                if(isset($col[2]))
                $this->cols->{$key}->sorted = true;
                
                $this->colsArray[$key] = $this->cols->{$key}->title;
                
            }
            
        }
        return $this;
        
    }
    
   
    function get_columns()
    {
	return $this->colsArray;
    }
    
    
    function get_sortable_columns()
    {
        $sortable_columns = array();
        
        foreach($this->cols as $cols ){
            
            if(isset($cols->sort)){
                
                if(isset($cols->sort)){
                    $sortable_columns[$cols->id][0] = $cols->id;
                    $sortable_columns[$cols->id][1] = $cols->sort;
                }
            }
        }

	return $sortable_columns;
    }

    
    public function setOrdering(){
        
        $this->setArguments('order','asc');
        $this->setArguments('orderby');
        
        if($this->orderby&&$this->order&&$this->order!='none'){
            $this->sql->ordering = 'order by '.$this->orderby.' '.$this->order;
        }else $this->sql->ordering = '';
        
        return $this;
    }

    public function setLimit($per_page){

        $this->setArguments('paged',1);
        
        $this->offset = $start = ($this->paged-1)*$per_page;
        $this->rpp    = $end   = $per_page;
        $this->sql->limit = 'limit '.$start.','.$end;

        return $this;
    }
    
    public function printStyles($pre,$widths){
          
        $output = '';
        foreach($widths as $key => $data){
            
             $output .= "$pre $key"."{".$data."}";
        }
        
        
       
        
        return "<style>$output</style>";
    
    }
    
   
    
    public function setActions($actions=array()){
          
        $search = array();
        
        foreach($actions as $key => $data){
            
             //$this->search->items[] = "$col like '%$this->s%'";
        }
        
        return $this;
    }
    
    public function getActions(){
          
 
        return $this->row_actions($this->_actions);
    }
    
    
    function getAction(array $actions){
        
        $action = '';
        if(isset($_POST['action'])&&$_POST['action']!=-1){
            $action = $_POST['action'];
        }
        elseif(isset($_POST['action2'])&&$_POST['action2']!=-1){
            $action = $_POST['action2'];
        }
        elseif(array_multi_key_exists($actions,$_POST)){
            foreach($actions as $item){
                if(isset($_POST[$item]))
                    $action = $item;
            }
        }

        return $action;

    }
    
    public function setBulkActions(array $actions=array()){
	 
        foreach($actions as $key => $action){
                $this->bulkActionsArray[$key] = $action;
        }
        return $this;
    }
    
    function get_bulk_actions()
    {
	return $this->bulkActionsArray;
    }

    public function setSearch($cols=array(),$lookup = array()){
        
        $this->setArguments('s');    
     
        $this->setSearchLookup($lookup );    
       
        if(preg_match_all('/([a-zA-Z_-]+\:[0-9a-zA-Z_-]+)/',$this->s,$matches)){
            
            $search = explode(',',$this->s);
            foreach($search as $part){
                $arr = explode(':',$part);
                $key = trim($arr[0]);
                $val = trim($arr[1]);
                $col = $this->search->lookup[$key];
                $this->search->items[] = "$col like '%$val%'";
            }
        }
        
       
        foreach($cols as $col){
            
             $this->search->items[] = "$col like '%$this->s%'";
        }
        
        
        $this->sql->search = ' ('.implode(' or ',$this->search->items).') ';
       
        return $this;
    }
    
    public function setSearchLookup($cols=array()){
        
        
        
        foreach($cols as $key => $val){
            
             $this->search->lookup[$key] = $val;
        }
        
        return $this;
    }
    
    public function setFiltering(){
        
        $this->setArguments('order','asc');
        $this->setArguments('orderby');
        
        if($this->orderby&&$this->order&&$this->order!='none'){
            $this->sql->ordering = 'order by '.$this->orderby.' '.$this->order;
        }else $this->sql->ordering = '';
        
        return $this;
    }

    
    public function setArguments($name,$default=''){
        
        if(isset($_REQUEST))
        $this->{$name} = $this->setVar($_REQUEST,$name,$default);
        
        return $this;
    }
    
    
    public function setVar($attr,$name,$default=''){
        if(is_array($attr))
        return isset($attr[$name])&&$attr[$name]?$attr[$name]:$default;
    }
    
    
    function prepare_items($data,$ttl_items)
    {
	$columns  = $this->get_columns();
        
	$hidden   = array( );
	$sortable = $this->get_sortable_columns();
	$this->_column_headers = array( $columns, $hidden, $sortable );

	$this->items = $data;

        $ttl_pages = ceil($ttl_items/$this->rpp);
        
        $arr = array(
	    'total_items' => $ttl_items, 
	    'per_page'    => $rpp, 
	    'total_pages' => $ttl_pages
	);
        
    
	$this->set_pagination_args($arr);
    }

    function column_cb($row){
        
        return sprintf('<input name="action_items[]" type="checkbox" value="%s"/>',$row->ID);
    }
    
    function single_row( $item ) {

        static $row_class = '';
        $row_class = ( $row_class == '' ? ' class="alternate"' : '' );
        echo '<tr' . $row_class . '>';
        echo $this->single_row_columns( $item );
        echo '</tr>';
    }
}
?>