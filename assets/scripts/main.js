$(document).ready(function () 
{
	$('#ajax-loader').hide();
	
	$('#checkboxid').change(function ()
	{
		if($('#checkboxid').is(':checked'))
		{
			$('#passdiv').show(200,"linear",function (){
				$('#passinput').focus();
			});			
		}
		else
		{
			$('#passdiv').hide(200,"linear",function(){
				$('#urlinput').focus();
			});
		}

	});

	$('#form').submit(function ()
	{
		privateLink = 0;
		password = "";
		event.preventDefault();
		if($('#urlinput').val().trim().length == 0)
		{
			$('#status').text('Please enter a valid URL');
			return;
		}
		if($('#checkboxid').is(':checked'))
		{
			if($('#passinput').val().trim().length == 0)
			{
				$('#status').text('Please enter a password');
				return;	
			}
			else
			{
				privateLink = 1;
				password = $('#passinput').val().trim();
			}
		}
		$('#status').empty();
		var data = {
			url: $('#urlinput').val(),
			privateLink: privateLink,
			password: password
		}
		$('#form').hide();
		$('#ajax-loader').show();
		$.ajax(
		{
			type: "POST",
			url: "/",
			data: data,
			success: function (ResponseData) 
			{
				$('#ajax-loader').hide();
				$('#form').show();
				var response = $.parseJSON(ResponseData);
				if(response.success == true)
				{
					$('#passinput').val("");
					$('#passdiv').hide();
					if($('#checkboxid').is(':checked'))
						$('#checkboxid').attr('checked',false);
					url = $('#urlinput').val();
				    if (!/^(?:f|ht)tps?\:\/\//.test(url)) 
					{
        				url = "http://" + url;
    				}
					$('#status').html('Url shortening success for <a href="'+url+'">'+((url.length > 25 )?url.substring(0,25)+'...' :url) +'</a> ');
					$('#urlinput').val(response['url']).select();

				}
				else
				{
					$('#status').text('Please enter a valid URL');
				}		
			},
			error: function ()
			{
				$('#ajax-loader').hide();
				$('#form').show();
				$('#status').text('Sorry. There was an error. Please try again');
			}
		});
	});
});