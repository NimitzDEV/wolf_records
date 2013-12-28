(function($){$.fn.checkWidth = function(){
  var table_width = $('#list').width();
  var window_width = $('body').width();

  if(table_width > window_width){
    $('.toggle_scroll').css("display","block");
    $('#scroll').css("overflow","scroll");
    $(".toggle_scroll a").click(
      function(){
        $('#scroll').css("overflow","visible");
        $('.toggle_scroll').css("display","none");
    });
  } else {
    $('.toggle_scroll').css("display","none");
    $('#scroll').css("overflow","auto");
  }
};
})(jQuery);
