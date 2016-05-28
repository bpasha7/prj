$(document).ready(function($){
	var URL = 'http://wts.dev/';
	var h = 0;
//Logining
	$("#login_back").on('submit', '#loginForm', function(e){
    var data = $(this).serialize();
$.ajax({
      url: "http://wts.dev/login/run",
      dataType: "json",
      type: "POST",
      data: data,
      success: function(data){
        		$('#bar_username').val(data.UserName);
        		$('#userbar_user').text(data.UserName);
        		$('#body_login').css('display','none');
        		$('#open_userbar').css('visibility','visible');
        		$('#open_userbar').text(data.UserName + " ");
        		$("#menu_login").hide();
        		//$("#loginForm").fadeOut("slow"); 	
        		$('#login_back').fadeOut(2500); 
	  },
         error: function() {
                        alert("Неправильные логин или пароль");
                  }
        });
        return false; 
	});
	//Load Login form
	$('body').delegate('#menu_login', 'click', function(){
		$( ".back" ).empty();
		   $( ".back" ).show();
                $.ajax({   
                    url: URL+'login/index',  
                    success: function(html){  
                        $('.back').html(html);  
                    }  
                });  
                return false;  
            }); 
      //load items table
    	/*$('body').delegate('#my_items', 'click', function(){
		$( "#userbar_content" ).empty();   
                $.ajax({   
                    url: URL+'userpanel/index',  
                    success: function(html){  
                        $("#userbar_content").html(html);  
                    }  
                });  
                return false;  
            });   */
    //load lots table
    	/*$('#my_lots').click(function(){
		//$( "#userbar_content" ).empty();   
                $.ajax({   
                    url: URL+'userpanel/index',  
                    success: function(html){  
                        $("#userbar_content").html(html);  
                    }  
                });  
                return false;  
            });      
      */
	//logout from control panel
		$("#logout").click(function(e){
		var returnVal = confirm("Выйти из учетной записи?");
		if(returnVal){
			$('#open_userbar').click();
			//$('#top-box').attr("checked", false);
			$('#open_userbar').text("");
			$('#open_userbar').css('visibility','hidden');
			$("#menu_login").show();
			$.post("http://wts.dev/dashboard/logout",function(data){
     });
			
		}
	});
	//Check balance after open userPanel
	$('#open_userbar').click(function(e){
		if($("#top-box").is(':checked'))
		{
			//$('#tst').height(250);
			var h = $('#tst').height();
			$('#open_userbar').offset({top:0, right:5});
			$('#tst').offset({top: -h});
		}
		else
		{
			var h = $('#tst').height();
			$('#open_userbar').offset({top:h, right:5});
			$('#tst').offset({top: 0});
	  $.ajax({
 	  url: "http://wts.dev/userpanel/about",
      dataType: "json",
      type: "POST",
      success: function(data){
        		$('#userbar_rub').text(data.rub+ " ");
        		$('#userbar_dol').text(data.dol+ " ");
        		$('#userbar_bonuses').text(data.bon+ " "); 
        		$('#my_items').text("Мои Товары (" +data.itemcount+")"); 	
        		$('#my_lots').text("Мои Лоты (" +data.lotcount+")"); 
	  },
	  error:function(){
	  	alert("ERROR");
	  }
        });
      }
	});
	//Users lots
	$('#my_lots').click(function(e){
		$('#tst').animate({
    height: "500"
  }, 500, function() {
  	$.ajax({   
                    url: URL+'userpanel/index',  
                    success: function(html){  
                        $("#userbar_content").html(html);  
                    }  
                });  
    $('#open_userbar').offset({top:500, right:5});
		$( "#tbl" ).empty();   
                $.ajax({   
                    url: URL+'userpanel/lots',  
                    success: function(html){  
                        $("#tbl").html(html); 
                        $('#tbl_name').text('Мои Лоты');                         
                    }  
                    //return false;
                });     
  });
	});
		//Users items
	$('#my_items').click(function(e){
		$( "#tbl" ).empty(); 
		$('#tst').height(500);
		/*$('#tst').animate({
    height: "500"
  }, 500, function() {*/
   // $('#open_userbar').offset({top:500, right:5});		  
                $.ajax({   
                    url: URL+'userpanel/items',  
                    success: function(html){  
                        $("#tbl").html(html);  
                        $('#tbl_name').text('Мои товары');
                        return false;
                    }  
                });     
 // });
  });
  //delete items by click
  	$("#userbar_content").on('click','a.ico.del', function(){
  		delItem = $(this);
  		var id = $(this).attr('rel');
		if(confirm("Подтвердите удаление...")){
				 $.ajax({   
                    url: URL+'userpanel/deleteitem/'+id,  
                    success: function(html){                     	
                    	alert(html);
                    	$('#my_items').click();
                    }  
                });  
			}
});
//Create lot
  	$("#userbar_content").on('click','a.ico.create', function(){
  		//delItem = $(this);
  		//var id = $(this).attr('rel');
		if(confirm("Перейти к созданию лота?")){
			                $.ajax({   
                    url: URL+form,  
                    success: function(html){                     	
                        $('#content').html(html);  
                        $('#form_titel').text('Создание лота');
                        $('#open_userbar').click();
                    }  
                }); 
                $.ajax({   
                    url: URL+'form/lotfields',  
                    success: function(html){
						 $('#form_fields').html(html);  
					}
                });  
                return false;   
			}
});
//new_item
  	$("#userbar_content").on('click','#new_item', function(){
  		newItem = $(this);
  		var form = $(this).attr('rel');
  		//===
  		$( "#content" ).empty();
		   //$( ".back" ).show();
                $.ajax({   
                    url: URL+form,  
                    success: function(html){                     	
                        $('#content').html(html);  
                        $('#form_titel').text('Новый товар');
                        $('#open_userbar').click();
                    }  
                });  
                return false;  
});


//ITEM===FORM==============================
  	$('#content').on('focus','#groups', function(){
  		//$( "#groups" ).empty();
  		$.ajax({   
                    url: URL+'form/groups',  
                    success: function(html){  
                        $('#groups').html(html);  
                    }  
                });  
});
  	$('#content').on('change','#groups', function(){
		var id = $('#groups').val();
		  		$.ajax({   
                   url: URL+'form/fields/'+id, 
                   //type: "POST",
                   //data: { grp: grp },
                    success: function(html){  
                        $('#form_fields').html(html);  
                    }  
                }); 
});
	$('#content').on('submit','.form-style', function(){
var data = $(this).serialize();
		  		$.ajax({   
                   url: URL+'form/createitem', 
                   type: "POST",
                   data: data,
                   success: function(){ 
                   		alert('Товар добавлен!');  
                    }  
                }); 
});
//===========UPLOADING=======================

});