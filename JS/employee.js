/**
 * File employee.js.
 * Version: 01.01.01
 * Lic : OHS
 * Theme Customizer enhancements for a better front end/employee experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

//form: ajax request
jQuery( document ).ready(function(e){
    
    jQuery("#auto_search").keyup(function(){
        var term = jQuery('#auto_search').val();
        jQuery.ajax({
                type : "post",
               // dataType : "json", 
                url : frontend_ajax_object.ajaxurl,
                data :  {'action':'osh_search_ajax', 'term': term},
                cache: false, 
                beforeSend: function(){
                    jQuery("#auto_search").text('Searching...');
                },
                success: function(data) { //console.log(data);
                    jQuery("#suggesstion-box").show();
                    jQuery("#suggesstion-box").html(data);
                    jQuery("#auto_search").css("background","#FFF");
                }
            });
    });      

});

/**
 * To select country name
 *
 * @param {jQuery} select
 * @param {Object} options
 * @returns {Multiselect}
 */
function selectSearch(val) {
    jQuery("#auto_search").val(val);
    jQuery("#suggesstion-box").hide();
}

     
var $window = $(window), $cdside=$('.cd_sidebar'), $cdwrap=$('.course_details_wrap');
function resize() {     
    var csideheight = $cdside.outerHeight();        
    $cdwrap.css("min-height",csideheight+80);
}
$window.resize(resize).trigger('resize');

/**
 * Check datepicker
 *
 * @param {jQuery} datepicker
 * @param {Object} options
 * @returns {Multiselect}
 */
$(document).ready(function(){
    if($( "#datepicker4" ).length){
        $( "#datepicker4" ).datepicker({
            changeMonth: true, 
            changeYear: true, 
            dateFormat: "dd/mm/yy",
            minDate: 0
        });
    }

    if($( "#datepicker5" ).length){
        $( "#datepicker5" ).datepicker({
            changeMonth: true, 
            changeYear: true, 
            dateFormat: "dd/mm/yy",
            minDate: 0
        });
    }

    /*--check script start--*/
  chekFun();
   $('input.chk').click(function() {
    chekFun();
   });


  $('.selall').click(function() {
    if($('.selall:checked').length){
      $('input.chk').prop('checked',true);
      $('.report_table tbody tr').addClass('tdselected');
      if(len<10){
        $('.chkcnt > span').html('0'+len);
      }
      else{
        $('.chkcnt > span').html(len);
      }       
    }
    else{
      $('input.chk').prop('checked',false);
      $('.report_table tbody tr').removeClass('tdselected');
      $('.chkcnt > span').html('0');     
    }    
    
  });

  $('.clearsel').click(function() {
    $('.selall').prop('checked',false); 
    $('input.chk').prop('checked',false);
    $('.report_table tbody tr').removeClass('tdselected');
    $('.chkcnt > span').html('00');
  });
 /*--check script ends--*/
});

/*--check script start--*/
var lencheck = $('input.chk:checked').length;
var len = $('input.chk').length;
function chekFun(){    
    $('input.chk').each(function(){
      if($(this).is(':checked')){
        $(this).closest('tr').addClass('tdselected');
      }
      else{
        $(this).closest('tr').removeClass('tdselected');
      }

      var lencheck = $('input.chk:checked').length;      
      var len = $('input.chk').length;
      if(lencheck == len){
        $('.selall').prop('checked',true); 
      }
      else{
        $('.selall').prop('checked',false); 
      }

      if(lencheck<10){
        $('.chkcnt > span').html('0'+lencheck);
      }
      else{
        $('.chkcnt > span').html(lencheck);
      } 
    });
}


/**
 * Couser Auto Search Suggestion
 *
 * @param {jQuery} keyup
 * @param {Object} options
 * @returns data
 */

jQuery( document ).ready(function(e){
    
    jQuery("#course_auto_search").keyup(function(){
        var term = jQuery('#course_auto_search').val();
        jQuery.ajax({
                type : "post",
               // dataType : "json", 
                url : frontend_ajax_object.ajaxurl,
                data :  {'action':'osh_course_search_ajax', 'term': term},
                cache: false, 
                beforeSend: function(){
                    jQuery("#course_auto_search").text('Searching...');
                    //jQuery("#course_auto_search").css("background","#FFF url('../wp-content/themes/hello-elementor/assets/images/Spinner-3.gif') no-repeat 165px");
                },
                success: function(data) { //console.log(data);
                    jQuery("#csuggesstion-box").show();
                    jQuery("#csuggesstion-box").html(data);
                    jQuery("#course_auto_search").css("background","#FFF");
                }
            });
    });    

});

/**
 * To select country name
 *
 * @param {jQuery} select
 * @param {Object} options
 * @returns data
 */
function selectSearchCourse(val) {
    jQuery("#course_auto_search").val(val);
    jQuery("#csuggesstion-box").hide();
}


/**
 * keyup User Auto Search
 *
 * @param {jQuery} keyup
 * @param {Object} options
 * @returns data
 */
jQuery( document ).ready(function(e){
    
    jQuery("#search_display_name").keyup(function(){
        var term = jQuery('#search_display_name').val();
        jQuery.ajax({
                type : "post",
                url : frontend_ajax_object.ajaxurl,
                data :  {'action':'osh_user_search_ajax', 'term': term},
                cache: false, 
                beforeSend: function(){
                    jQuery("#search_display_name").text('Searching...');                    
                },
                success: function(data) { //console.log(data);
                    jQuery("#user-sugg-box").show();
                    jQuery("#user-sugg-box").html(data);
                    jQuery("#search_display_name").css("background","#FFF");
                }
            });
    });    

});

/**
 * To select country name
 *
 * @param {jQuery} select
 * @param {Object} options
 * @returns data
 */
function selectSearchUser(val) {
    jQuery("#search_display_name").val(val);
    jQuery("#user-sugg-box").hide();
}


/**
 * Onclick Assign user menus
 *
 * @param {jQuery} click
 * @param {Object} options
 * @returns msg
 */
jQuery( document ).ready(function(e){
    
    jQuery("#assign_menu").click(function(){        
        
        jQuery.ajax({
                type : "post",
                dataType : "json", 
                url : frontend_ajax_object.ajaxurl,
                 data :  jQuery("#from_menu_list").serialize(),
                cache: false, 
                beforeSend: function(){
                    jQuery("#assign_menu").text('Assigning...');
                    jQuery('#assign_menu').attr("disabled","disabled");
                    jQuery('#from_menu_list').css("opacity",".5");
                },
                success: function(response) { //console.log(data);
                    if(response.msg == "success") {
                        swal.fire("Thank You!", response.msg_html, "success"); 
                        jQuery("#user-menu-updated-box").addClass('form-message success');
                        jQuery("#user-menu-updated-box").show();
                        jQuery("#user-menu-updated-box").html('Menu assigned successfully.');
                        setTimeout(function(){ window.location.reload(); }, 1000);
                          
                      }
                      else {
                        jQuery("#user-menu-updated-box").addClass('form-message error');
                        jQuery("#user-menu-updated-box").show();
                        jQuery("#user-menu-updated-box").html('Error! Please try after some times.');
                        setTimeout(function(){ window.location.reload(); }, 1000);
                      }
                   
                    
                }
            });
    });    

});

/**
 * Onclick Update user profile
 *
 * @param {jQuery} click
 * @param {Object} options
 * @returns msg
 */

jQuery( document ).ready(function(e){
    
    jQuery("#update_profile").click(function(){        
        
        jQuery.ajax({
                type : "post",
                dataType : "json", 
                url : frontend_ajax_object.ajaxurl,
                 data :  jQuery("#from_update_pro").serialize(),
                cache: false, 
                beforeSend: function(){
                    jQuery("#update_profile").text('Updating...');
                    jQuery('#update_profile').attr("disabled","disabled");
                    jQuery('#from_update_pro').css("opacity",".5");
                },
                success: function(response) { //console.log(data);
                    if(response.msg == "success") {
                        swal.fire("Thank You!", response.msg_html, "success");                         
                        setTimeout(function(){ window.location.reload(); }, 1000);
                          
                      }else if(response.msg == "empty") {
                        swal.fire("Oops...", response.msg_html, "error");                         
                        jQuery("#update_profile").text('Submit');
                        jQuery('#update_profile').prop("disabled", false);
                        jQuery('#from_update_pro').css("opacity",""); 
                          
                      }else {
                        swal.fire('Oops...', 'Error! Please try after some times', 'error');                        
                        setTimeout(function(){ window.location.reload(); }, 1000);
                      } 
                   
                    
                }
            });
    });    

});

/**
 * Onclick Assign form to users
 *
 * @param {jQuery} click
 * @param {Object} options
 * @returns msg
 */

jQuery( document ).ready(function(e){
    
    jQuery("#assign_form").click(function(){        
        
        jQuery.ajax({
                type : "post",
                dataType : "json", 
                url : frontend_ajax_object.ajaxurl,
                 data :  jQuery("#from_user_list").serialize(),
                cache: false, 
                beforeSend: function(){
                    jQuery("#assign_form").text('Assigning...');
                    jQuery('#assign_form').attr("disabled","disabled");
                    jQuery('#from_user_list').css("opacity",".5");
                },
                success: function(response) { console.log(response);
                    if(response.msg == "success") {
                        swal.fire("Thank You!", response.msg_html, "success");                         
                        setTimeout(function(){ window.location.reload(); }, 1000);
                          
                      }else if(response.msg == "empty") {
                        swal.fire("Oops...", response.msg_html, "error");                         
                        setTimeout(function(){ window.location.reload(); }, 1000);
                          
                      }else {
                        swal.fire('Oops...', 'Error! Please try after some times', 'error');                        
                        setTimeout(function(){ window.location.reload(); }, 1000);
                      }                      
                   
                    
                }
            });
    });    

});

/**
 * Onclick Set read to unread msg on action items
 *
 * @param {jQuery} click
 * @param {Object} options
 * @returns msg
 */ 

jQuery( document ).ready(function(e){
    
    jQuery(".notifi_pop").click(function(){        
        
        jQuery.ajax({
                type : "post",
                url : frontend_ajax_object.ajaxurl,
                data :  {'action':'clear_action_items_ajax'},
                cache: false, 
                beforeSend: function(){
                    jQuery("#search_display_name").text('Searching...');                    
                },
                success: function(data) { 
                    if(response.msg == "success") {                       
                        setTimeout(function(){ window.location.reload(); }, 1000);
                          
                      }

                }
            });
    });    

});


jQuery( document ).ready(function(e){    

    /**
     * Onclick Add Document
     *
     * @param {jQuery} click
     * @param {Object} options
     * @returns msg
     */
    jQuery.validator.addMethod("noSpace", function(value, element) { 
                return value.replace(/^\s+|\s+$/g, "").length != 0 && value != ""; 
              }, "Please don't leave it empty");

    jQuery('#add_doc_btn').click(function(){ 
    //FORM VALIDATION: validates on submit
        
          jQuery("#form_add_doc").validate({
            rules: {
                doc_title: {           //input name: doc_title
                    required: true ,
                    noSpace: true,
                    minlength: 7,
                    maxlength: 50   
                },
                "doc_cat_id[]": {           //input name: doc_cat_id
                    required: true 
                },
                doc_details: {           //input name: doc_details
                    required: true ,
                    minlength: 10    
                },
                doc_attachment: {           //input name: doc_attachment
                    required: true ,
                    extension: "pdf" ,
                    //extension: "docx|rtf|doc|pdf" 
                    accept: "pdf"
                }
            },
            messages: {               //messages to appear on error
                doc_title: 'Please enter document title (min 7 and max 50 characters)',
                "doc_cat_id[]": 'Please select document category', 
                doc_details: 'Please enter document details (minimum 10 characters)',
                //doc_attachment: 'Please select valid input file format'
                doc_attachment:{
                    //required:"Please select valid input file format",
                    required:"Please select a file",
                    extension:"Select valid input file format"
                }              
            },
            submitHandler: function(form) {   
                    var doc_title = $('form#form_add_doc #doc_title').val();
                    var doc_cat_id = $('form#form_add_doc #doc_cat_id').val();
                    var doc_details = $('form#form_add_doc #doc_details').val();
                    var _availability_nonce = $('form#form_add_doc #_availability_nonce').val(); 
                    var formData = new FormData();
                    formData.append('doc_attachment', jQuery('input[name="doc_attachment"]')[0].files[0]);
                    formData.append('action', 'osh_add_doc_form_ajax');
                    formData.append('doc_title', doc_title);
                    formData.append('doc_cat_id', doc_cat_id);  
                    formData.append('doc_details', doc_details); 
                    formData.append('_availability_nonce', _availability_nonce);           
                    jQuery.ajax({
                        type : "post",
                        dataType : "json", 
                        url : frontend_ajax_object.ajaxurl,
                        data :  formData,
                        "timeout": 0,         
                        contentType: false,
                        processData:false,
                      beforeSend: function(){
                            jQuery("#add_doc_btn").text('Adding...');
                            jQuery('#add_doc_btn').attr("disabled","disabled");
                            jQuery('#form_add_doc').css("opacity",".5");
                          },
                      success: function(response) { console.log(response);
                        if(response.msg == "success") {
                            swal.fire("Thank You!", response.msg_html, "success");                         
                            setTimeout(function(){ window.location.reload(); }, 1000);
                          
                        }else if(response.msg == "error") {
                            swal.fire('Oops...', response.msg_error, "error"); 
                            jQuery("#add_doc_btn").text('Submit');
                            jQuery('#add_doc_btn').prop("disabled", false);
                            jQuery('#form_add_doc').css("opacity","");                        
                          
                        }else {
                            swal.fire('Oops...', 'Error! Please try after some times', 'error');                        
                            setTimeout(function(){ window.location.reload(); }, 1000);
                        }                      
                    }
                });
            }  
        });
    });

    /**
     * Onclick Edit Document.
     *
     * @param {jQuery} click validate
     * @param {Object} options
     * @returns msg
     */
    jQuery('#edit_doc_btn').click(function(){ 
    //FORM VALIDATION: validates on submit

        jQuery("#form_edit_doc").validate({
            rules: {
                doc_title: {           //input name: doc_title
                    required: true ,
                    noSpace: true,
                    minlength: 7,
                    maxlength: 50   
                },
                "doc_cat_id[]": {           //input name: doc_cat_id
                    required: true 
                },
                doc_details: {           //input name: doc_details
                    required: true ,
                    minlength: 10    
                },
            },
            messages: {               //messages to appear on error
                doc_title: 'Please enter document title (min 7 and max 50 characters)',
                "doc_cat_id[]": 'Please select document category', 
                doc_details: 'Please enter document details (minimum 10 characters)',
            },
            submitHandler: function(form) {   
                    var redirect_url = $('form#form_edit_doc #redirect_url').val();
                    var post_id = $('form#form_edit_doc #post_id').val();
                    var doc_title = $('form#form_edit_doc #doc_title').val();
                    var doc_cat_id = $('form#form_edit_doc #doc_cat_id').val();
                    var doc_details = $('form#form_edit_doc #doc_details').val();
                    var _availability_nonce = $('form#form_edit_doc #_availability_nonce').val(); 
                    var formData = new FormData();
                    formData.append('doc_attachment', jQuery('input[name="doc_attachment"]')[0].files[0]);
                    formData.append('action', 'osh_edit_doc_form_ajax');
                    formData.append('post_id', post_id);
                    formData.append('doc_title', doc_title);
                    formData.append('doc_cat_id', doc_cat_id);  
                    formData.append('doc_details', doc_details); 
                    formData.append('_availability_nonce', _availability_nonce);           
                    jQuery.ajax({
                        type : "post",
                        dataType : "json", 
                        url : frontend_ajax_object.ajaxurl,
                        data :  formData,
                        "timeout": 0,         
                        contentType: false,
                        processData:false,
                      beforeSend: function(){
                            jQuery("#edit_doc_btn").text('Updating…');
                            jQuery('#edit_doc_btn').attr("disabled","disabled");
                            jQuery('#form_edit_doc').css("opacity",".5");
                          },
                      success: function(response) { console.log(response);
                        if(response.msg == "success") {
                            swal.fire("Thank You!", response.msg_html, "success");                        
                            setTimeout(function(){ window.location.href = redirect_url; }, 1000);
                            
                      }else {
                        swal.fire('Oops...', 'Error! Please try after some times', 'error');                        
                        setTimeout(function(){ window.location.reload(); }, 1000);
                      }                      
                    }
                });
            }  
        });
    });


    /**
     * Onclick Revert Document
     *
     * @param {jQuery} click
     * @param {Object} options
     * @returns msg
     */
     jQuery("#revert_yes").click(function(){        
        var post_id = $('form#form_revert_doc #post_id').val();
        var _availability_nonce = $('form#form_revert_doc #_availability_nonce').val();
        jQuery.ajax({
                type : "post",
                dataType : "json", 
                url : frontend_ajax_object.ajaxurl,
                data :  {'action':'osh_revert_doc_ajax', 'post_id': post_id, '_availability_nonce': _availability_nonce},
                cache: false, 
                beforeSend: function(){
                    jQuery("#search_display_name").text('Searching...');                    
                },
                success: function(response) { 
                    if(response.msg == "success") {                       
                        setTimeout(function(){ window.location.reload(); }, 1000);
                          
                      }

                }
            });
    });  

    /**
     * Onclick Replace Document
     *
     * @param {jQuery} click validate
     * @param {Object} options
     * @returns msg
     */
    jQuery('#replace_yes').click(function(){ 
    //FORM VALIDATION: validates on submit

        jQuery("#form_rep_doc").validate({
            rules: {                
                rep_attachment: {           //input name: doc_attachment
                    required: true                     
                }
            },
            messages: {              //messages to appear on error
                rep_attachment: 'Please select valid input file format'
                              
            },
            submitHandler: function(form) {   
                    var post_id = $('form#form_rep_doc #post_id').val();
                    var _availability_nonce = $('form#form_rep_doc #_availability_nonce').val(); 
                    var formData = new FormData();
                    formData.append('rep_attachment', jQuery('input[name="rep_attachment"]')[0].files[0]);
                    formData.append('action', 'osh_rep_doc_form_ajax');
                    formData.append('post_id', post_id);
                    formData.append('_availability_nonce', _availability_nonce);           
                    jQuery.ajax({
                        type : "post",
                        dataType : "json", 
                        url : frontend_ajax_object.ajaxurl,
                        data :  formData,
                        "timeout": 0,         
                        contentType: false,
                        processData:false,
                      success: function(response) { console.log(response);
                        if(response.msg == "success") {  
                        jQuery('#replace').modal('hide'); 
                        jQuery('#replaceYes').modal('show');                     
                        setTimeout(function(){ window.location.reload(); }, 1000);
                          
                      }                      
                    }
                });
            }  
        });
    });

    /**
     * Onclick modal close
     *
     * @param {jQuery} select
     * @param {Object} options
     * @returns null
     */
     jQuery('.cancelbtn').click(function(){ 
            jQuery('.modal').modal('toggle');
        });
    
     
});
