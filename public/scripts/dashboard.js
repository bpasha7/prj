$(document).ready(function($){
        var URL = 'http://wts.dev/';
        var h = 0;

        //==MENU===============================================
        $('#nav li a').click(function(){
                var menuId = $(this).attr('rel');
                if(menuId !== undefined)
                {
                    $.ajax({
                            url: URL+menuId,
                            success: function(html){
                                $('#content').html(html);
                            }
                        });
                    switch(menuId){
                        case 'menu/index':
                        $.ajax({
                                url: URL+'menu/top',
                                success: function(html){
                                    $('.plans').html(html);
                                    //$('#item_id').val(lotId);
                                }
                            });
                        break;

                        default:
                        break;
                    }
                }
            });
        //===Auction=================================
        //Search lots
        $("#content").on('submit','#search_bar', function(){
                var pattern = $(this).serialize();
                $.ajax({
                        url: URL+'menu/searchlots',
                        type: "POST",
                        data: pattern,
                        success: function(html){
                            $('#auction_lots').html(html);
                        }
                    });
                return false;
            });
        $("#content").on('click','a.to_lot', function(){
                var ItemId = $(this).attr('rel');
                var LotId = $(this).attr('lot');
                window.open( URL+'lot/newpage/'+ItemId+':'+LotId, '_blank');
                /* $.ajax({
                url: URL+'lot/index/'+ItemId+':'+LotId,
                success: function(html){


                //$('#content').html(html);
                }
                });*/
            });
        //===========================================
        //==StickMenu=====================
        /* var MenuStick = $("#container"); //Получаем нужный объект
        var topOfMenuStick = $(MenuStick).offset().top; //Получаем начальное расположение нашего блока

        $(window).scroll(function () {
        var windowScroll = $(window).scrollTop(); //Получаем величину, показывающую на сколько прокручено окно

        if (windowScroll > topOfMenuStick) { // Если прокрутили больше, чем расстояние до блока, то приклеиваем его
        $(MenuStick).addClass("topWindow");
        } else {
        $(MenuStick).removeClass("topWindow");
        };
        });*/
        //====================================================
        //===Lot script====
        $("#content").on('submit','#bet', function(){
                var bid = $(this).serialize();
                $.ajax({
                        url: URL+'lot/newbid',
                        type: "POST",
                        data:  bid,
                        success: function(html){
                            //setTimeout(function(html) { alert(html) }, 1000);
                            $('#bet').html(html)
                        }
                    });
                return false;
            });
        $("#content").on('click','a#writeto', function(){
                var who = $(this).attr('rel');
                $('#typing_text').text(who+', ');
                $('#typing_text').focus();

            });
        $("#content").on('click','#sent_msg', function(){
                var message = {
                    'msg' : $('#typing_text').val(),
                    'lot': $(this).attr('lot')
                };
                var t = JSON.stringify(message)
                $.ajax({
                        url: URL+'lot/addcomment',
                        type: "POST",
                        data:  message,
                        success: function(html){
                            if(html!='NO')
                            $('#content2').html(html);
                            else
                            alert('Не получилось отправить!');
                        }
                    });
            });
        $("#content").on('click','.rating span', function(){
                var stars = {
                    'count' : $(this).attr('starnum'),
                    'comment': $(this).parent().attr('coment'),
                    'lot': $('.rating').attr('lot')
                };
                var t = JSON.stringify(stars);
                $.ajax({
                        url: URL+'lot/addstars',
                        type: "POST",
                        data:  stars,
                        success: function(html){
                            if(html!='NO')
                            $('#content2').html(html);
                            else
                            alert('Вы не можете оценить!');
                        }
                    });

            });
        $("#content").on('click','a.plan-button', function(){
                var ItemId = $(this).attr('rel');
                var LotId = $(this).attr('lot');
                window.open( URL+'lot/newpage/'+ItemId+':'+LotId, '_blank');
                /* $.ajax({
                url: URL+'lot/index/'+ItemId+':'+LotId,
                success: function(html){
                $('#content').html(html);
                }
                });*/
            });
        $("#content").on('click','#slider img', function(e){
                var Imgid = $(this).attr('rel');
                $('#images').animate({
                        opacity: 0
                    },
                    500, function(){
                        $('#images').children().hide();
                        $('#'+Imgid).show();
                    }
                );
                $('#images').animate({
                        opacity: 1
                    },
                    500);
            });
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
                            $('#login_back').fadeOut("fast");
                        },
                        error: function(data) {
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
        //Hide login back
        $("#login_back").click(function(e){
                if(e.target == this)
                $(this).fadeOut("fast");
            });
        //Registration
        $("#login_back").on('click', '#create_account', function(e){
                $('#login_back').fadeOut("fast");
                //var data = $(this).serialize();
                $.ajax({
                        url: URL+'form',
                        success: function(html){
                            $('#content').html(html);
                            $('#form_titel').text('Форма Регистрации');
                        }
                    });
                $.ajax({
                        url: URL+'form/registrationfields',
                        success: function(html){
                            $('#form_fields').html(html);
                        }
                    });
                //return false;
            });
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
                                url: URL+'userpanel/lots',
                                success: function(html){
                                    $("#tbl").html(html);
                                    $('#tbl_name').text('Мои Лоты');
                                }
                            });
                        $('#open_userbar').offset({top:500, right:5});
                        $('#box').show();
                    });
            });
        //Users items
        $('#my_items').click(function(e){
                $('#tst').animate({
                        height: "500"
                    }, 500, function() {
                        $.ajax({
                                url: URL+'userpanel/items',
                                success: function(html){
                                    $("#tbl").html(html);
                                    $('#tbl_name').text('Мои товары');
                                   
                                }
                            });
                         $('#open_userbar').offset({top:500, right:5});
                       	 $('#box').show();
                    });
            });
        //Users bets
        $('#my_bets').click(function(e){
			$('#tst').animate({
                        height: "500"
                    }, 500, function() {
                        $.ajax({
                                url: URL+'userpanel/bets',
                                success: function(html){
                                    $("#tbl").html(html);
                                    $('#tbl_name').text('Ставки');
                                }
                            });
                        $('#open_userbar').offset({top:500, right:5});
                        $('#box').show();
                    });
            });
        //delete items by click
        $("#userbar_content").on('click','a.ico.del', function(){
                var obj =  $(this).attr('name');
                var id = $(this).attr('rel');
                if(confirm("Подтвердите удаление...")){
                    $.ajax({
                            url: URL+'userpanel/delete'+obj+'/'+id,
                            success: function(html){
                                alert(html);
                                $('#my_'+obj+'s').click();
                            }
                        });
                }
            });
        //Create lot
        $("#userbar_content").on('click','a.ico.create', function(){
                var lotName = $(this).attr('name');
                var lotId = $(this).attr('rel');
               
                if(confirm("Перейти к созданию лота <"+lotName+">?")){
                	  $.ajax({
                            url: URL+'form',
                            success: function(html){
                                $('#content').html(html);
                                $('#form_titel').text('Создание лота<' + lotName +'>');
                                $('#open_userbar').click();
                    $.ajax({
                            url: URL+'form/lotfields',
                            success: function(html){
                                $('#form_fields').html(html);
                                $('#item_id').val(lotId);
                                //$('#lot_name').attr('rel') = '234';
                                //$('#lot_name').val(lotName);
                            }
                        });
                                                    }
                        });
                    return false;
                }
            });
        //new_item
        $("#userbar_content").on('click','#new_item', function(){
                newItem = $(this);
                var form = $(this).attr('rel');
                $( "#content" ).empty();
                $.ajax({
                        url: URL+form,
                        success: function(html){
                            $('#content').html(html);
                            $('#form_titel').text('Новый товар');
                            $('.styled-select').show();
                            $('#open_userbar').click();
                        }
                    });
                return false;
            });


        //===FORMS==============================
        //Loading groups
        $('#content').on('focus','#groups', function(){
                //$( "#groups" ).empty();
                $.ajax({
                        url: URL+'form/groups',
                        success: function(html){
                            $('#groups').html(html);
                        }
                    });
            });
        //Selecting group
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
        //Submitting forms
        $('#content').on('submit','#create_form', function(){
                //if lot or item
                var data = $(this).serialize();
                var formName = $('#submit_form').attr('name');
                switch(formName){
                    case 'reg':
                    if($('#pass1').val()!=$('#pass2').val())
                    {
                        alert('Пароли не совпадают, повторите ввод!');
                        $('#pass1').val('');
                        $('#pass2').val('');
                        return false;
                    }
                    $.ajax({
                            url: URL+'form/registration',
                            type: "POST",
                            data: data,
                            success: function(res){
                                if(res == 'OK'){
                                    alert('Вы зарегистрированы!');
                                    $('#content').empty();
                                    $('html, body').animate({scrollTop:0}, 'slow');
                                }
                                else{
                                    alert(res);
                                }
                            }
                        });
                    break;
                    case 'lot':
                    $.ajax({
                            url: URL+'form/createlot',
                            type: "POST",
                            data: data,
                            success: function(res){
                                if(res == 'OK'){
                                    alert('Лот Успешно создан');
                                    $('#content').empty();
                                    $('html, body').animate({scrollTop:0}, 'slow');
                                }
                                else{
                                    alert(res);
                                }
                            }
                        });
                    break;
                    case 'item':
                    $.ajax({
                            url: URL+'form/createitem',
                            type: "POST",
                            data: data,
                            success: function(res){
                                if(res == 'OK'){
                                    alert('Товар успешно добавлен');
                                    $('#content').empty();
                                    $('html, body').animate({scrollTop:0}, 'slow');
                                }
                                else{
                                    alert(res);
                                }
                            }
                        });
                    break;

                    default:
                    break;
                }

            });
        //===========UPLOADING=======================
        var progressbox     = $('#progressbox');
        var progressbar     = $('#progressbar');
        var statustxt       = $('#statustxt');
        var completed       = '0%';

        var options = {
            target:   '#output',   // target element(s) to be updated with server response
            beforeSubmit:  beforeSubmit,  // pre-submit callback
            uploadProgress: OnProgress,
            success:       afterSuccess,  // post-submit callback
            resetForm: true        // reset the form after successful submit
        };

        // $('#MyUploadForm').submit(function() {
        $('#content').on('click','#submit-btn', function(){
                $('#MyUploadForm').ajaxSubmit(options);
                // return false to prevent standard browser submit and page navigation
                return false;
            });

        //when upload progresses
        function OnProgress(event, position, total, percentComplete)
        {
            //Progress bar
            $('#progressbar').width(percentComplete + '%') //update progressbar percent complete
            $('#statustxt').html(percentComplete + '%'); //update status text
            if(percentComplete>50)
            {
                statustxt.css('color','#fff'); //change status text to white after 50%
            }
        }

        //after succesful upload
        function afterSuccess()
        {
            $('#submit-btn').show(); //hide submit button
            $('#loading-img').hide(); //hide submit button

        }

        //function to check file size before uploading.
        function beforeSubmit(){
            //check whether browser fully supports all File API
            if (window.File && window.FileReader && window.FileList && window.Blob)
            {

                if( !$('#files').val()) //check empty input filed
                {
                    $("#output").html("Are you kidding me?");
                    return false
                }

                //Progress bar
                $('#progressbox').show(); //show progressbar
                $('#progressbar').width(completed); //initial value 0% of progressbar
                $('#statustxt').html(completed); //set status text
                $('#statustxt').css('color','#000'); //initial color of status text


                $('#submit-btn').hide(); //hide submit button
                $('#loading-img').show(); //hide submit button
                $("#output").html("");
            }
            else
            {
                //Output error to older unsupported browsers that doesn't support HTML5 File API
                $("#output").html("Please upgrade your browser, because your current browser lacks some new features we need!");
                return false;
            }
        }

        //function to format bites bit.ly/19yoIPO
        function bytesToSize(bytes) {
            var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
            if (bytes == 0) return '0 Bytes';
            var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
            return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
        }




    });