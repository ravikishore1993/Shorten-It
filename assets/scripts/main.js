$(document).ready(function () 
{
	$('#ajax-loader').hide();
	$('#form').submit(function ()
	{
		event.preventDefault();
		if($('#urlinput').val().trim().length == 0)
			return;
		$('#status').empty();
		var data = {
			url: $('#urlinput').val()
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
					url = $('#urlinput').val();
				    if (!/^(?:f|ht)tps?\:\/\//.test(url)) 
					{
        				url = "http://" + url;
    				}
					$('#status').html('Url shortening success for <a href="'+url+'">'+((url.length > 25 )?url.substring(0,25)+'...' :url) +'</a> ');
					$('#urlinput').val(response['url']);

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