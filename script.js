!function ($) {

  /** Set up initial load and load on option updates (.pl-trigger will fire this) */
  $( '.pl-sn-curtain-call' ).on('template_ready', function(){

    $.plCurtain_Call.init( $(this) )

  })

  $.plCurtain_Call = {

    init: function( section ){

      var that = this;

      that.setCurtain_Call( section );

    },

    setCurtain_Call: function( section ){

      var theContainer = section.find('.plc-image-container');

      setTimeout(function(){
        theContainer.addClass("is-visible")
      }, 400);

      theContainer.each(function(){
          var actual = $(this);
          drags(actual.find('.plc-handle'), actual.find('.plc-resize-img'), actual);
      });

    }

  }

  //draggable funtionality - credits to http://css-tricks.com/snippets/jquery/draggable-without-jquery-ui/
  function drags(dragElement, resizeElement, container) {
      dragElement.on("mousedown vmousedown", function(e) {
          dragElement.addClass('draggable');
          resizeElement.addClass('resizable');

          var dragWidth = dragElement.outerWidth(),
              xPosition = dragElement.offset().left + dragWidth - e.pageX,
              containerOffset = container.offset().left,
              containerWidth = container.outerWidth(),
              minLeft = containerOffset + 10,
              maxLeft = containerOffset + containerWidth - dragWidth - 10;

          dragElement.parents().on("mousemove vmousemove", function(e) {
              leftValue = e.pageX + xPosition - dragWidth;

              //constrain the draggable element to move inside its container
              if(leftValue < minLeft ) {
                  leftValue = minLeft;
              } else if ( leftValue > maxLeft) {
                  leftValue = maxLeft;
              }

              widthValue = (leftValue + dragWidth/2 - containerOffset)*100/containerWidth+'%';

              $('.draggable').css('left', widthValue).on("mouseup vmouseup", function() {
                  $(this).removeClass('draggable');
                  resizeElement.removeClass('resizable');
              });

              $('.resizable').css('width', widthValue);

              //function to upadate images label visibility here
              // ...

          }).on("mouseup vmouseup", function(e){
              dragElement.removeClass('draggable');
              resizeElement.removeClass('resizable');
          });
          e.preventDefault();
      }).on("mouseup vmouseup", function(e) {
          dragElement.removeClass('draggable');
          resizeElement.removeClass('resizable');
      });
  }


}(window.jQuery);
