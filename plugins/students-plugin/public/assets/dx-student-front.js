jQuery(document).ready(function ($) {
	/**
	 * Handle Student widget pagination
	 */
	let paginate_links = $('#dx-student-pagination')
	paginate_links.on('click', 'a', function (e) {
		e.preventDefault();
		let link = $(this).attr('href');
		let widget_content = $('#dx-student-widget');
		let widget_pagination = $('#dx-student-pagination');
		widget_content.html('<img src="/wp-content/plugins/students-plugin/public/assets/ajax-loader.gif">');
		widget_content.load(link + ' #dx-student-widget');
		widget_pagination.html('');
		widget_pagination.load(link + ' #dx-student-pagination');
	});
});