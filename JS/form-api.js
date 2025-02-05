 /**
 * Form api.js.
 * Version: 01.01.01
 * Lic : OHS
 * Theme Customizer enhancements for a better front API.
 *
 */

/**
 * Onclick Retireve System Codes
 *
 * @param {jQuery} click
 * @param {Object} options
 * @returns msg
 */

function retrvEmpCode($employerIdentifier) { 
    console.log($employerIdentifier);
    var formData = new FormData();         
    formData.append('action', 'retrieve_emp_code');
    formData.append('employerIdentifier', $employerIdentifier);          
          
        jQuery.ajax({
          type : "post",
          dataType : "json", 
          url : frontend_ajax_object.ajaxurl,
          data :  formData,
          "timeout": 0, 
          cache: false,        
          contentType: false,
          processData:false,            
            beforeSend: function(){
                  jQuery("#retrv_emp_code").text('Calling...');
                  jQuery('#retrv_emp_code').attr("disabled","disabled");
            
              },
            success: function(response) { 
                jQuery("#retrv_emp_code").text('Retireve System Codes');
                jQuery('#retrv_emp_code').removeAttr("disabled");
                //console.log(response);
                    if(response.msg == "success") {
                      swal.fire("Thank You!", response.msg_html, "success");                        
                      setTimeout(function(){ window.location.reload(); }, 1000);
                      
                    }else if(response.msg == "error") {
                        swal.fire('Oops...', response.msg_error, "error"); 
                        jQuery("#edit_pkdoc_btn").text('Save');
                        jQuery('#edit_pkdoc_btn').prop("disabled", false);
                        jQuery('#form_edit_pkdoc').css("opacity","");                        
                      
                    }else {
                      swal.fire('Oops...', 'Error! Please try after some times', 'error');                        
                      setTimeout(function(){ window.location.reload(); }, 1000);
                  }                      
                }
              });
      return false;
}


/**
 * Onclick Retireve Incident Status
 *
 * @param {jQuery} click
 * @param {Object} options
 * @returns msg
 */

function retrvIncStatus($employerIdentifier) { 
    console.log($employerIdentifier);
    jQuery('#retrv_inc_status').modal('show');
    var formData = new FormData();         
    formData.append('action', 'retrieve_inc_status');
    formData.append('employerIdentifier', $employerIdentifier);          
          
        jQuery.ajax({
          type : "post",
          dataType : "json", 
          url : frontend_ajax_object.ajaxurl,
          data :  formData,
          "timeout": 0, 
          cache: false,        
          contentType: false,
          processData:false,            
            beforeSend: function(){
                  
                jQuery("#retrv_inc_status .modal-body").text('Please wait...');
            
              },
            success: function(response) { 
                console.log(response);
                if(response.msg == "success") {
                  
                  jQuery("#retrv_inc_status .modal-body").html(response.msg_html); 
                  
                  }
                  else {
                      
                      jQuery("#retrv_inc_status .modal-body").html(response.msg);
                  }                    
                }
      });
      
      return false;
}


/**
 * Onclick Retireve System Codes
 *
 * @param {jQuery} click
 * @param {Object} options
 * @returns msg
 */

function retrvSysCode() { 
    var formData = new FormData();         
    formData.append('action', 'retrieve_sys_code');          
        jQuery.ajax({
          type : "post",
          dataType : "json", 
          url : frontend_ajax_object.ajaxurl,
          data :  formData,
          "timeout": 0, 
          cache: false,        
          contentType: false,
          processData:false,            
            beforeSend: function(){
                  jQuery("#retrv_sys_code").text('Calling...');
                  jQuery('#retrv_sys_code').attr("disabled","disabled");
            
              },
            success: function(response) { 

                jQuery("#retrv_sys_code").text('Retireve System Codes');
                jQuery('#retrv_sys_code').removeAttr("disabled");

                //console.log(response);
                    if(response.msg == "success") {
                      swal.fire("Thank You!", response.msg_html, "success");                        
                      setTimeout(function(){ window.location.reload(); }, 1000);
                      
                    }else if(response.msg == "error") {
                        swal.fire('Oops...', response.msg_error, "error"); 
                        jQuery("#edit_pkdoc_btn").text('Save');
                        jQuery('#edit_pkdoc_btn').prop("disabled", false);
                        jQuery('#form_edit_pkdoc').css("opacity","");                        
                        //setTimeout(function(){ window.location.reload(); }, 1000);
                      
                    }else {
                      swal.fire('Oops...', 'Error! Please try after some times', 'error');                        
                      setTimeout(function(){ window.location.reload(); }, 1000);
                  }                      
                }
              });
      return false;
}