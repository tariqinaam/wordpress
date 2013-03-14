<?php 

    class Blog_Listing extends Repeater_Model{
        
        public function _link($obj){
  
            return '/blog/'.$obj->post_name;
        }
        
        
    }

    $list = new Blog_Listing($this->posts);
    
    
   
    
    $list->setTemplate('<li><h4><a href="{{link}}">{{post_title}}</a></h4><p class="date">{{post_date}}</p>{{image}}<p>{{content}}</p></li>');

    echo '<ul>'.$list->render().'</ul>';
    
    
    echo '<ul>';
    foreach($this->pagination as $page){
        
        printf('<li><a href="/blog/page/%s">%s</a></li>',$page,$page);
        
    }
    echo '</ul>';
    