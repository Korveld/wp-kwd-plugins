jQuery(document).ready(function($) {
  /*console.log(kwd_accordion_obj.disableLink);*/
  var disableLink = (/true/i).test(kwd_accordion_obj.disableLink); //returns true
  var eventType = kwd_accordion_obj.eventType;
  var hoverDelay = kwd_accordion_obj.hoverDelay;
  var speed = kwd_accordion_obj.speed;

  if (hoverDelay == '') {
    hoverDelay = 100
  }
  if (speed == '') {
    speed = 300
  }

  var speed = parseInt(speed, 10);

  $('.js-accordion').dcAccordion({
    eventType: eventType,
    disableLink: disableLink,
    hoverDelay: hoverDelay,
    speed: speed,
    saveState: false
  });
});