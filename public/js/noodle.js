$(function() {

	// List delete dialog
	$('table a.list-delete').click(function(){
		$('#myModal a.confirm-delete').attr('href', $('#myModal a.confirm-delete').attr('rel').replace("ID_PLACEHOLDER", $(this).parent().parent().attr('rel')));
	});

	// Form elements

	// Picture element
	$('.form_picture_container .remove').click(function(){
		var currentInput = $(this).parent().find('input');
		$(this).parents('.form_picture_container').find('img').hide();
		$(this).parents('.form_picture_container').find('a.remove').hide();
		$(this).parents('.form_picture_container').find('h4').hide();
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
        	var progressBar = $(this).parents('.form_picture_container').find('.progress .bar');
        	$(this).parents('.form_picture_container').find('.progress').show();
            var progress = parseInt(data.loaded / data.total * 100, 10);
            progressBar.css(
                'width',
                progress + '%'
            );
        	//alert('progress ' + data.loaded);
        },
        done: function (e, data) {
        	var form_picture = $(this).parents('.form_picture_container').find('.form_picture');

        	$(this).hide();

        	$(this).parents('.form_picture_container').find('.progress').hide();

        	form_picture.show();
        	form_picture.find('img').show();
        	$(this).parents('.form_picture_container').find('a.remove').show();
        	form_picture.find('img').attr('src', data.result.files[0].thumbnailUrl);
        	form_picture.find('input').attr('value', data.result.files[0].name);
        }
    });

	// Mass operations
	$('input.mass_checked').click(function(){
		if($('input.mass_checked:checked').length > 0){
			var checkedArray = [];
			$('#massDelete input[name="ids[]"]').remove();
			$('input.mass_checked:checked').each(function( index ) {
				$('#massDelete').append('<input type="hidden" name="ids[]" value="'+$(this).val()+'" />')
			});
			$('#massOperations').show();
		} else {
			$('#massOperations').hide();
		}

	});

	// Redactor
	$('textarea').attr('required', false);
	$('textarea').redactor({
		focus: true,
		imageUpload: '../../filesystem/upload?redactor'
	});

});