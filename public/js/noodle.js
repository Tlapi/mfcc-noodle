$(function() {

	// List delete dialog
	$('table a.list-delete').click(function(){
		$('#myModal a.confirm-delete').attr('href', $('#myModal a.confirm-delete').attr('rel').replace("ID_PLACEHOLDER", $(this).parent().parent().attr('rel')));
	});
	
	// Form elements

	// Picture element
	$('.form_picture .remove').click(function(){
		var currentInput = $(this).parent().find('input');
		$(this).parent().find('img').hide();
		$(this).parent().find('a').hide();
		$('#fileupload').show();
		//$("<input type='file' />").attr({ name: currentInput.attr('name') }).insertBefore(currentInput);
		//currentInput.remove();
		currentInput.val('');
		return false;
	});

	// file uploader
	$('#fileupload').fileupload({
        dataType: 'json',
        progressall: function (e, data) {
        	var progressBar = $(this).parent().find('.progress .bar');
            var progress = parseInt(data.loaded / data.total * 100, 10);
            progressBar.css(
                'width',
                progress + '%'
            );
        	//alert('progress ' + data.loaded);
        },
        done: function (e, data) {
        	var form_picture = $(this).parent().find('.form_picture');

        	$(this).hide();

        	form_picture.show();
        	form_picture.find('img').show();
        	form_picture.find('a').show();
        	form_picture.find('img').attr('src', data.result.files[0].thumbnailUrl);
        	form_picture.find('input').attr('value', data.result.files[0].name);
        }
    });
	
	// Mass operations
	$('input.mass_checked').click(function(){
		if($('input.mass_checked:checked').length > 0){
			var checkedArray = [];
			$('input.mass_checked:checked').each(function( index ) {
				checkedArray.push($(this).val());
			});
			//alert(checkedArray.serializeArray());
			$('#massOperations').show();
		} else {
			$('#massOperations').hide();
			$('#massOperations a.mass-delete').attr('href', '#');
		}
		
	});

});