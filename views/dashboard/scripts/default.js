$(document).ready(function($){
$.get('dashboard/xhrGetListings', function(o) {
  for(var i = 0; i < o.length; i++) {
   $("#listInserts").append('<div>' + o[i].test + '<a href="#" class="del" rel="'+ o[i].ID +'">X</a></div>');
  }
  },'json');

  $('#randomInsert').submit(function() {

   var url = $(this).attr('action');
   var data = $(this).serialize();
   $.post(url, data, function(o) {
    alert(1);
   });
   return false;
  });
});
$(document).on('click', "a.del", function() {
  delItem = $(this);
  var id = $(this).attr('rel');
  $.post('dashboard/xhrDeleteListing', {'id': id}, function(o) {
  $("#listInserts").append('<div>' + o.test + '<a href="#" class="del" rel="'+ o.ID +'">X</a></div>');
  delItem.parent().remove();
  }, 'json');
  return false;        
});