jQuery(function(a){"use strict";function b(b){return d(b),a.ajax({type:"POST",url:_pafw_bill_key.ajaxurl,dataType:"json",data:{action:_pafw_bill_key.slug+"-pafw_ajax_action",payment_method:_pafw_bill_key.payment_method,payment_action:"register_card",data:b.serialize(),_wpnonce:_pafw_bill_key._wpnonce},success:function(a){void 0!==a?void 0!==a.success&&!0===a.success?(alert("카드가 정상적으로 등록되었습니다."),window.location="/my-account/pafw-card/"):(alert(a.data),e(b)):(alert("카드 등록중 오류가 발생했습니다. 잠시 후 다시 시도해주세요."),e(b))}}),!1}var c=function(a){return a.is(".processing")||a.parents(".processing").length},d=function(a){c(a)||a.addClass("processing").block({message:null,overlayCSS:{background:"#fff",opacity:.6}})},e=function(a){a.removeClass("processing").unblock()};a.blockUI.defaults.overlayCSS.cursor="default";a(".pafw_register_card").on("click",function(){var c=a(this).closest("form");c.hasClass("processing")||b(c)}),a(".pafw_delete_card").on("click",function(){var b=a("div.msspg-myaccount");confirm("등록된 카드 정보를 삭제하시겠습니까?")&&(d(b),a.ajax({type:"POST",url:_pafw_bill_key.ajaxurl,dataType:"json",data:{action:_pafw_bill_key.slug+"-pafw_ajax_action",payment_method:_pafw_bill_key.payment_method,payment_action:"delete_card",_wpnonce:_pafw_bill_key._wpnonce},success:function(a){if(void 0!==a){if(void 0!==a.success&&!0===a.success)return alert("카드 정보가 삭제되었습니다."),void window.location.reload(!0);alert(a.data)}else alert("카드 정보 삭제중 오류가 발생했습니다. 잠시 후 다시 시도해주세요.");e(b)},error:function(){alert("카드 정보 삭제중 오류가 발생했습니다. 잠시 후 다시 시도해주세요."),e(b)}}))})});