$(document).ready(function () 
{
	$('#form').submit(function ()
	{
		event.preventDefault();
		$('#status').empty();
		var data = {
			url: $('#urlinput').val()
		}
		$.ajax(
		{
			type: "POST",
			url: "/",
			data: data,
			success: function (ResponseData) 
			{
				var response = $.parseJSON(ResponseData);
				if(response.success == true)
				{

				}
				else
				{
					$('#status').text('Please enter a valid URL');
				}		
			},
			error: function ()
			{
				$('#status').text('Sorry. There was an error. Please try again');
			}
		});
	});
});