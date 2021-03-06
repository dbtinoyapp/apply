;(function($) {
	
	/* Bootstrap modal */
	var Modal = $.fn.modal.Constructor;
	
	/* Constructor of the reloadable modal */
	var ReloadableModal = function (element, options) {
		this.cache     = {};
	    this.options   = options;
	    this.$element  = $(element);
	    this.$backdrop =
	    this.isShown   = null;
	    this.origHtml  = this.$element.html();
	    
	    
	    this.load = function(href, options)
	    {
	    	if (!this.cache[href]) {
	    		options = undefined === options ? this.options : options; 
	    		this.$element.html(this.origHtml);
	    		if (true === this.options.reloadable) {
	    			var $this = this;
	    			this.$element.load(href, function() {
	    				$this.cache[href] = $this.$element.html();
	    			});
	    			return;
	    		}
	    		
	    		var $this = this;
	    		if (options.usePost) {
	    			var data = options.postData ? options.postData : {};
	    			var promise = $.post(href, data);
	    			this.options.data = null;
	    		} else {
	    			var promise = $.get(href);
	    		}
	    		//$.get(href)
	    		promise
	    		.done(function(html) {
                                html = '<div>' + html + '</div>';
	    			 var $html = $(html);
	    			 var $element = $this.$element;
	    			 var found = false;
	    			 $html.each(function() {
	    				 var $htmlElement = $(this);
	    				 $.each(['header', 'title', 'body', 'footer'], function(i, name) {
	    					 var className = '.modal-' + name;
	    					 var $elem = $htmlElement.hasClass(className.substr(1))
	    					           ? $htmlElement
	    					           : $htmlElement.find(className);
	    					 
	    					 if ($elem.length) {
	    						 found = true;
	    						 var $orig = $element.find(className);
	    						 if ($orig.length) {
	    							 $orig.html($elem.html());
	    						 }
	    					 }
	    				 });
	    			 });
	    			 
	    			 if (!found && $element.find('.modal-' + $this.options.reloadable).length) {
	    				 $element.find('.modal-' + $this.options.reloadable).html(html);
	    			 }
	    			 $this.cache[href] = $this.$element.html();
	    		 })
	    		 .fail(function() {
	    			 var title = $this.$element.data('error-title');
	    			 var body  = $this.$element.data('error-message');
	    			 
	    			 if (!title) {
	    				 title = 'Error loading content.';
	    			 }
	    			 if (!body) {
	    				 body = '<p>An error occured while fetching the content of this modal box.</p>';
	    			 }
	    			 
	    			 $this.$element.find('.modal-title').html(title);
	    			 $this.$element.find('.modal-body').html(body);
	    			
	    		 });

	    	} else {
	    		this.$element.html(this.cache[href]);
	    	}
	    };
	    	    
	    this.load(this.options.remote);
	};
	
	ReloadableModal.prototype = $.fn.modal.Constructor.prototype;
	
	$.fn.modal = function (option, _relatedTarget) 
	{
		
	    return this.each(function () {
	      var $this   = $(this);
	      var data    = $this.data('bs.modal');
	      var options = $.extend({}, Modal.DEFAULTS, $this.data(), typeof option == 'object' && option);
	      if (options['reloadable']) {
	    	  if (!data) {
	    		  data = new ReloadableModal(this, options);
	    		  $this.data('bs.modal', data);
	    	  } else {
	    		  var href = options.remote 
	    		           ? options.remote
	    		           : $(_relatedTarget).attr('href').replace(/.*(?=#[^\s]+$)/, '');
	    		  
	    		  if (!/#/.test(href)) {
	    			  data.load(href, options);
	    		  }
	    	  }
	      } else {
	    	  data = new Modal(this, options);
	    	  $this.data('bs.modal', data);
	      }
    	
    	if (typeof option == 'string') data[option](_relatedTarget)
    	else if (options.show) data.show(_relatedTarget)
	  });
	};

})(jQuery);