jQuery(document).ready(function(a){var b=function(b){a(b)&&a(b).magnificPopup({type:"ajax",midClick:!0,closeOnBgClick:!1,closeBtnInside:!1,showCloseBtn:!1,removalDelay:500,mainClass:"mfp-move-horizontal",tLoading:'<div class="ui active inverted dimmer"> <div class="ui text loader"></div> </div>',ajax:{settings:null,cursor:"",tError:'<a href="%url%">The content</a> could not be loaded.'},callbacks:{open:function(){this.st.mainClass="mfp-move-horizontal"},close:function(){},ajaxContentAdded:function(){var a=this;this.content.find(".msl_close_btn").on("click",function(){a.close()}),c()}}})},c=function(){d(),a("a[href$="+_msm.slug+"-register_form]").addClass(_msm.slug+"-register_popup"),a("a[href$="+_msm.slug+"-login_form]").addClass(_msm.slug+"-login_popup"),a("a[href$="+_msm.slug+"-lostpassword_form]").addClass(_msm.slug+"-lostpassword_popup"),b("."+_msm.slug+"-register_popup"),b("."+_msm.slug+"-login_popup"),b("."+_msm.slug+"-lostpassword_popup")},d=function(){var c=a('a[href$="?mshop_login"]');c&&c.length>0&&(c.addClass(_msm.slug+"-login_popup"),_msm.lang_code?c.attr("href",_msm.ajaxurl+"&action="+_msm.slug+"-login_form"):c.attr("href",_msm.ajaxurl+"?action="+_msm.slug+"-login_form"),b("."+_msm.slug+"-login_popup"));var d=a('a[href$="?mshop_register"]');d&&d.length>0&&(d.addClass(_msm.slug+"-register_popup"),_msm.lang_code?d.attr("href",_msm.ajaxurl+"&action="+_msm.slug+"-register_form"):d.attr("href",_msm.ajaxurl+"?action="+_msm.slug+"-register_form"),b("."+_msm.slug+"-register_popup"));var e=a('a[href$="?mshop_lostpassword"]');e&&e.length>0&&(e.addClass(_msm.slug+"-lostpassword_popup"),_msm.lang_code?e.attr("href",_msm.ajaxurl+"&action="+_msm.slug+"-lostpassword_form"):e.attr("href",_msm.ajaxurl+"?action="+_msm.slug+"-lostpassword_form"),b("."+_msm.slug+"-lostpassword_popup"))};d(),/mshop_login/.test(location.href)&&a("."+_msm.slug+"-login_popup")?a("."+_msm.slug+"-login_popup").trigger("click"):/mshop_register/.test(location.href)&&a("."+_msm.slug+"-register_popup")&&a("."+_msm.slug+"-register_popup").trigger("click")});