jQuery(document).ready(function ($) {
	/**
	 * Handle Student widget pagination
	 */

	/* Select all widgets on the screen in order for everything to work properly if there are more than one widget. */
	let student_widgets = $('[id*="dx-student-widget-"]');
	/* Iterate over each one */
	student_widgets.each(function () {
		let widget = $(this); //current widget.
		let widget_id = widget[0].id; //current widget id.
		let paginate_links = widget.find('#dx-student-pagination');
		paginate_links.on('click', 'a', function (e) {
			e.preventDefault();
			let link = $(this).attr('href');
			let widget_content = widget.find('#dx-student-list');
			let widget_pagination = widget.find('#dx-student-pagination');
			let widget_parent = widget.parent()[0].localName;
			widget_content.html('<img alt="loading.." src="/wp-content/plugins/students-plugin/public/assets/ajax-loader.gif">');
			widget_content.load(link + ' ' + widget_parent + ' > #' + widget_id + ' > #dx-student-list > *');
			widget_pagination.html('');
			widget_pagination.load(link + ' ' + widget_parent + ' > #' + widget_id + ' > #dx-student-pagination > *');
		});
	});
});