function vcpUpdateShortCodePreview(){
  var $ = jQuery; //for noConflict mode
  var $shortCode = $('#vcpShortCode');
  var Style = parseInt(document.vcpForm.styleSelect.value);
  var Position = parseInt(document.vcpForm.positionSelect.value);
  var Rate = parseFloat(document.vcpForm.rateEdit.value);

  //update ShortCode
  var content = '[vatcalc';
  if (Style != 1){content += ' style=' + Style}
  if (Position != 0){content += ' position=' + Position}
  if (Rate != 0){content += ' rate=' + Rate}
  content += ']';

  if (window.vcpPrevVal != content){
    $shortCode.val(content);
    window.vcpPrevVal = content;

    //flash background color
    $shortCode.css('background-color', '#FF9');
    setTimeout(function(){$shortCode.css('background-color', '');}, 300);
  }
}


//page INIT
jQuery( document ).ready(function() {

  jQuery("#vcpForm *").on('change keyup paste', function() {
    window.clearTimeout(window.vcpTimeoutID);
    window.vcpTimeoutID = window.setTimeout(vcpUpdateShortCodePreview, 300);
    });

});

//vcpUpdateShortCodePreview();