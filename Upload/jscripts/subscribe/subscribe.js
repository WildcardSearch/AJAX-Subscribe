/*
 * Plugin Name: AJAX Subscribe for MyBB 1.8.x
 * Copyright 2016 WildcardSearch
 * http://www.rantcentralforums.com
 *
 * observe subscribe/unsubscribe links and
 * divert functionality to XMLHttp
 */

!function() {
	/*
	 * gather data and execute the request
	 *
	 * @param  event
	 * @return void
	 */
	function onClick(e) {
		e.preventDefault();

		var data = {},
			params = this.href.split('?')[1].split('&'),
			x = 0,
			param;

		// pass the data from the original link
		for (x = 0; x < params.length; x++) {
			param = params[x].split('=');
			data[param[0]] = param[1];
		}

		$.ajax('xmlhttp.php?action=subscribe&ajax=1', {
			data: {
				data: data,
			},
			complete: onComplete.bind(this),
		});
	}

	/*
	 * report results and toggle the link
	 *
	 * @param  object jQuery XMLHttp Response
	 * @param  string
	 * @return void
	 */
	function onComplete(jqXHR, status) {
		var response = jqXHR.responseJSON;

		// error
		if (response.errors) {
			$.jGrowl(response.errors, {theme: 'jgrowl_error'});
		// success
		} else {
			$.jGrowl(response.success, {theme: 'jgrowl_success'});

			// toggle the link
			if (this.href.indexOf('addsubscription') != -1) {
				this.href = this.href.replace('addsubscription', 'removesubscription');
			} else {
				this.href = this.href.replace('removesubscription', 'addsubscription');
			}

			// toggle the link text
			$(this).html(response.linkText);
		}
	}

	/*
	 * observe the links
	 *
	 * @param  event
	 * @return void
	 */
	function init() {
		$("a[href^='usercp2.php?action=addsubscription']").click(onClick);
		$("a[href^='usercp2.php?action=removesubscription']").click(onClick);
	}

	$(init);
}();
