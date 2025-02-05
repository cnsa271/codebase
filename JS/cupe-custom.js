/**
 * Custom.js.
 * Version: 01.01.01
 * Lic : OHS
 * Theme Customizer enhancements for a better admin experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */
$(window).on("load", function(){
  $(".ajax_loader").addClass('hideloader');
});
$(document).ready(function() {
 
      
    $(document).on("click",".resnav",function(){ 
      $("html").addClass("showMenu");
    });

    $(document).on("click",".close_menu,.res_overlay",function(){
      $("html").removeClass("showMenu");
    });

    $(document).on("click",".strlist .dropdown-item",function(){
      var strtext = $(this).text();
      $("#srtd").html(strtext);
    });


    $(".spnav").mCustomScrollbar({
        axis:"x",        
        autoExpandScrollbar:true,
        advanced:{autoExpandHorizontalScroll:true}
    });


    $(document).on("click",".viewBtn",function(){
      $('html').addClass('viewDetails');     
    });

    $(document).on("click",".viewClose, .viewClosecross, .overlayall",function(){
      $('html').removeClass('viewDetails');      
    });

    // Resume pop js
    $(document).on("click",".openresm",function(){
      $(".sidepopup_inner").animate({ scrollTop: 150 }, "fast");
      $('.popinbox').addClass('showRpop');     
    });

    $(document).on("click",".backpop, .closeresm",function(){
      $('.popinbox').removeClass('showRpop');      
    });

    // textarea editor
    // https://summernote.org/examples/
    if($('.summernote').length){
      $('.summernote').summernote();    
    }

    //close sidebar
    $(document).on("click",".left_close",function(){
      $('html').toggleClass('closeLeft');
      setTimeout(function(){
        $(".hscroll").mCustomScrollbar("update");
      }, 1000);
      
    });
   

    // Application page Horizontal scroll and footer
    var $window = $(window), $hscrl = $(".hscroll"), $apcon = $('.applicant_container'), $apbody = $('.sa_app_body'), $aphead = $('.applicant_header'), $apfoot = $('.landing-cupe-footer');
    function resize() {   
        var windowWidth = $window.width();       

        if(windowWidth > 479){            

            if($hscrl.length){
              $hscrl.mCustomScrollbar({
                  axis:"x",        
                  autoExpandScrollbar:true,
                  advanced:{autoExpandHorizontalScroll:true}
              });
            }
        }


        if( $window.height() > ($apbody.outerHeight() + $aphead.outerHeight() +$apfoot.outerHeight() ) ){
          $('html').addClass('fixed_footer');
        }
        else{
          $('html').removeClass('fixed_footer');
        }
          
    }
    $window.resize(resize).trigger('resize');

    // Choose position
    $('#job_position').submit(function() {
      if ($.trim($("#job_type").val()) === "") {
        $("#error_choose_position").show();
        $("#job_type").parent().find(".select2-selection--multiple").css("border-color", "red");
        return false;
      }
    });

    $("#job_type").on("change", function(){
      $("#job_type").parent().find(".select2-selection--multiple").removeAttr("style");
      $("#error_choose_position").hide();
    });
});

/**
 * Generl Application Fields and validation
 *
 * @param {jQuery} select
 * @param {Object} options
 * @returns {Multiselect}
 */
jQuery( document ).ready(function(e){

  //------start------personal section-------
  jQuery(".personal.fp_nextbtn").click(function(){
    var valid = true;
    var emailReg =/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      var phoneReg=/([0-9]{10})|(\([0-9]{3}\)\s+[0-9]{3}\-[0-9]{4})/;
      var name_prefix = jQuery('#name_prefix').val();
      var first_name = jQuery('#first_name').val();
      var last_name = jQuery('#last_name').val();
      var address = jQuery('#address').val();
      var city = jQuery('#city').val();
      var country = jQuery('#country').val();
      var postal_code = jQuery('#postal_code').val();
      var your_phone = jQuery('#your_phone').val();
      var your_email = jQuery('#your_email').val();    
      var employed = jQuery('#employed').val();
      var entitled = jQuery('#entitled').val();
      var position = jQuery('#position').val();
      var communicate = jQuery('#communicate').val();
      var aboriginal = jQuery('#aboriginal').val();
      var certificate = jQuery('#certificate').is(':checked');

      if(first_name =='' || first_name == null){
          jQuery('#error_cupe_first_name').show();
          jQuery('#first_name').css('border-color', 'red');
          valid = false;
      }else{
          jQuery('#error_cupe_first_name').hide();
          jQuery('#first_name').css('border-color', '');
      }

      if(last_name =='' || last_name == null){
          jQuery('#error_cupe_last_name').show();
          jQuery('#last_name').css('border-color', 'red');
          valid = false;
      }else{
          jQuery('#error_cupe_last_name').hide();
          jQuery('#last_name').css('border-color', '');
      }

      if(address =='' || address == null){
        jQuery('#error_cupe_address').show();
        jQuery('#address').css('border-color', 'red');
        valid=false;
      }else{
        jQuery('#error_cupe_address').hide();
        jQuery('#address').css('border-color', '');
      }

      if(city =='' || city == null){
        jQuery('#error_cupe_city').show();
        jQuery('#city').css('border-color', 'red');
        valid=false;
      }else{
        jQuery('#error_cupe_city').hide();
        jQuery('#city').css('border-color', '');
      }


      if(country =='' || country == null){
        jQuery('#error_cupe_country').show();
        jQuery('#country').css('border-color', 'red');
        valid=false;
      }else{
        jQuery('#error_cupe_country').hide();
        jQuery('#country').css('border-color', '');
      }

      if(postal_code =='' || postal_code == null){
        jQuery('#error_cupe_postal_code').show();
        jQuery('#postal_code').css('border-color', 'red');
        valid=false;
      }else{
        jQuery('#error_cupe_postal_code').hide();
        jQuery('#postal_code').css('border-color', '');
      }     
      console.log(your_phone);
      if(your_phone =='' || your_phone == null){
          jQuery('#error_cupe_your_phone').show();
          jQuery('#your_phone').css('border-color', 'red');
          valid = false;
      }else{
        if(phoneReg.test(your_phone) == false){
          jQuery('#error_cupe_your_phone').show();
          jQuery('#your_phone').css('border-color', 'red');
          jQuery('#your_phone').val('');
          valid = false;
        }else{
          jQuery('#error_cupe_your_phone').hide();
          jQuery('#your_phone').css('border-color', '');
        }

      }
      
      if(your_email =='' || your_email == null){
        jQuery('#error_cupe_your_email').show();
        jQuery('#your_email').css('border-color', 'red');
        valid=false;
      }else{
        if(emailReg.test(your_email) == false){
          jQuery('#error_cupe_your_email').hide();
          jQuery('#error_cupe_your_emailv').show();
          jQuery('#your_email').css('border-color', 'red');
          valid = false;
        }else{
          jQuery('#error_cupe_your_email').hide();
          jQuery('#error_cupe_your_emailv').hide();
          jQuery('#your_email').css('border-color', '');
        }

      }   

      if(employed =='' || employed == null){
          jQuery('#error_cupe_employed').show();
          jQuery('#employed').css('border-color', 'red');
          valid = false;
      }else{
          jQuery('#error_cupe_employed').hide();
          jQuery('#employed').css('border-color', '');
      }

      if(entitled =='' || entitled == null){
          jQuery('#error_cupe_entitled').show();
          jQuery('#entitled').css('border-color', 'red');
          valid = false;
      }else{
          jQuery('#error_cupe_entitled').hide();
          jQuery('#entitled').css('border-color', '');
      }

      if(position =='' || position == null){
          jQuery('#error_cupe_position').show();
          jQuery('#position').css('border-color', 'red');
          valid = false;
      }else{
          jQuery('#error_cupe_position').hide();
          jQuery('#position').css('border-color', '');
      }

      if(communicate =='' || communicate == null){
          jQuery('#error_cupe_communicate').show();
          jQuery('#communicate').css('border-color', 'red');
          valid = false;
      }else{
          jQuery('#error_cupe_communicate').hide();
          jQuery('#communicate').css('border-color', '');
      }

      if(aboriginal =='' || aboriginal == null){
          jQuery('#error_cupe_aboriginal').show();
          jQuery('#aboriginal').css('border-color', 'red');
          valid = false;
      }else{
          jQuery('#error_cupe_aboriginal').hide();
          jQuery('#aboriginal').css('border-color', '');
      } 
      
    //------End------Validating form-------
    if(valid == true){  
      jQuery(".fscol.education").addClass("completed");
      jQuery(".formcontent.education").addClass("active");    
      //jQuery(".fscol.personal").removeClass("completed");
      jQuery(".formcontent.personal").removeClass("active");
    }else{
      $('html, body').animate({ 
        scrollTop: $("#first_name").offset().top
      }, 2000);
    }
  });
  //------end------personal section-------

  //------start------Secondary Education-------
  jQuery(".education.fprevbtn").click(function(){    
      jQuery(".fscol.personal").addClass("completed");
      jQuery(".formcontent.personal").addClass("active");    
      jQuery(".fscol.education").removeClass("completed");
      jQuery(".formcontent.education").removeClass("active");
  });

  jQuery(".education.fp_nextbtn").click(function(){
    var valid = true;
    var se_sc_name = jQuery('#se_sc_name').val();
    var se_start_date = jQuery('#se_start_date').val();
    var se_end_date = jQuery('#se_end_date').val();
    //var se_cou_spec = jQuery('#se_cou_spec').val();
    var se_last_grade = jQuery('#se_last_grade').val();
    var pse_sc_name = jQuery('#pse_sc_name').val();
    var pse_start_date = jQuery('#pse_start_date').val();
    var pse_end_date = jQuery('#pse_end_date').val();
    var ot_sc_name = jQuery('#ot_sc_name').val();
    var ot_start_date = jQuery('#ot_start_date').val();
    var ot_end_date = jQuery('#ot_end_date').val();

      if(se_sc_name =='' || se_sc_name == null){
          jQuery('#error_cupe_se_sc_name').show();
          jQuery('#se_sc_name').css('border-color', 'red');
          valid = false;
      }else{
          jQuery('#error_cupe_se_sc_name').hide();
          jQuery('#se_sc_name').css('border-color', '');
      }

      if(se_start_date =='Course start date' || se_start_date == null){
          jQuery('#error_cupe_se_start_date').show();
          jQuery('#se_start_date').css('border-color', 'red');
          valid = false;
      }else{
          jQuery('#error_cupe_se_start_date').hide();
          jQuery('#se_start_date').css('border-color', '');
      }

      if(se_end_date =='Course end date' || se_end_date == null){
        jQuery('#error_cupe_se_end_date').show();
        jQuery('#se_end_date').css('border-color', 'red');
        valid=false;
      }else{
        jQuery('#error_cupe_se_end_date').hide();
        jQuery('#se_end_date').css('border-color', '');
      }    

      if(new Date(se_end_date) <= new Date(se_start_date)){
        jQuery('#error_cupe_se_end_date_less').show();
        jQuery('#se_end_date').css('border-color', 'red');
        valid=false;
      }else{
        jQuery('#error_cupe_se_end_date_less').hide();
        jQuery('#se_end_date').css('border-color', '');
      }  

      if(se_last_grade =='' || se_last_grade == null){
        jQuery('#error_cupe_se_last_grade').show();
        jQuery('#se_last_grade').css('border-color', 'red');
        valid=false;
      }else{
        jQuery('#error_cupe_se_last_grade').hide();
        jQuery('#se_last_grade').css('border-color', '');
      }

      if(new Date(pse_end_date) <= new Date(pse_start_date)){
        jQuery('#error_cupe_pse_end_date_less').show();
        jQuery('#pse_end_date').css('border-color', 'red');
        valid=false;
      }else{
        jQuery('#error_cupe_pse_end_date_less').hide();
        jQuery('#pse_end_date').css('border-color', '');
      }

      
    //------End------Secondary Education Form Validating-------
      if(valid == true){  
      jQuery(".fscol.general").addClass("completed");
      jQuery(".formcontent.general").addClass("active");    
      //jQuery(".fscol.education").removeClass("completed");
      jQuery(".formcontent.education").removeClass("active");
    }else{
      $('html, body').animate({ 
        scrollTop: $("#pse_sc_name").offset().top
      }, 2000);
    }
  });
  //------end------Secondary Education-------

  //------start------General Questions section-------
   jQuery(".general.fprevbtn").click(function(){    
      jQuery(".fscol.education").addClass("completed");
      jQuery(".formcontent.education").addClass("active");    
      jQuery(".fscol.general").removeClass("completed");
      jQuery(".formcontent.general").removeClass("active");
  });
  jQuery(".general.fp_nextbtn").click(function(){    
      jQuery(".fscol.position").addClass("completed");
      jQuery(".formcontent.position").addClass("active");    
      //jQuery(".fscol.general").removeClass("completed");
      jQuery(".formcontent.general").removeClass("active");
  });
  //------end------General Questions section

  //------start----Questions For Applicants Positions section
   jQuery(".position.fprevbtn").click(function(){    
      jQuery(".fscol.general").addClass("completed");
      jQuery(".formcontent.general").addClass("active");    
      jQuery(".fscol.position").removeClass("completed");
      jQuery(".formcontent.position").removeClass("active");
  });
  jQuery(".position.fp_nextbtn").click(function(){    
      jQuery(".fscol.history").addClass("completed");
      jQuery(".formcontent.history").addClass("active");    
      //jQuery(".fscol.position").removeClass("completed");
      jQuery(".formcontent.position").removeClass("active");
  });
  //------end------Questions For Applicants Positions section


  //------start----Employment History section
   jQuery(".history.fprevbtn").click(function(){    
      jQuery(".fscol.position").addClass("completed");
      jQuery(".formcontent.position").addClass("active");    
      jQuery(".fscol.history").removeClass("completed");
      jQuery(".formcontent.history").removeClass("active");
  });

  jQuery(".history.fp_nextbtn").click(function(){
    var valid = true;
    var eh_one_emp_name = jQuery('#eh_one_emp_name').val();
    var eh_one_sup_name = jQuery('#eh_one_sup_name').val();
    var eh_one_con_no_emp = jQuery('#eh_one_con_no_emp').val();
    var eh_one_position = jQuery('#eh_one_position').val();
    var eh_one_emp_start_date = jQuery('#eh_one_emp_start_date').val();
    var eh_one_emp_end_date = jQuery('#eh_one_emp_end_date').val();
    var eh_one_reason_lev = jQuery('#eh_one_reason_lev').val();

      if(eh_one_emp_name =='' || eh_one_emp_name == null){
          jQuery('#error_cupe_eh_one_emp_name').show();
          jQuery('#eh_one_emp_name').css('border-color', 'red');
          valid = false;
      }else{
          jQuery('#error_cupe_eh_one_emp_name').hide();
          jQuery('#eh_one_emp_name').css('border-color', '');
      }

      if(eh_one_sup_name =='' || eh_one_sup_name == null){
          jQuery('#error_cupe_eh_one_sup_name').show();
          jQuery('#eh_one_sup_name').css('border-color', 'red');
          valid = false;
      }else{
          jQuery('#error_cupe_eh_one_sup_name').hide();
          jQuery('#eh_one_sup_name').css('border-color', '');
      }

      if(eh_one_con_no_emp =='' || eh_one_con_no_emp == null){
        jQuery('#error_cupe_eh_one_con_no_emp').show();
        jQuery('#eh_one_con_no_emp').css('border-color', 'red');
        valid = false;
      }else{
        jQuery('#error_cupe_eh_one_con_no_emp').hide();
        jQuery('#eh_one_con_no_emp').css('border-color', '');
      }      

      if(eh_one_position =='' || eh_one_position == null){
        jQuery('#error_cupe_eh_one_position').show();
        jQuery('#eh_one_position').css('border-color', 'red');
        valid = false;
      }else{
        jQuery('#error_cupe_eh_one_position').hide();
        jQuery('#eh_one_position').css('border-color', '');
      }

      if(eh_one_emp_start_date =='' || eh_one_emp_start_date == null){
        jQuery('#error_cupe_eh_one_emp_start_date').show();
        jQuery('#eh_one_emp_start_date').css('border-color', 'red');
        valid = false;
      }else{
        jQuery('#error_cupe_eh_one_emp_start_date').hide();
        jQuery('#eh_one_emp_start_date').css('border-color', '');
      }

      if(eh_one_emp_end_date =='' || eh_one_emp_end_date == null){
        jQuery('#error_cupe_eh_one_emp_end_date').show();
        jQuery('#eh_one_emp_end_date').css('border-color', 'red');
        valid = false;
      }else{
        jQuery('#error_cupe_eh_one_emp_end_date').hide();
        jQuery('#eh_one_emp_end_date').css('border-color', '');
      }

      if(new Date(eh_one_emp_end_date) <= new Date(eh_one_emp_start_date)){
        jQuery('#error_eh_one_emp_end_date_less').show();
        jQuery('#eh_one_emp_end_date').css('border-color', 'red');
        valid = false;
      }else{
        jQuery('#error_eh_one_emp_end_date_less').hide();
        jQuery('#eh_one_emp_end_date').css('border-color', '');
      }     

      if(eh_one_reason_lev =='' || eh_one_reason_lev == null){
          jQuery('#error_cupe_eh_one_reason_lev').show();
          jQuery('#eh_one_reason_lev').css('border-color', 'red');
          valid = false;
      }else{
          jQuery('#error_cupe_eh_one_reason_lev').hide();
          jQuery('#eh_one_reason_lev').css('border-color', '');
      } 

    var eh_two_emp_name = jQuery('#eh_two_emp_name').val();
    var eh_two_sup_name = jQuery('#eh_two_sup_name').val();
    var eh_two_con_no_emp = jQuery('#eh_two_con_no_emp').val();
    var eh_two_position = jQuery('#eh_two_position').val();
    var eh_two_emp_start_date = jQuery('#eh_two_emp_start_date').val();
    var eh_two_emp_end_date = jQuery('#eh_two_emp_end_date').val();
    var eh_two_reason_lev = jQuery('#eh_two_reason_lev').val();

      
      var eh_three_emp_name = jQuery('#eh_three_emp_name').val();
      var eh_three_sup_name = jQuery('#eh_three_sup_name').val();
      var eh_three_con_no_emp = jQuery('#eh_three_con_no_emp').val();
      var eh_three_position = jQuery('#eh_three_position').val();
      var eh_three_emp_start_date = jQuery('#eh_three_emp_start_date').val();
      var eh_three_emp_end_date = jQuery('#eh_three_emp_end_date').val();
      var eh_three_reason_lev = jQuery('#eh_three_reason_lev').val();

        
      
    //------End------Validating form-------
      if(valid == true){ 
      jQuery(".fscol.resume").addClass("completed");
      jQuery(".formcontent.resume").addClass("active");    
      //jQuery(".fscol.history").removeClass("completed");
      jQuery(".formcontent.history").removeClass("active");
    }else{
      $('html, body').animate({ 
        scrollTop: $("#eh_one_con_no_emp").offset().top
      }, 2000);
    }
  });

/**
 * Upload Your Resume
 *
 * @param {jQuery} select
 * @param {Object} options
 * @returns {Multiselect}
 */

   jQuery(".resume.fprevbtn").click(function(){    
      jQuery(".fscol.history").addClass("completed");
      jQuery(".formcontent.history").addClass("active");    
      jQuery(".fscol.resume").removeClass("completed");
      jQuery(".formcontent.resume").removeClass("active");
  });

  jQuery(".resume.fp_nextbtn").click(function(){
    var valid = true;
    var resume = jQuery('.resume .drop-zone__thumb').attr('data-label');
      if(typeof resume === "undefined"){
        var extension = '';
      }else{
        var extension = resume.split('.').pop().toLowerCase();
      }
      if(typeof resume === "undefined"){
          jQuery('#error_cupe_upload_resume').show();
          jQuery('#upload_resume').css('border-color', 'red');
          valid = false;
      }else if(extension != 'doc' && extension != 'docx' && extension != 'txt' && extension != 'pdf'){
          jQuery('#error_cupe_upload_resume').show();
          jQuery('#upload_resume').css('border-color', 'red');
          valid = false;
      }else{
          jQuery('#error_cupe_upload_resume').hide();
          jQuery('#upload_resume').css('border-color', '');
      }
        
    
    //------End------Validating form-------
    if(valid == true){  
      jQuery(".fscol.reference").addClass("completed");
      jQuery(".formcontent.reference").addClass("active");    
      //jQuery(".fscol.resume").removeClass("completed");
      jQuery(".formcontent.resume").removeClass("active");
    }
  });

/**
 * Reference Details Section
 *
 * @param {jQuery} select
 * @param {Object} options
 * @returns {Multiselect}
 */
   jQuery(".reference.fprevbtn").click(function(){    
      jQuery(".fscol.resume").addClass("completed");
      jQuery(".formcontent.resume").addClass("active");    
      jQuery(".fscol.reference").removeClass("completed");
      jQuery(".formcontent.reference").removeClass("active");
  });
   
  jQuery(".reference.fp_nextbtn").click(function(){
    var valid = true;
    var srdEmailReg =/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    var srd_ref_one_fname = jQuery('#srd_ref_one_fname').val();
    var srd_ref_one_lname = jQuery('#srd_ref_one_lname').val();
    var srd_ref_one_phone = jQuery('#srd_ref_one_phone').val();
    var srd_ref_one_email = jQuery('#srd_ref_one_email').val();
    var srd_ref_one_job_title = jQuery('#srd_ref_one_job_title').val();
    var srd_ref_one_desc = jQuery('#srd_ref_one_desc').val();
    var srd_ref_one_cover_ltr_yes = jQuery('#srd_ref_one_cover_ltr_yes').is(':checked');
    var srd_ref_one_cover_ltr = jQuery('#srd_ref_one_cover_ltr').val();
    

      if(srd_ref_one_fname =='' || srd_ref_one_fname == null){
          jQuery('#error_cupe_srd_ref_one_fname').show();
          jQuery('#srd_ref_one_fname').css('border-color', 'red');
          valid = false;
      }else{
          jQuery('#error_cupe_srd_ref_one_fname').hide();
          jQuery('#srd_ref_one_fname').css('border-color', '');
      }

      if(srd_ref_one_lname =='' || srd_ref_one_lname == null){
          jQuery('#error_cupe_srd_ref_one_lname').show();
          jQuery('#srd_ref_one_lname').css('border-color', 'red');
          valid = false;
      }else{
          jQuery('#error_cupe_srd_ref_one_lname').hide();
          jQuery('#srd_ref_one_lname').css('border-color', '');
      }

      if(srd_ref_one_phone =='' || srd_ref_one_phone == null){
        jQuery('#error_cupe_srd_ref_one_phone').show();
        jQuery('#srd_ref_one_phone').css('border-color', 'red');
        valid=false;
      }else{
        jQuery('#error_cupe_srd_ref_one_phone').hide();
        jQuery('#srd_ref_one_phone').css('border-color', '');
      }  
      
      if(srd_ref_one_email =='' || srd_ref_one_email == null){
        jQuery('#error_cupe_your_email').show();
        jQuery('#srd_ref_one_email').css('border-color', 'red');
        valid=false;
      }else{
        if(srdEmailReg.test(srd_ref_one_email) == false){
          jQuery('#error_cupe_srd_ref_one_email').hide();
          jQuery('#error_cupe_srd_ref_one_emailv').show();
          jQuery('#srd_ref_one_email').css('border-color', 'red');
          valid = false;
        }else{
          jQuery('#error_cupe_srd_ref_one_email').hide();
          jQuery('#error_cupe_srd_ref_one_emailv').hide();
          jQuery('#srd_ref_one_email').css('border-color', '');
        }

      } 

      if(srd_ref_one_job_title =='' || srd_ref_one_job_title == null){
        jQuery('#error_cupe_srd_ref_one_job_title').show();
        jQuery('#srd_ref_one_job_title').css('border-color', 'red');
        valid=false;
      }else{
        jQuery('#error_cupe_srd_ref_one_job_title').hide();
        jQuery('#srd_ref_one_job_title').css('border-color', '');
      }     

      if(srd_ref_one_desc =='' || srd_ref_one_desc == null){
          jQuery('#error_cupe_srd_ref_one_desc').show();
          jQuery('#srd_ref_one_desc').css('border-color', 'red');
          valid = false;
      }else{
          jQuery('#error_cupe_srd_ref_one_desc').hide();
          jQuery('#srd_ref_one_desc').css('border-color', '');
      }

      var reference1 = jQuery('.reference1 .drop-zone__thumb').attr('data-label');
      if(srd_ref_one_cover_ltr_yes == true && typeof reference1 === "undefined"){
          jQuery('#error_cupe_srd_ref_one_cover_ltr').show();
          jQuery('#srd_ref_one_cover_ltr').css('border-color', 'red');
          valid = false;
      }else{
          jQuery('#error_cupe_srd_ref_one_cover_ltr').hide();
          jQuery('#srd_ref_one_cover_ltr').css('border-color', '');
      }

    var srd_ref_two_fname = jQuery('#srd_ref_two_fname').val();
    var srd_ref_two_lname = jQuery('#srd_ref_two_lname').val();
    var srd_ref_two_phone = jQuery('#srd_ref_two_phone').val();
    var srd_ref_two_email = jQuery('#srd_ref_two_email').val();
    var srd_ref_two_job_title = jQuery('#srd_ref_two_job_title').val();
    var srd_ref_two_desc = jQuery('#srd_ref_two_desc').val();
    var srd_ref_two_cover_ltr_yes = jQuery('#srd_ref_two_cover_ltr_yes').is(':checked');
    var srd_ref_two_cover_ltr = jQuery('#srd_ref_two_cover_ltr').val();    

      if(srd_ref_two_fname =='' || srd_ref_two_fname == null){
          jQuery('#error_cupe_srd_ref_two_fname').show();
          jQuery('#srd_ref_two_fname').css('border-color', 'red');
          valid = false;
      }else{
          jQuery('#error_cupe_srd_ref_two_fname').hide();
          jQuery('#srd_ref_two_fname').css('border-color', '');
      }

      if(srd_ref_two_lname =='' || srd_ref_two_lname == null){
          jQuery('#error_cupe_srd_ref_two_lname').show();
          jQuery('#srd_ref_two_lname').css('border-color', 'red');
          valid = false;
      }else{
          jQuery('#error_cupe_srd_ref_two_lname').hide();
          jQuery('#srd_ref_two_lname').css('border-color', '');
      }

      if(srd_ref_two_phone =='' || srd_ref_two_phone == null){
        jQuery('#error_cupe_srd_ref_two_phone').show();
        jQuery('#srd_ref_two_phone').css('border-color', 'red');
        valid=false;
      }else{
        jQuery('#error_cupe_srd_ref_two_phone').hide();
        jQuery('#srd_ref_two_phone').css('border-color', '');
      }  
      
      if(srd_ref_two_email =='' || srd_ref_two_email == null){
        jQuery('#error_cupe_your_email').show();
        jQuery('#srd_ref_two_email').css('border-color', 'red');
        valid=false;
      }else{
        if(srdEmailReg.test(srd_ref_two_email) == false){
          jQuery('#error_cupe_srd_ref_two_email').hide();
          jQuery('#error_cupe_srd_ref_two_emailv').show();
          jQuery('#srd_ref_two_email').css('border-color', 'red');
          valid = false;
        }else{
          jQuery('#error_cupe_srd_ref_two_email').hide();
          jQuery('#error_cupe_srd_ref_two_emailv').hide();
          jQuery('#srd_ref_two_email').css('border-color', '');
        }

      } 

      if(srd_ref_two_job_title =='' || srd_ref_two_job_title == null){
        jQuery('#error_cupe_srd_ref_two_job_title').show();
        jQuery('#srd_ref_two_job_title').css('border-color', 'red');
        valid=false;
      }else{
        jQuery('#error_cupe_srd_ref_two_job_title').hide();
        jQuery('#srd_ref_two_job_title').css('border-color', '');
      }     

      if(srd_ref_two_desc =='' || srd_ref_two_desc == null){
          jQuery('#error_cupe_srd_ref_two_desc').show();
          jQuery('#srd_ref_two_desc').css('border-color', 'red');
          valid = false;
      }else{
          jQuery('#error_cupe_srd_ref_two_desc').hide();
          jQuery('#srd_ref_two_desc').css('border-color', '');
      }

      var reference2 = jQuery('.reference2 .drop-zone__thumb').attr('data-label');
      if(srd_ref_two_cover_ltr_yes == true && typeof reference2 === "undefined"){
          jQuery('#error_cupe_srd_ref_two_cover_ltr').show();
          jQuery('#srd_ref_two_cover_ltr').css('border-color', 'red');
          valid = false;
      }else{
          jQuery('#error_cupe_srd_ref_two_cover_ltr').hide();
          jQuery('#srd_ref_two_cover_ltr').css('border-color', '');
      }

    var srd_ref_three_fname = jQuery('#srd_ref_three_fname').val();
    var srd_ref_three_lname = jQuery('#srd_ref_three_lname').val();
    var srd_ref_three_phone = jQuery('#srd_ref_three_phone').val();
    var srd_ref_three_email = jQuery('#srd_ref_three_email').val();
    var srd_ref_three_job_title = jQuery('#srd_ref_three_job_title').val();
    var srd_ref_three_desc = jQuery('#srd_ref_three_desc').val();
    var srd_ref_three_cover_ltr_yes = jQuery('#srd_ref_three_cover_ltr_yes').is(':checked');
    var srd_ref_three_cover_ltr = jQuery('#srd_ref_three_cover_ltr').val();    

      if(srd_ref_three_fname =='' || srd_ref_three_fname == null){
          jQuery('#error_cupe_srd_ref_three_fname').show();
          jQuery('#srd_ref_three_fname').css('border-color', 'red');
          valid = false;
      }else{
          jQuery('#error_cupe_srd_ref_three_fname').hide();
          jQuery('#srd_ref_three_fname').css('border-color', '');
      }

      if(srd_ref_three_lname =='' || srd_ref_three_lname == null){
          jQuery('#error_cupe_srd_ref_three_lname').show();
          jQuery('#srd_ref_three_lname').css('border-color', 'red');
          valid = false;
      }else{
          jQuery('#error_cupe_srd_ref_three_lname').hide();
          jQuery('#srd_ref_three_lname').css('border-color', '');
      }

      if(srd_ref_three_phone =='' || srd_ref_three_phone == null){
        jQuery('#error_cupe_srd_ref_three_phone').show();
        jQuery('#srd_ref_three_phone').css('border-color', 'red');
        valid=false;
      }else{
        jQuery('#error_cupe_srd_ref_three_phone').hide();
        jQuery('#srd_ref_three_phone').css('border-color', '');
      }  
      
      if(srd_ref_three_email =='' || srd_ref_three_email == null){
        jQuery('#error_cupe_your_email').show();
        jQuery('#srd_ref_three_email').css('border-color', 'red');
        valid=false;
      }else{
        if(srdEmailReg.test(srd_ref_three_email) == false){
          jQuery('#error_cupe_srd_ref_three_email').hide();
          jQuery('#error_cupe_srd_ref_three_emailv').show();
          jQuery('#srd_ref_three_email').css('border-color', 'red');
          valid = false;
        }else{
          jQuery('#error_cupe_srd_ref_three_email').hide();
          jQuery('#error_cupe_srd_ref_three_emailv').hide();
          jQuery('#srd_ref_three_email').css('border-color', '');
        }

      } 

      if(srd_ref_three_job_title =='' || srd_ref_three_job_title == null){
        jQuery('#error_cupe_srd_ref_three_job_title').show();
        jQuery('#srd_ref_three_job_title').css('border-color', 'red');
        valid=false;
      }else{
        jQuery('#error_cupe_srd_ref_three_job_title').hide();
        jQuery('#srd_ref_three_job_title').css('border-color', '');
      }     

      if(srd_ref_three_desc =='' || srd_ref_three_desc == null){
          jQuery('#error_cupe_srd_ref_three_desc').show();
          jQuery('#srd_ref_three_desc').css('border-color', 'red');
          valid = false;
      }else{
          jQuery('#error_cupe_srd_ref_three_desc').hide();
          jQuery('#srd_ref_three_desc').css('border-color', '');
      }

      var reference3 = jQuery('.reference3 .drop-zone__thumb').attr('data-label');
      if(srd_ref_three_cover_ltr_yes == true && typeof reference3 === "undefined"){
          jQuery('#error_cupe_srd_ref_three_cover_ltr').show();
          jQuery('#srd_ref_three_cover_ltr').css('border-color', 'red');
          valid = false;
      }else{
          jQuery('#error_cupe_srd_ref_three_cover_ltr').hide();
          jQuery('#srd_ref_three_cover_ltr').css('border-color', '');
      }

    //------End------Validating form-------
      if(valid == true){  
      jQuery(".fscol.agreement").addClass("completed");
      jQuery(".formcontent.agreement").addClass("active");    
      //jQuery(".fscol.reference").removeClass("completed");
      jQuery(".formcontent.reference").removeClass("active");
    }else{
      $('html, body').animate({ 
        scrollTop: $("#srd_ref_one_job_title").offset().top
      }, 2000);
    }
  });

/**
 * Applicant's Declaration And Agreement Section
 *
 * @param {jQuery} select
 * @param {Object} options
 * @returns {Multiselect}
 */
   jQuery(".agreement.fprevbtn").click(function(){    
      jQuery(".fscol.reference").addClass("completed");
      jQuery(".formcontent.reference").addClass("active");    
      jQuery(".fscol.agreement").removeClass("completed");
      jQuery(".formcontent.agreement").removeClass("active");
  });
   
  jQuery(".agreement.fp_nextbtn").click(function(){
    var valid = true;
    var ada_read_agree = jQuery('#ada_read_agree').is(':checked');;
    var ada_appl_funame = jQuery('#ada_appl_funame').val();
    

      if(ada_read_agree == false || ada_read_agree == null){
          jQuery('#error_cupe_ada_read_agree').show();
          jQuery('#ada_read_agree').css('border-color', 'red');
          valid = false;
      }else{
          jQuery('#error_cupe_ada_read_agree').hide();
          jQuery('#ada_read_agree').css('border-color', '');
      }

      if(ada_appl_funame =='' || ada_appl_funame == null){
          jQuery('#error_cupe_ada_appl_funame').show();
          jQuery('#ada_appl_funame').css('border-color', 'red');
          valid = false;
      }else{
          jQuery('#error_cupe_ada_appl_funame').hide();
          jQuery('#ada_appl_funame').css('border-color', '');
      }
    //------End------Validating form-------
      if(valid == true){ 
          var formData = new FormData();         
          formData.append('action', 'cupe_general_app_ajax');
          var _cupe_app_nonce = $('form#cupe_general_app_form #_cupe_app_nonce').val();
          var job_ids = $('form#cupe_general_app_form #job_ids').val();
          formData.append('_cupe_app_nonce', _cupe_app_nonce); 
          formData.append('job_ids', job_ids); 

          //=================Personal Data============//  
          //var name_prefix = $('form#cupe_general_app_form #name_prefix').val();
          var name_prefix = $("input[name='name_prefix']:checked").val();
          var first_name = $('form#cupe_general_app_form #first_name').val();          
          var last_name = $('form#cupe_general_app_form #last_name').val();
          var address = $('form#cupe_general_app_form #address').val();
          var city = $('form#cupe_general_app_form #city').val();
          var country = $('form#cupe_general_app_form #country').val();
          var postal_code = $('form#cupe_general_app_form #postal_code').val();
          var your_phone = $('form#cupe_general_app_form #your_phone').val();
          var your_email = $('form#cupe_general_app_form #your_email').val();
          //var employed = $('form#cupe_general_app_form #employed').val();
          var employed = $("input[name='employed']:checked").val();
          //var entitled = $('form#cupe_general_app_form #entitled').val();
          var entitled = $("input[name='entitled']:checked").val();
          
          var position = $('form#cupe_general_app_form #position').val();
          //var communicate = $('form#cupe_general_app_form #communicate').val();
          var communicate = $("input[name='communicate']:checked").val();
          //var aboriginal = $('form#cupe_general_app_form #aboriginal').val();
          var aboriginal = $("input[name='aboriginal']:checked").val();
          //var certificate = $('form#cupe_general_app_form #certificate').val();
          
          var certificates = $("input[name^='certificate']:checkbox:checked").map(function (idx, ele) {
             return $(ele).val();
          }).get();

          formData.append('name_prefix', name_prefix);
          formData.append('first_name', first_name);  
          formData.append('last_name', last_name); 
          formData.append('address', address);
          formData.append('city', city);  
          formData.append('country', country); 
          formData.append('postal_code', postal_code);
          formData.append('your_phone', your_phone);  
          formData.append('your_email', your_email);           
          formData.append('employed', employed);
          formData.append('entitled', entitled);  
          formData.append('position', position); 
          formData.append('communicate', communicate);
          formData.append('aboriginal', aboriginal);  
          formData.append('certificate', certificates); 

          //=================Education============//
          var se_sc_name = $('form#cupe_general_app_form #se_sc_name').val();
          var se_start_date = $('form#cupe_general_app_form #se_start_date').val();
          var se_end_date = $('form#cupe_general_app_form #se_end_date').val();
          var se_cou_spec = $('form#cupe_general_app_form #se_cou_spec').val();
          var se_last_grade = $('form#cupe_general_app_form #se_last_grade').val();
          var pse_sc_name = $('form#cupe_general_app_form #pse_sc_name').val();
          var pse_start_date = $('form#cupe_general_app_form #pse_start_date').val();
          var pse_end_date = $('form#cupe_general_app_form #pse_end_date').val();
          var pse_cou_spec = $('form#cupe_general_app_form #pse_cou_spec').val();
          var ot_sc_name = $('form#cupe_general_app_form #ot_sc_name').val();
          var ot_start_date = $('form#cupe_general_app_form #ot_start_date').val();
          var ot_end_date = $('form#cupe_general_app_form #ot_end_date').val();
          var ot_cou_spec = $('form#cupe_general_app_form #ot_cou_spec').val();

          formData.append('se_sc_name', se_sc_name);
          formData.append('se_start_date', se_start_date);  
          formData.append('se_end_date', se_end_date); 
          formData.append('se_cou_spec', se_cou_spec);
          formData.append('se_last_grade', se_last_grade);  
          formData.append('pse_sc_name', pse_sc_name); 
          formData.append('pse_start_date', pse_start_date);
          formData.append('pse_end_date', pse_end_date); 
          formData.append('pse_cou_spec', pse_cou_spec);  
          formData.append('ot_sc_name', ot_sc_name);
          
          if(ot_start_date !='Course start date' || ot_start_date != null){
            formData.append('ot_start_date', ot_start_date); 
          }
          if(ot_end_date !='Course end date' || ot_end_date != null){
            formData.append('ot_end_date', ot_end_date);
          }
          formData.append('ot_cou_spec', ot_cou_spec);

          //=================General Questions============//
          //var gq_convicted = $('form#cupe_general_app_form #gq_convicted').val();
          var gq_convicted = $("input[name='gq_convicted']:checked").val();
          //var gq_convicted_other = $('form#cupe_general_app_form #gq_convicted_other').val();
          var gq_convicted_other = $("input[name='gq_convicted_other']:checked").val();
          //var gq_suspended = $('form#cupe_general_app_form #gq_suspended').val();
          var gq_suspended = $("input[name='gq_suspended']:checked").val();
          //var gq_pre_emp = $('form#cupe_general_app_form #gq_pre_emp').val();
          var gq_pre_emp = $("input[name='gq_pre_emp']:checked").val();
          //var gq_accident = $('form#cupe_general_app_form #gq_accident').val();
          var gq_accident = $("input[name='gq_accident']:checked").val();
          //var gq_capacity = $('form#cupe_general_app_form #gq_capacity').val();
          var gq_capacity = $("input[name='gq_capacity']:checked").val();

          formData.append('gq_convicted', gq_convicted);
          formData.append('gq_convicted_other', gq_convicted_other);  
          formData.append('gq_suspended', gq_suspended); 
          formData.append('gq_pre_emp', gq_pre_emp);
          formData.append('gq_accident', gq_accident);  
          formData.append('gq_capacity', gq_capacity); 

          //=================Questions For Applicants============//
          //var qa_ie_metis = $('form#cupe_general_app_form #qa_ie_metis').val();
          var qa_ie_metis = $("input[name='qa_ie_metis']:checked").val();
          var qa_ie_pns = $('form#cupe_general_app_form #qa_ie_pns').val();
          //var qa_ie_srwork = $('form#cupe_general_app_form #qa_ie_srwork').val();
          var qa_ie_srwork = $("input[name='qa_ie_srwork']:checked").val();
          var qa_ie_pro = $('form#cupe_general_app_form #qa_ie_pro').val();
          //var qa_tr_current = $('form#cupe_general_app_form #qa_tr_current').val();
          var qa_tr_current = $("input[name='qa_tr_current']:checked").val();
          //var qa_tr_license = $('form#cupe_general_app_form #qa_tr_license').val();
          var qa_tr_license = $("input[name='qa_tr_license']:checked").val();
          //var qa_tr_convicted = $('form#cupe_general_app_form #qa_tr_convicted').val();
          var qa_tr_convicted = $("input[name='qa_tr_convicted']:checked").val();
          //var qa_tr_lc_suspended = $('form#cupe_general_app_form #qa_tr_lc_suspended').val();
          var qa_tr_lc_suspended = $("input[name='qa_tr_lc_suspended']:checked").val();
          //var qa_tr_abstract = $('form#cupe_general_app_form #qa_tr_abstract').val();
          var qa_tr_abstract = $("input[name='qa_tr_abstract']:checked").val();
          //var qa_tr_restriction = $('form#cupe_general_app_form #qa_tr_restriction').val();
          var qa_tr_restriction = $("input[name='qa_tr_restriction']:checked").val();
          var qa_tr_dsc = $('form#cupe_general_app_form #qa_tr_dsc').val();
          //var qa_tr_endorse = $('form#cupe_general_app_form #qa_tr_endorse').val();
          var qa_tr_endorse = $("input[name='qa_tr_endorse']:checked").val();

          //var qa_cus_current = $('form#cupe_general_app_form #qa_cus_current').val();
          var qa_cus_current = $("input[name='qa_cus_current']:checked").val();
          //var qa_cus_license = $('form#cupe_general_app_form #qa_cus_license').val();
          var qa_cus_license = $("input[name='qa_cus_license']:checked").val();
          //var qa_cus_restriction = $('form#cupe_general_app_form #qa_cus_restriction').val();
          var qa_cus_restriction = $("input[name='qa_cus_restriction']:checked").val();
          var qa_cus_dsc = $('form#cupe_general_app_form #qa_cus_dsc').val();
          //var qa_cus_endorse = $('form#cupe_general_app_form #qa_cus_endorse').val();
          var qa_cus_endorse = $("input[name='qa_cus_endorse']:checked").val();
          //var qa_cus_convicted = $('form#cupe_general_app_form #qa_cus_convicted').val();
          var qa_cus_convicted = $("input[name='qa_cus_convicted']:checked").val();
          //var qa_cus_lc_suspended = $('form#cupe_general_app_form #qa_cus_lc_suspended').val();
          var qa_cus_lc_suspended = $("input[name='qa_cus_lc_suspended']:checked").val();
          //var qa_cus_abstract = $('form#cupe_general_app_form #qa_cus_abstract').val(); 
          var qa_cus_abstract = $("input[name='qa_cus_abstract']:checked").val();        
                     
          //var qa_scs_caas = $('form#cupe_general_app_form #qa_scs_caas').val();
          var qa_scs_caas = $("input[name='qa_scs_caas']:checked").val();
          var qa_scs_pni = $('form#cupe_general_app_form #qa_scs_pni').val();
          //var qa_ls_ceac = $('form#cupe_general_app_form #qa_ls_ceac').val();
          var qa_ls_ceac = $("input[name='qa_ls_ceac']:checked").val();
          //var qa_ls_pop = $('form#cupe_general_app_form #qa_ls_pop').val();
          var qa_ls_pop = $("input[name='qa_ls_pop']:checked").val();
          //var qa_ls_nvci = $('form#cupe_general_app_form #qa_ls_nvci').val();
          var qa_ls_nvci = $("input[name='qa_ls_nvci']:checked").val();
          //var qa_cus_bswc = $('form#cupe_general_app_form #qa_cus_bswc').val();
          var qa_cus_bswc = $("input[name='qa_cus_bswc']:checked").val();
          //var qa_mt_rsc = $('form#cupe_general_app_form #qa_mt_rsc').val();
          var qa_mt_rsc = $("input[name='qa_mt_rsc']:checked").val();
          //var qa_mt_current = $('form#cupe_general_app_form #qa_mt_current').val();
          var qa_mt_current = $("input[name='qa_mt_current']:checked").val();
          //var qa_mt_license = $('form#cupe_general_app_form #qa_mt_license').val();
          var qa_mt_license = $("input[name='qa_mt_license']:checked").val();
          //var qa_mt_restriction = $('form#cupe_general_app_form #qa_mt_restriction').val();
          var qa_mt_restriction = $("input[name='qa_mt_restriction']:checked").val();
          var qa_mt_dsc = $('form#cupe_general_app_form #qa_mt_dsc').val();
          //var qa_mt_endorse = $('form#cupe_general_app_form #qa_mt_endorse').val();
          var qa_mt_endorse = $("input[name='qa_mt_endorse']:checked").val();
          //var qa_mt_convicted = $('form#cupe_general_app_form #qa_mt_convicted').val();
          var qa_mt_convicted = $("input[name='qa_mt_convicted']:checked").val();
          //var qa_mt_lc_suspended = $('form#cupe_general_app_form #qa_mt_lc_suspended').val();
          var qa_mt_lc_suspended = $("input[name='qa_mt_lc_suspended']:checked").val();
          //var qa_mt_abstract = $('form#cupe_general_app_form #qa_mt_abstract').val();
          var qa_mt_abstract = $("input[name='qa_mt_abstract']:checked").val();
          
          formData.append('qa_ie_metis', qa_ie_metis);
          formData.append('qa_ie_pns', qa_ie_pns);  
          formData.append('qa_ie_srwork', qa_ie_srwork); 
          formData.append('qa_ie_pro', qa_ie_pro);
          formData.append('qa_tr_current', qa_tr_current);            
          formData.append('qa_tr_license', qa_tr_license); 
          formData.append('qa_tr_convicted', qa_tr_convicted); 
          formData.append('qa_tr_lc_suspended', qa_tr_lc_suspended);
          formData.append('qa_tr_abstract', qa_tr_abstract);  
          formData.append('qa_tr_restriction', qa_tr_restriction);
          formData.append('qa_tr_dsc', qa_tr_dsc);
          formData.append('qa_tr_endorse', qa_tr_endorse);
          formData.append('qa_cus_current', qa_cus_current);
          formData.append('qa_cus_license', qa_cus_license);
          formData.append('qa_cus_restriction', qa_cus_restriction);
          formData.append('qa_cus_dsc', qa_cus_dsc);
          formData.append('qa_cus_endorse', qa_cus_endorse);
          formData.append('qa_cus_convicted', qa_cus_convicted);
          formData.append('qa_cus_lc_suspended', qa_cus_lc_suspended); 
          formData.append('qa_cus_abstract', qa_cus_abstract);          

          formData.append('qa_scs_caas', qa_scs_caas);
          formData.append('qa_scs_pni', qa_scs_pni);  
          formData.append('qa_ls_ceac', qa_ls_ceac); 
          formData.append('qa_ls_pop', qa_ls_pop);
          formData.append('qa_ls_nvci', qa_ls_nvci);  
          formData.append('qa_cus_bswc', qa_cus_bswc); 
          formData.append('qa_mt_rsc', qa_mt_rsc);
          formData.append('qa_mt_current', qa_mt_current);
          formData.append('qa_mt_license', qa_mt_license);
          formData.append('qa_mt_restriction', qa_mt_restriction);
          formData.append('qa_mt_dsc', qa_mt_dsc);
          formData.append('qa_mt_endorse', qa_mt_endorse);
          formData.append('qa_mt_convicted', qa_mt_convicted);
          formData.append('qa_mt_lc_suspended', qa_mt_lc_suspended);
          formData.append('qa_mt_abstract', qa_mt_abstract);
          
          //=================Employment History============//
          var eh_one_emp_name = $('form#cupe_general_app_form #eh_one_emp_name').val();
          var eh_one_sup_name = $('form#cupe_general_app_form #eh_one_sup_name').val();
          var eh_one_con_no_emp = $('form#cupe_general_app_form #eh_one_con_no_emp').val();
          var eh_one_position = $('form#cupe_general_app_form #eh_one_position').val();
          var eh_one_emp_start_date = $('form#cupe_general_app_form #eh_one_emp_start_date').val();
          var eh_one_emp_end_date = $('form#cupe_general_app_form #eh_one_emp_end_date').val();
          
          var eh_one_reason_lev = $('form#cupe_general_app_form #eh_one_reason_lev').val();
           
          var eh_two_emp_name = $('form#cupe_general_app_form #eh_two_emp_name').val();
          var eh_two_sup_name = $('form#cupe_general_app_form #eh_two_sup_name').val();
          var eh_two_con_no_emp = $('form#cupe_general_app_form #eh_two_con_no_emp').val();
          var eh_two_position = $('form#cupe_general_app_form #eh_two_position').val();
          var eh_two_emp_start_date = $('form#cupe_general_app_form #eh_two_emp_start_date').val();
          var eh_two_emp_end_date = $('form#cupe_general_app_form #eh_two_emp_end_date').val();
          var eh_two_reason_lev = $('form#cupe_general_app_form #eh_two_reason_lev').val();
          
          var eh_three_emp_name = $('form#cupe_general_app_form #eh_three_emp_name').val();
          var eh_three_sup_name = $('form#cupe_general_app_form #eh_three_sup_name').val();
          var eh_three_con_no_emp = $('form#cupe_general_app_form #eh_three_con_no_emp').val();
          var eh_three_position = $('form#cupe_general_app_form #eh_three_position').val();
          var eh_three_emp_start_date = $('form#cupe_general_app_form #eh_three_emp_start_date').val();
          var eh_three_emp_end_date = $('form#cupe_general_app_form #eh_three_emp_end_date').val();
          var eh_three_reason_lev = $('form#cupe_general_app_form #eh_three_reason_lev').val();
         
          formData.append('eh_one_emp_name', eh_one_emp_name);
          formData.append('eh_one_sup_name', eh_one_sup_name);  
          formData.append('eh_one_con_no_emp', eh_one_con_no_emp); 
          formData.append('eh_one_position', eh_one_position);
          formData.append('eh_one_emp_start_date', eh_one_emp_start_date);
          formData.append('eh_one_emp_end_date', eh_one_emp_end_date);            
          formData.append('eh_one_reason_lev', eh_one_reason_lev);

          formData.append('eh_two_emp_name', eh_two_emp_name);
          formData.append('eh_two_sup_name', eh_two_sup_name);  
          formData.append('eh_two_con_no_emp', eh_two_con_no_emp); 
          formData.append('eh_two_position', eh_two_position);
          formData.append('eh_two_emp_start_date', eh_two_emp_start_date);
          formData.append('eh_two_emp_end_date', eh_two_emp_end_date);  
          formData.append('eh_two_reason_lev', eh_two_reason_lev); 

          formData.append('eh_three_emp_name', eh_three_emp_name);
          formData.append('eh_three_sup_name', eh_three_sup_name);  
          formData.append('eh_three_con_no_emp', eh_three_con_no_emp); 
          formData.append('eh_three_position', eh_three_position);
          formData.append('eh_three_emp_start_date', eh_three_emp_start_date);
          formData.append('eh_three_emp_end_date', eh_three_emp_end_date);  
          formData.append('eh_three_reason_lev', eh_three_reason_lev);

          //=================Upload Your Resume============//
          formData.append('upload_resume', jQuery('input[name="upload_resume"]')[0].files[0]);

          //=================Reference Details============//
          var srd_ref_one_fname = $('form#cupe_general_app_form #srd_ref_one_fname').val();
          var srd_ref_one_lname = $('form#cupe_general_app_form #srd_ref_one_lname').val();
          var srd_ref_one_phone = $('form#cupe_general_app_form #srd_ref_one_phone').val();
          var srd_ref_one_email = $('form#cupe_general_app_form #srd_ref_one_email').val();
          var srd_ref_one_job_title = $('form#cupe_general_app_form #srd_ref_one_job_title').val();
          var srd_ref_one_desc = $('form#cupe_general_app_form #srd_ref_one_desc').val();
          var srd_ref_one_cover_ltr_yes = $('form#cupe_general_app_form #srd_ref_one_cover_ltr_yes').val();
          
          var srd_ref_two_fname = $('form#cupe_general_app_form #srd_ref_two_fname').val();
          var srd_ref_two_lname = $('form#cupe_general_app_form #srd_ref_two_lname').val();
          var srd_ref_two_phone = $('form#cupe_general_app_form #srd_ref_two_phone').val();
          var srd_ref_two_email = $('form#cupe_general_app_form #srd_ref_two_email').val();
          var srd_ref_two_job_title = $('form#cupe_general_app_form #srd_ref_two_job_title').val();
          var srd_ref_two_desc = $('form#cupe_general_app_form #srd_ref_two_desc').val();
          var srd_ref_two_cover_ltr_yes = $('form#cupe_general_app_form #srd_ref_two_cover_ltr_yes').val();
          
          var srd_ref_three_fname = $('form#cupe_general_app_form #srd_ref_three_fname').val();
          var srd_ref_three_lname = $('form#cupe_general_app_form #srd_ref_three_lname').val();
          var srd_ref_three_phone = $('form#cupe_general_app_form #srd_ref_three_phone').val();
          var srd_ref_three_email = $('form#cupe_general_app_form #srd_ref_three_email').val();
          var srd_ref_three_job_title = $('form#cupe_general_app_form #srd_ref_three_job_title').val();
          var srd_ref_three_desc = $('form#cupe_general_app_form #srd_ref_three_desc').val();
          var srd_ref_three_cover_ltr_yes = $('form#cupe_general_app_form #srd_ref_three_cover_ltr_yes').val();
          
          formData.append('srd_ref_one_fname', srd_ref_one_fname);
          formData.append('srd_ref_one_lname', srd_ref_one_lname);  
          formData.append('srd_ref_one_phone', srd_ref_one_phone); 
          formData.append('srd_ref_one_email', srd_ref_one_email);
          formData.append('srd_ref_one_job_title', srd_ref_one_job_title);  
          formData.append('srd_ref_one_desc', srd_ref_one_desc);
          formData.append('srd_ref_one_cover_ltr_yes', srd_ref_one_cover_ltr_yes);
          
          formData.append('srd_ref_two_fname', srd_ref_two_fname);
          formData.append('srd_ref_two_lname', srd_ref_two_lname);  
          formData.append('srd_ref_two_phone', srd_ref_two_phone); 
          formData.append('srd_ref_two_email', srd_ref_two_email);
          formData.append('srd_ref_two_job_title', srd_ref_two_job_title);  
          formData.append('srd_ref_two_desc', srd_ref_two_desc);
          formData.append('srd_ref_two_cover_ltr_yes', srd_ref_two_cover_ltr_yes);

          formData.append('srd_ref_three_fname', srd_ref_three_fname);
          formData.append('srd_ref_three_lname', srd_ref_three_lname);  
          formData.append('srd_ref_three_phone', srd_ref_three_phone); 
          formData.append('srd_ref_three_email', srd_ref_three_email);
          formData.append('srd_ref_three_job_title', srd_ref_three_job_title);  
          formData.append('srd_ref_three_desc', srd_ref_three_desc);
          formData.append('srd_ref_three_cover_ltr_yes', srd_ref_three_cover_ltr_yes);

          formData.append('srd_ref_one_cover_ltr', jQuery('input[name="srd_ref_one_cover_ltr"]')[0].files[0]);
          formData.append('srd_ref_two_cover_ltr', jQuery('input[name="srd_ref_two_cover_ltr"]')[0].files[0]);
          formData.append('srd_ref_three_cover_ltr', jQuery('input[name="srd_ref_three_cover_ltr"]')[0].files[0]);
          
          //======================Declaration And Agreement============
          var ada_read_agree = $('form#cupe_general_app_form #ada_read_agree').val();
          var ada_appl_funame = $('form#cupe_general_app_form #ada_appl_funame').val();
          formData.append('ada_read_agree', ada_read_agree);
          formData.append('ada_appl_funame', ada_appl_funame);
                    
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
                  jQuery('.agreement').prop('disabled', true);
                  jQuery('#cupe_general_app_form').css("opacity",".5");
                  jQuery('#saving_app').show();
                  jQuery('#cupe_general_app_form').hide();
                  
              },
            success: function(response) { console.log(response);
              jQuery('#saving_app').hide();
              jQuery('#cupe_general_app_form').show();
              console.log(response.msg);
            jQuery('.agreement').prop('disabled', false);
                  jQuery('#cupe_general_app_form').css("opacity","1");
              if(response.msg == "success") {
                  //swal.fire(response.msg_html, "success");
                  swal.fire("Thank You!", response.msg_html, "success");
                  setTimeout(function(){ window.location.href=response.return_url; }, 2000);
                  
              }
              else {
                  swal.fire('Oops...', response.msg_error, 'error');
              }
            }
                    });
      return false;
    }
  }); 
  
  //=============End of General Application
});

/**
 * Reject Application
 *
 * @param {jQuery} select
 * @param {Object} options
 * @returns {Multiselect}
 */
function rejectAppById(e){ 
  var data = $(e).attr('id'); 
  var appId = data.split('_');
  var cupe_app_nonce = jQuery("#_cupe_app_listing_nonce").val();
  //var app_id = jQuery('#app_id').val();
  var app_id = appId[1];
  console.log(app_id);

  Swal.fire({  
    title: 'Are you sure?',  
    showDenyButton: true,
    //showCancelButton: true,  
    confirmButtonText: `Reject Application`,  
    denyButtonText: `Keep Application`,
    }).then((result) => {  
      /* Read more about isConfirmed, isDenied below */  
        if (result.isConfirmed) {    
          jQuery.ajax({
              type : "post",
              dataType : "json",
              cache: false,
              url : frontend_ajax_object.ajaxurl,
              data : {action: "cupe_reject_app_ajax", app_id : app_id, cupe_app_nonce: cupe_app_nonce},
              
              success: function(response) { //console.log(response);
                        if(response.msg == "success") {
                            //swal.fire(response.msg_html, "success");
                            swal.fire("Thank You!", response.msg_html, "success");
                            setTimeout(function(){ window.location.href=window.location.href; }, 2000);
                            
                        }
                        else {
                            swal.fire('Oops...', response.msg, 'error');
                        }
                }
              }); 
        }
    });
    return false;

 };

/**
 * Generate View Application
 *
 * @param {jQuery} select
 * @param {Object} options
 * @returns {Multiselect}
 */
function generateViewById(e){ 
  var data = $(e).attr('id'); 
  var appId = data.split('_');
  var cupe_app_nonce = jQuery("#_cupe_app_listing_nonce").val();
  //var app_id = jQuery('#app_id').val();
  var app_id = appId[1];
  console.log(app_id);
    jQuery.ajax({
    type : "post",
    dataType : "json",
    cache: false,
    url : frontend_ajax_object.ajaxurl,
    data : {action: "cupe_view_app_ajax", app_id : app_id, cupe_app_nonce: cupe_app_nonce},
    
    success: function(response) { //console.log(response);
              if(response.msg == "success") {
                  jQuery('.sidepopup_inner#replace_view').html(response.msg_html);  
                  $('html').addClass('viewDetails');
                  
                  $(".spnav").mCustomScrollbar({
                    axis:"x",        
                    autoExpandScrollbar:true,
                    advanced:{autoExpandHorizontalScroll:true}
                  });
              }
              else {
                  swal.fire('Oops...', response.msg, 'error');
              }
      }
    });
    return false;
 };

/**
 * Validate email
 *
 * @param {jQuery} select
 * @param {Object} options
 * @returns {Multiselect}
 */
function checkCupeEmailExist(){ 
  var cupe_app_nonce = jQuery("#_cupe_app_nonce").val();
  var your_email = jQuery('#your_email').val();
    jQuery.ajax({
    type : "post",
    dataType : "json",
    cache: false,
    url : frontend_ajax_object.ajaxurl,
    data : {action: "cupe_email_exist_ajax", your_email : your_email, cupe_app_nonce: cupe_app_nonce},
    
    success: function(response) { 
        if(response.msg == "error") {
            jQuery('#error_cupe_your_email_exist').show();
            jQuery('#your_email').css('border-color', 'red');
            jQuery('#error_cupe_your_email').hide();
            jQuery('#error_cupe_your_emailv').hide();            
            jQuery('.personal.fp_nextbtn').prop('disabled', true);               
            
        }else {
             jQuery('#error_cupe_your_email_exist').hide();
             jQuery('#your_email').css('border-color', '');
             jQuery('.personal.fp_nextbtn').prop('disabled', false);
             
        }
    }
    });
  return false;
}

/**
 * Application view verify/unveify
 *
 * @param {jQuery} select
 * @param {Object} options
 * @returns {Multiselect}
 */
function updateRefVerify(e){ 
  var data = $(e).attr('id'); 
  var refId = data.split('_');
  var ref_id = refId[1];
  var ref_val = jQuery('#'+data).val();
    jQuery.ajax({
    type : "post",
    dataType : "json",
    cache: false,
    url : frontend_ajax_object.ajaxurl,
    data : {action: "cupe_ref_verify_ajax", ref_id : ref_id, ref_val : ref_val},
    
    success: function(response) { //console.log(response);
              if(response.msg == "success") {
                   swal.fire("Thank You!", response.msg_html, "success");
              }
              else {
                  swal.fire('Oops...', response.msg_html , 'error');
              }
      }
    });
    return false;
 };

/**
 * Application view save Ref Note
 *
 * @param {jQuery} select
 * @param {Object} options
 * @returns {Multiselect}
 */

function saveRefAdminNote(e){ 
  var data = $(e).attr('id'); 
  var refId = data.split('_');
  var ref_id = refId[1];
  var admin_note = jQuery('#admin-note_'+ref_id).val();
  //console.log(ref_id);
  //console.log(ref_val);
    jQuery.ajax({
    type : "post",
    dataType : "json",
    cache: false,
    url : frontend_ajax_object.ajaxurl,
    data : {action: "cupe_ref_admin_note_ajax", ref_id : ref_id, admin_note : admin_note},
    
    success: function(response) { //console.log(response);
              if(response.msg == "success") {
                   swal.fire("Thank You!", "Updated", "success");
              }
              else {
                  swal.fire('Oops...', 'Try after some times', 'error');
              }
      }
    });
    return false;
 };

/**
 * Show/Hide hidden div
 *
 * @param {jQuery} select
 * @param {Object} options
 * @returns {Multiselect}
 */

function showHideMeritStatus($clickId, $showId) {
  var $val = jQuery('.'+$clickId).val();
     //console.log( $val);
        if ( $val == 'yes')         
           jQuery('#'+$showId).fadeIn('slow');
        else 
            jQuery('#'+$showId).fadeOut('slow');
    }
