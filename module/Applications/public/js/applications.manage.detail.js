
;(function($) {
        var $emailInputs;
	var changeStatus = function(event)
	{
		var $target = $(event.target);
		
		if ($target.data('toggle')) {
			$("#mail-box-label").html($target.data('title'));
			$("#mail-box-content").load($target.data('href'), function(){
                                $(this).find('#noMail').on('change', toggleMessage);
                        });
		} else {
			$.get($target.data('href'))
			 .done(function() {
				 location.reload();
			 })
			 .fail(function(jqXHR, status, error) {
				 console.debug(jqXHR, status, error);
			 });
		}
		return;
		var uri = "/" + lang + "/applications/" 
		        + $target.data("applicationId") + "/"
		        + $target.data("action") + "?format=json";
		
		$.get(uri)
		 .done(function(result) { console.debug(result); })
		 .fail(function(jqXHR, textStatus, errorThrown) {
			 console.debug(jqXHR, textStatus, errorThrown);
		  });
		
		
		
	};
	
	var toggleMessage = function(event)
	{
            var $target = $(event.target);
            $emailInputs = $target.parents('form').find('.emailInputs');
            if ($target.is(':checked')) {
                $emailInputs.attr('disabled', 'disabled');
            }   else {
                $emailInputs.removeAttr('disabled');
            }
        };
	var forwardEmailHandler = function(event)
	{
		var displayResult = function(text, type)
		{
			alert = $('#forward-email-result');
			alert.addClass('ats-' + type);
			alert.html(text);
			alert.slideDown();
			window.setTimeout(function() { alert.removeClass('ats-' + type); alert.slideUp(); }, 3000);
		};
		
		var $form = $(event.target);
		if ('' == $form.find('#forward-email-input').val()) {
			return false;
		}
		
		var alert = $('#forward-email-result');
		
		console.debug(alert);
		
		$.get($form.attr('action') + '?' + $form.serialize())
		 .done(function (data) {
			 displayResult(data.text, data.ok ? 'success' : 'error');
		 })
		 .fail(function (jqXHR, textStatus, errorThrown) {
			 displayResult('Unexpected error: ' + jqXHR.status + ' ' + jqXHR.statusText, 'error');
		 });
		return false;
	};
	
	var commentsDialog = function()
	{
		var forceListReload = true;
		
		var showDialog = function(mode)
		{
			if ('list' == mode) {
				$dialog.find('#ats-application-comments-cancelbtn').addClass('hide');
				$dialog.find('#ats-application-comments-savebtn').addClass('hide');
				$dialog.find('#ats-application-comments-addbtn').removeClass('hide');
				//$dialog.find('#ats-application-comments-closebtn').removeClass('hide');
			} else {
				$dialog.find('#ats-application-comments-cancelbtn').removeClass('hide');
				$dialog.find('#ats-application-comments-savebtn').removeClass('hide');
				$dialog.find('#ats-application-comments-addbtn').addClass('hide');
				//$dialog.find('#ats-application-comments-closebtn').addClass('hide');
			}
			if (!$dialog.data('modal') || !$dialog.data('modal').isShown) {
				$dialog.modal('show');
			}
		};
		
		var replaceContent = function(html, err)
		{
			if (err) {
				html = '<div class="alert ats-error"><p>' + html + '</p></div>';
			}
			$dialog.find('.modal-body').html(html);
			$loader.addClass('hide');
		};
		
		var loadList = function(event)
		{
			showDialog('list');
			
			if (!forceListReload) {
				return;
			}
			
			replaceContent('');
			$loader.removeClass('hide');
			
			var href    = $dialog.data('list-url');
			
			$.get(href)
			 .done(function(data) { 
				 replaceContent(data); 
				 forceListReload=false; 
				 $dialog.find('.modal-body button.comment-edit').click(loadForm);
			 })
			 .fail(function() { 
				 replaceContent($dialog.data('list-errormessage'), true);
			 });
			
		};
		
		var loadForm = function(event)
		{
			forceListReload = true;
			
			showDialog('form');
			replaceContent('');
			$loader.removeClass('hide');
			
			var href = $dialog.data('form-url');
			var $target = $(event.target);
			if ($target.data('comment-id')) {
				href += '?mode=edit&id=' + $target.data('comment-id');
			} else {
				href += '?mode=new&id=' + $dialog.data('application-id');
			}
			
			$.get(href)
			 .done(function(data) {
				 replaceContent(data);
				 $dialog.find('.modal-body .rating').barrating();
			 })
			 .fail(function() { replaceContent($dialog.data('form-errormessage'), true); });
		};
		
		var submitForm = function(event)
		{
			$loader.removeClass('hide');
			$form = $('#application-comment-form');
			console.debug($form.attr('action'));
			$.post($form.attr('action'), $form.serialize())
			 .done(function(data) { 
				 if ('ok' == data) { 
					 loadList();
					 $('#application-rating').load(basePath + '/' + lang + '/applications/'
							                       + $dialog.data('application-id') 
							                       + '?do=refresh-rating');
				 } else { 
					 replaceContent(data);
					 $dialog.find('.modal-body .rating').barrating(); 
				 }
			 })
			 .fail(function() { replaceContent($dialog.data('form-errormessage'), true); });
			
		}
		
		$dialog = $('#ats-application-comments');
		$loader = $dialog.find('.modal-header h3 i');
		
		$('#ats-applications-comments-toggle, #ats-application-comments-cancelbtn').click(loadList);
		$('#ats-application-comments-addbtn, #ats-applications-comments-quickadd' ).click(loadForm);
		$('#ats-application-comments-savebtn').click(submitForm);
		
	};
	
	
	$(function() {
		$('#state-actions button').click(changeStatus);
		
		$('#forward-email span').popover();
		$('#forward-email-form').submit(forwardEmailHandler);
		commentsDialog();
		
	});
	
})(jQuery);