(function ($) {

	Drupal.behaviors.oilberg_helper = {

    attach: function (context, settings) {      

      if (window.innerWidth>'1170') {
        let winWidth = window.innerWidth;
        $('.cats.js-sticky-widget').css({'right': ((winWidth-1170)/2)+"px"});
      }
      if (window.innerWidth > '640') {
      	var categories = new Sticky('.cats.js-sticky-widget');
      }

      
    }
  }

  Drupal.ajax.prototype.commands.append_to = function (ajax, response, status) {
    // Get information from the response. If it is not there, default to
    // our presets.
    var wrapper = response.selector ? $(response.selector) : $(ajax.wrapper);
    var method = response.method || ajax.method;
    var effect = ajax.getEffect(response);

    // We don't know what response.data contains: it might be a string of text
    // without HTML, so don't rely on jQuery correctly iterpreting
    // $(response.data) as new HTML rather than a CSS selector. Also, if
    // response.data contains top-level text nodes, they get lost with either
    // $(response.data) or $('<div></div>').replaceWith(response.data).
    // var new_content_wrapped = $('<div></div>').html(response.data);
    // var new_content = new_content_wrapped.contents();
    wrapper['append'](response.data);
  };

})(jQuery);
