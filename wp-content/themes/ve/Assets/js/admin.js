if(typeof String.prototype.trim !== 'function') {
    String.prototype.trim = function() {
        return this.replace(/^\s+|\s+$/g, ''); 
    }
}


$(document).ready(function(){

            //setup fields on load
            $(".maxlength").each(function(e){

                $(this).attr("id","dl_"+e)

                var strlen = $(this).val().length,maxlen = $(this).attr("maxlength");

                $(this).after("<span class='dl_view dl_"+e+"_view'><span class='dl_"+e+" dl_dynamic'>"+strlen+"</span>/"+maxlen+"</span>");

            }).keyup(function(){

                var id = $(this).attr("id"),strlen = $(this).val().length;

                if($(this).attr("maxlength")>=strlen )
                     $("."+id+"_view").css({"font-weight":"bold"})
                else $("."+id+"_view").css({"font-weight":"normal"})

                $("."+id).html(strlen);

            }); 
            
            $(".media_contents").css({"display":"none"});
            
            
            $(".media_type").each(function(){
                
                if($(this).is(':checked'))
                {
                     $("#"+$(this).val()+"_option").css({"display":"block"});
                }//else $("#"+$(this).val()+"_option").css({"display":"none"});
                
      
            });
            
            $("#rightsidebar").click(function(){
                
                if($(this).is(':checked'))
                {
                     $("#media-type").css({"display":"block"});
                     $("#billboard").css({"display":"none"});
                }else{
                     $("#media-type").css({"display":"none"});
                     $("#billboard").css({"display":"block"});
                }
                
            });
            
            
            $("#in-category-"+eventID).click(function(){
                
                if($(this).is(':checked'))
                {
                     $("#event").css({"display":"block"});
                   
                }else{
                     $("#event").css({"display":"none"});
                }
                
            });
            if($("#in-category-"+eventID).is(':checked'))
            {
                $("#event").css({"display":"block"})
            }else {
                $("#event").css({"display":"none"})
            }
            
            
            if($("#rightsidebar").is(':checked'))
            {
                $("#media-type").css({"display":"block"})
                $("#billboard").css({"display":"none"})
            }else {
                $("#media-type").css({"display":"none"})
                $("#billboard").css({"display":"block"})
            }
             
 
            $(".media_type").click(function(){
                $(".media_contents").hide();
                
                var id = $(this).attr("id");
                
                $("#"+id+"_option").fadeTo(0,0).slideDown(200,function(){
                            
                            $(this).fadeTo(500,1);
                        });
            });

    $('.remove_gal_img').bind("click",function(){
        alert('fdf')
    });
    
    $(".suggest").bind("click",function(){

        var width = $(this).width()+10,height = $(this).height(),offset = $(this).offset();
        var top = -50;//plus padding
        var left = offset.left-30;

        var type= $(this).hasClass("suggest_img")?'img':'url';

        //check that we have destroyed the suggest message div
        $("#suggest_message").remove();
        
        //recreate the div not so great it keeps flashing on and off 
        $(this).after("<div id='suggest_message' class='suggestrem'></div>");
        
        $("#suggest_message").css({
            "width":"400px","margin-top":"-30px",
            "left":left+"px",
            "position":"absolute"
        });
        
        
        //if(flag==false)
        {
            $.post(directory+"/_inc/images.php",{ field_base:$(this).attr('value')},

            function(data){ 

                $("#suggest_message").show(0).html(data);

            });
        }


    });

    $("li.select").live( "click" , function(){

        var img_id  = $(this).attr('img_id');
        var fbase   = $(this).attr('fbase');  
        var img_url = $(this).attr('img_url');  

        $("#"+fbase).val(img_id);
        
        $("#"+fbase+"_img").html("<img src='"+img_url+"' width='50px' height='50px'/>");
        $("#suggest_message").hide(0);
    });

    
   
    $(".RR_suggest").bind("keyup",function(){


        var query = $(this).val();
        
        query = query.replace(/[^a-zA-z0-9] /g,'').trim();
        
        var this_id = $(this).attr('id');
        var width = $(this).width()+8,height = $(this).height(),offset = $(this).offset();
        var top = offset.top+height-4;//plus padding
        var left = offset.left-166;
        var j=0;
        var resultsDIV = this_id+'_resultsDiv';
        var resultID = '#'+resultsDIV;
        var resultIDattr = 'id="'+resultsDIV+'"';
        $(resultID).hide().html();
        $(this).after("<div "+resultIDattr+" class='suggestResults suggestrem' style='display:none'></div>");
        var list ='<ul class="'+this_id+'">';
        var match = false;
        for (var x in link_objects)
        {
            
            var id = link_objects[x][0];
            var name = link_objects[x][1];
            var type = link_objects[x][2];
            var link = link_objects[x][3];
            var alt = link_objects[x][4];
            var url = link_objects[x][5];
            var tree = link_objects[x][6];
            
            
            var classes = 'data-id-'+id+' '+'data-type-'+type;
            
            query = query.toLowerCase();
            
            if(name.toLowerCase().match(query)&&j<5){
                
                var label = name;//.toLowerCase().replace(query, "<b>"+query+"</b>")
                
                list += '<li class="select '+classes+'">('+link+') <span class="label">'+label+'</span> <div class="tree"><span>'+tree+'</span></div></li>';
                match = true;
                j++;
           }
                
            
        }
        list += '<li class="last">'+query+'</li>';
        list += '</ul>';
       
        
        $(resultID).css({
            "width":width+"px",
            "left":left+"px",
            "position":"absolute"
        });
        //if the input has nothing then dont allow suggest
        if (match==false)
        { 
             $(resultID).hide().html(); 
             return false;
        }
        else 
        {
             $(resultID).show(0).html(list);
        }
    });

    $("li.select").live( "click" , function(){
          
          
          var classes = $(this).attr('class');
          var arr = classes.split(' ');
          var pairs = new Array, keyVal = new Array;
          var i=0;
          
          var this_id = $(this).parent().attr('class');
          
          for (var x in arr)
          {
              if(arr[x].match(/data\-/)){
                  
                  var pairs_str = arr[x].replace(/data\-/,'');
            
                  pairs[i++] = pairs_str.split('-');
              }
              
          }
          
        
        $("#"+this_id+'_'+pairs[0][0]).val(pairs[0][1]);
        $("#"+this_id+'_'+pairs[1][0]).val(pairs[1][1]);
        $("#"+this_id).val($(this).find('span.label').text());

    });

    $("body,html").live("click",function(){ $(".suggestrem").hide().html(""); });
});