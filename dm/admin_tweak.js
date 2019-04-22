var $ = jQuery.noConflict();
var target_id = "#metavalue";

function dm_select(){
    $("#category-page").show(); 
    dm_change(  $("#category-page") );
    $("#dm_select_btn").hide();
}

function dm_change(m){ 
    $(target_id).val($(m).val());
}


$(function(){ 
    var select = "<select name='' id='category-page' style='display:none;' onchange='dm_change(this)' >" + $("#select_category").html() + "</select> ";  
    var notexist = "";
    var currentvalue = "";
 

    $(" <span>&nbsp;</span> " + select + " <input type='button' value='Define as content of category' class='button' onclick='dm_select()' id='dm_select_btn' />   ").insertAfter($("#insert-media-button"));

    $('#metakeyselect').val('categorycontent');  
    
    if($("#postcustomstuff #list-table").length > 0 && $("#postcustomstuff #list-table").is(':visible')){ //exists   
 
        currentvalue = $("#postcustomstuff #list-table textarea").eq(0).val(); 
        target_id = "#"+ $("#postcustomstuff #list-table textarea").eq(0).attr("id");  


        if($(target_id).val() != ''){                   
            $("#category-page").val($(target_id).val());  
            dm_select(); 
        } 

        

    }else{ 
        $('#metakeyinput,#metakeyselect').val('categorycontent');  
        notexist = "<input type='hidden' name='metakeyinput' id='metakeyinput' value='categorycontent' /> <input type='hidden' name='metavalue' id='metavalue' value='' /> ";
    }


});