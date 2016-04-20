$(document).ready(function () {

    $('#login').click(function(event){ // нажатие на кнопку - выпадает модальное окно
        event.preventDefault();
         
        var url = '/login';
        var clickedbtn = $(this);

        var modalContainer = $('#my-modal');
        var modalBody = modalContainer.find('.modal-body');
        modalContainer.modal({show:true});

        $.ajax({
            url: url,
            type: "GET",
            data: {/*'userid':UserID*/},
            success: function (data) {
                $('.modal-body').html(data);
                modalContainer.modal({show:true});
            }
        });
    });

    $(document).on("submit", '.login-form', function (e) {
        e.preventDefault();
        var form = $(this);
        var result;
        $.ajax({
            url: "/submitlogin",
            type: "POST",
			scriptCharset: "utf-8",
            data: form.serialize(),
            success: function (data) {
                    var modalContainer = $('#my-modal');
    		        var modalBody = modalContainer.find('.modal-body');
					var insidemodalBody = modalContainer.find('.gb-user-form');
				try
				{
	                result = jQuery.parseJSON(data);
    				console.log(result);

					if (result.flag == true) {
						insidemodalBody.html(result).hide(); 
						$('#my-modal').modal('hide');
                        /*$('#userlabel').label(result.username + " / " + result.phone);
                        $('#userlabel').css("display","block");
	
						$('#login').css("display", "none");
						$('#signup').css("display", "none");*/
                        location.reload();
						return true;
					}
                }
				catch(e){
                    modalBody.html(data).hide().fadeIn();
					return true;
	            }
            },
        });
    });

    $('#signup').click(function(event){ // нажатие на кнопку - выпадает модальное окно
        event.preventDefault();
         
        var url = '/signup';
        var clickedbtn = $(this);

        var modalContainer = $('#signup-modal');
        var modalBody = modalContainer.find('.modal-body');
        modalContainer.modal({show:true});
        $.ajax({
            url: url,
            type: "GET",
            data: {/*'userid':UserID*/},
            success: function (data) {
                $('.modal-body').html(data);
                modalContainer.modal({show:true});
            }
        });
    });


    $(document).on("submit", '.signup-form', function (e) {
        e.preventDefault();
        var form = $(this);
        $.ajax({
            url: "/submitsignup",
            type: "POST",
			scriptCharset: "utf-8",
            data: form.serialize(),
            success: function (data) {
                var modalContainer = $('#signup-modal');
                var modalBody = modalContainer.find('.modal-body');
                var insidemodalBody = modalContainer.find('.gb-user-form');
				try
				{
	                result = jQuery.parseJSON(data);
    				console.log(result);
				//	alert(result.username);
	                       
					if (result.flag == true) {
						insidemodalBody.html(result).hide(); 
						$('#signup-modal').modal('hide');
						/*$('#userlabel').text(result.username + " / "+result.phone);
                        $('#userlabel').css("display","block");

						$('#login').css("display", "none");
						$('#signup').css("display", "none");
                           */ location.reload();
						return true;
					}
                }
				catch(e){
                    modalBody.html(data).hide().fadeIn();
					return true;
	            }
            },
        });
    });


});