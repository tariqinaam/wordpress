/*jQuery(document).ready(function($){

 

    // ajaxurl should always be defined in the head

    $( "#fote_link_text" ).tokenInput(ajaxurl + '?action=fote_staff_search',{

                theme: "facebook"

    });

    

});*/

 

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

            

            //alert($(".media_type").val());

            

            $(".media_type").each(function(){

                

                if($(this).is(':checked'))

                {

                     $("#"+$(this).val()+"_option").css({"display":"block"})

                }

                

            })

            

            $("#rightsidebar").click(function(){

                

                if($(this).is(':checked'))

                {

                     $("#related-links").css({"display":"block"})

                     $("#question-form").css({"display":"block"})

                     $("#staff-spotlight").css({"display":"block"})

                     $("#sidebar_options").css({"display":"block"})

                }else{

                     $("#related-links").css({"display":"none"})

                     $("#question-form").css({"display":"none"})

                     $("#staff-spotlight").css({"display":"none"})

                     $("#sidebar_options").slideUp('fast')

                }

                

            })

            

            if($("#rightsidebar").is(':checked'))

            {

                $("#related-links").css({"display":"block"})

                $("#question-form").css({"display":"block"})

                $("#staff-spotlight").css({"display":"block"})

                $("#sidebar_options").css({"display":"block"})

            }else {

                $("#related-links").css({"display":"none"})

                $("#question-form").css({"display":"none"})

                $("#staff-spotlight").css({"display":"none"})

                $("#sidebar_options").css({"display":"none"})

            }

             

 

 

            $(".checkbox_list .checkbox").change(function(){

                

                if($(this).is(":checked"))

                {

                    //alert(".parent_"+$(this).val())

                    $(".parent_"+$(this).val()).attr({"checked":"checked"});

                    var i=0;

                    

                    var classes = $(this).attr("class").split(/\s+/);

                    

                    for(i=0;i<classes.length;i++)

                    {

                        

                        if(classes[i]!="checkbox")

                        {

                            //var class = classes[i];

                            

                            //alert("#"+classes[i])

                            $("#"+classes[i]).attr({"checked":"checked"})

                        }

                    }

                    

                    //alert(classes)

                    

                }else $(".parent_"+$(this).val()).removeAttr("checked")

            })

            

           // if($(".media_type").is(":checked"))

           // $("#"+$(".media_type").val()+"_option").css({"display":"block"})

            //else if($(".media_type").val()=="image"&&$(".media_type").is(":checked"))

            //$("#image_option").css({"display":"block"})

            

            

            $(".media_type").click(function(){

                $(".media_contents").css({"display":"none"})

                

                var id = $(this).attr("id");

                

                $("#"+id+"_option").css({"display":"block"})

            })

        });

var gdata;

var flag=false;

 

 

$(function() {

            var i = $('#flow_testimonials li').size() + 1;

 

            $('a#add').click(function() {

                $('<li>Testimonial ' + i + ': <input type="text" name="the_field[]"></li>').appendTo('ul#flow_testimonials');

                i++;

            });

 

            $('a#remove').click(function() {

                $('#flow_testimonials li:last').remove();

                if (i == 1) {

                                                                

                                                                } else {

                i--;

                }

                

            });

        });

$(document).ready(function(){

    

    

    $(".suggest").bind("click",function(){

 

        //var query = $(this).val();

        

        //get details to position the div... watch out for padding

        var width = $(this).width()+10,height = $(this).height(),offset = $(this).offset();

        var top = -50;//plus padding

        var left = offset.left-30;

        //alert("top--- "+width+" ---- left:"+left+"px")

 

        var type= $(this).hasClass("suggest_img")?'img':'url';

 

        //check that we have destroyed the suggest message div

        $("#suggest_message").remove();

        

        //recreate the div not so great it keeps flashing on and off 

        $(this).after("<div id='suggest_message'></div>");

        

        $("#suggest_message").css({

            "width":"400px","margin-top":"-30px",

            //"top":top+"px",

            "left":left+"px",

            "position":"absolute"

        });

        

        //alert($(this).attr('name'));

             

        //if the input has nothing then dont allow suggest

        

            //if(flag==false)

            {

                $.post(directory+"/images.php",{ field_base:$(this).attr('value')},

 

                function(data){ 

 

                    //grab data and display

                    //flag=true;

                    //gdata = data;

                    $("#suggest_message").show(0).html(data);

 

                });

            }

            

               /*$.post(directory+"/images.php",

   

                function(){ 

 

                    $("#suggest_message").show(0).html(gdata);

 

                });*/

 

 

    });

 

    $("li.select").live( "click" , function(){

 

        var img_id  = $(this).attr('img_id');

        var fbase   = $(this).attr('fbase');  

        var img_url = $(this).attr('img_url');  

        //alert(img_id+" "+fbase);

        $("#"+fbase).val(img_id);

        

        $("#"+fbase+"_img").html("<img src='"+img_url+"' width='50px' height='50px'/>");

        $("#suggest_message").hide(0);

    });

 

    $("body,html").live("click",function(){ $("#suggest_message").hide(0); });

});
