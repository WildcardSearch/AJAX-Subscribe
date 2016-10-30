!function() {
	function onClick(e) {
		e.preventDefault();

		var data = {},
			params = this.href.split('?')[1].split('&'),
			x = 0,
			param;

		for (x = 0; x < params.length; x++) {
			param = params[x].split('=');
			data[param[0]] = param[1];
		}

		$.ajax('xmlhttp.php?action=subscribe&ajax=1', {
			data: {
				data: data,
			},
			complete: onComplete.bind(this),
		})
	}

	function onComplete(jqXHR, status) {
		var response = jqXHR.responseJSON;

		if (!response) {
			$.jGrowl('oops', {theme: 'jgrowl_error'});
			return;
		}

		if (response.errors) {
			$.jGrowl(response.errors, {theme: 'jgrowl_error'});
		} else {
			$.jGrowl(response.success, {theme: 'jgrowl_success'});

			if (this.href.indexOf('addsubscription') != -1) {
				this.href = this.href.replace('addsubscription', 'removesubscription');
			} else {
				this.href = this.href.replace('removesubscription', 'addsubscription');
			}
			
			$(this).html(response.linkText);
		}
	}

	function init() {
		$("a[href^='usercp2.php?action=addsubscription']").click(onClick);
		$("a[href^='usercp2.php?action=removesubscription']").click(onClick);
	}

	$(init);
}();