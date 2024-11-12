<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Application Name
	|--------------------------------------------------------------------------
	|
	| This value is the name of your application. This value is used when the
	| framework needs to place the application's name in a notification or
	| any other location as required by the application or its packages.
	|
	*/

	'name' => env('APP_NAME', 'Vora Laravel'),


	'public' => [
		'global' => [
			'css' => [
				'vendor/bootstrap-select/dist/css/bootstrap-select.min.css',
				'css/style.css',
			],
			'js' => [
				'top'=> [
					'vendor/global/global.min.js',
					'vendor/bootstrap-select/dist/js/bootstrap-select.min.js',
				],
				'bottom'=> [
					'js/custom.min.js',
					'js/dlabnav-init.js',
				],
			],
		],
		'pagelevel' => [
			'css' => [
				'VoraAdminController_dashboard_1' => [
					'vendor/chartist/css/chartist.min.css',
					'vendor/jqvmap/css/jqvmap.min.css',
					'vendor/owl-carousel/owl.carousel.css',
				],
				'VoraAdminController_dashboard_2' => [
					'vendor/chartist/css/chartist.min.css',
					'vendor/jqvmap/css/jqvmap.min.css',
					'vendor/owl-carousel/owl.carousel.css',
				],
				'VoraAdminController_projects' => [
					'vendor/chartist/css/chartist.min.css',
					'vendor/datatables/css/jquery.dataTables.min.css',
				],
				'VoraAdminController_contacts' => [
					'vendor/chartist/css/chartist.min.css'
				],
				'VoraAdminController_kanban' => [
					'vendor/chartist/css/chartist.min.css',
				],
				'VoraAdminController_calendar' => [
					'vendor/chartist/css/chartist.min.css',
					'vendor/datatables/css/jquery.dataTables.min.css',
					'vendor/fullcalendar-5.11.0/lib/main.css',
				],
				'VoraAdminController_messages' => [
					'vendor/chartist/css/chartist.min.css',
					'vendor/datatables/css/jquery.dataTables.min.css',
				],
				'VoraAdminController_app_calender' => [
					'vendor/fullcalendar-5.11.0/lib/main.css',
				],
				'VoraAdminController_app_profile' => [
					'vendor/lightgallery/css/lightgallery.min.css',
				],
				'VoraAdminController_edit_profile' => [
					'vendor/bootstrap-daterangepicker/daterangepicker.css',
					'vendor/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css',
					'vendor/jquery-nice-select/css/nice-select.css',
					'vendor/lightgallery/css/lightgallery.min.css',
					'https://fonts.googleapis.com/css2?family=Material+Icons',
				],
				'VoraAdminController_post_details' => [
					'vendor/lightgallery/css/lightgallery.min.css',
				],
				'VoraAdminController_chart_chartist' => [
					'vendor/chartist/css/chartist.min.css',
				],
				'VoraAdminController_chart_chartjs' => [
				],
				'VoraAdminController_chart_flot' => [

				],
				'VoraAdminController_chart_morris' => [
				],
				'VoraAdminController_chart_peity' => [
				],
				'VoraAdminController_chart_sparkline' => [
				],
				'VoraAdminController_ecom_checkout' => [
				],
				'VoraAdminController_ecom_customers' => [
				],
				'VoraAdminController_ecom_invoice' => [
				],
				'VoraAdminController_ecom_product_detail' => [
					'vendor/star-rating/star-rating-svg.css',
				],
				'VoraAdminController_ecom_product_grid' => [
				],
				'VoraAdminController_ecom_product_list' => [
					'vendor/star-rating/star-rating-svg.css',
				],
				'VoraAdminController_ecom_product_order' => [
				],
				'VoraAdminController_email_compose' => [
					'vendor/dropzone/dist/dropzone.css',
				],
				'VoraAdminController_email_inbox' => [
				],
				'VoraAdminController_email_read' => [
				],
				'VoraAdminController_form_ckeditor' => [
					'vendor/summernote/summernote.css',
				],
				'VoraAdminController_form_element' => [
				],
				'VoraAdminController_form_pickers' => [
					'vendor/bootstrap-daterangepicker/daterangepicker.css',
					'vendor/clockpicker/css/bootstrap-clockpicker.min.css',
					'vendor/jquery-asColorPicker/css/asColorPicker.min.css',
					'vendor/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css',
					'vendor/pickadate/themes/default.css',
					'vendor/pickadate/themes/default.date.css',
					'https://fonts.googleapis.com/icon?family=Material+Icons'
				],
				'VoraAdminController_form_validation_jquery' => [
				],
				'VoraAdminController_form_wizard' => [
					'vendor/jquery-smartwizard/dist/css/smart_wizard.min.css',
				],
				'VoraAdminController_map_jqvmap' => [
					'vendor/jqvmap/css/jqvmap.min.css',
				],
				'VoraAdminController_table_bootstrap_basic' => [
				],
				'VoraAdminController_table_datatable_basic' => [
					'vendor/datatables/css/jquery.dataTables.min.css',
				],
				'VoraAdminController_uc_lightgallery' => [
					'vendor/lightgallery/css/lightgallery.min.css',
				],
				'VoraAdminController_uc_nestable' => [
					'vendor/nestable2/css/jquery.nestable.min.css',
				],
				'VoraAdminController_uc_noui_slider' => [
					'vendor/nouislider/nouislider.min.css',
				],
				'VoraAdminController_uc_select2' => [
					'vendor/select2/css/select2.min.css',
				],
				'VoraAdminController_uc_sweetalert' => [
					'vendor/sweetalert2/dist/sweetalert2.min.css',
				],
				'VoraAdminController_uc_toastr' => [
					'vendor/toastr/css/toastr.min.css',
				],
				'VoraAdminController_ui_accordion' => [
				],
				'VoraAdminController_ui_alert' => [
				],
				'VoraAdminController_ui_badge' => [
				],
				'VoraAdminController_ui_button' => [
				],
				'VoraAdminController_ui_button_group' => [
				],
				'VoraAdminController_ui_card' => [
				],
				'VoraAdminController_ui_carousel' => [
				],
				'VoraAdminController_ui_dropdown' => [
				],
				'VoraAdminController_ui_grid' => [
				],
				'VoraAdminController_ui_list_group' => [
				],
				'VoraAdminController_ui_media_object' => [
				],
				'VoraAdminController_ui_modal' => [
				],
				'VoraAdminController_ui_pagination' => [
				],
				'VoraAdminController_ui_popover' => [
				],
				'VoraAdminController_ui_progressbar' => [
				],
				'VoraAdminController_ui_tab' => [
				],
				'VoraAdminController_ui_typography' => [
				],
				'VoraAdminController_widget_basic' => [
					'vendor/chartist/css/chartist.min.css',
				],
			],
			'js' => [
				'VoraAdminController_dashboard_1' => [
					'vendor/chart.js/Chart.bundle.min.js',
					'vendor/owl-carousel/owl.carousel.js',
					'vendor/peity/jquery.peity.min.js',
					'vendor/jquery-nice-select/js/jquery.nice-select.min.js',
					'vendor/apexchart/apexchart.js',
					'js/dashboard/dashboard-1.js',
				],
				'VoraAdminController_dashboard_2' => [
					'vendor/chart.js/Chart.bundle.min.js',
					'vendor/owl-carousel/owl.carousel.js',
					'vendor/peity/jquery.peity.min.js',
					'vendor/jquery-nice-select/js/jquery.nice-select.min.js',
					'vendor/apexchart/apexchart.js',
					'js/dashboard/dashboard-1.js',
				],
				'VoraAdminController_projects' => [
					'vendor/chart.js/Chart.bundle.min.js',
					'vendor/datatables/js/jquery.dataTables.min.js',
				],
				'VoraAdminController_contacts' => [
					'vendor/chart.js/Chart.bundle.min.js',
				],
				'VoraAdminController_kanban' => [
					'vendor/chart.js/Chart.bundle.min.js',
					'vendor/draggable/draggable.js'
				],
				'VoraAdminController_calendar' => [
					'vendor/chart.js/Chart.bundle.min.js',
					'vendor/jqueryui/js/jquery-ui.min.js',
					'vendor/moment/moment.min.js',
					'vendor/fullcalendar-5.11.0/lib/main.min.js',
					'js/plugins-init/fullcalendar-init.js',
				],
				'VoraAdminController_messages' => [
					'vendor/chart.js/Chart.bundle.min.js',
				],
				'VoraAdminController_app_calender' => [
					'vendor/jqueryui/js/jquery-ui.min.js',
					'vendor/moment/moment.min.js',
					'vendor/fullcalendar-5.11.0/lib/main.js',
					'js/plugins-init/fullcalendar-init.js',
				],
				'VoraAdminController_app_profile' => [
					'vendor/lightgallery/js/lightgallery-all.min.js',
				],
				'VoraAdminController_edit_profile' => [
					'vendor/jquery-nice-select/js/jquery.nice-select.min.js',
					'vendor/moment/moment.min.js',
					'vendor/chart.js/Chart.bundle.min.js',
					'vendor/lightgallery/js/lightgallery-all.min.js',
					'vendor/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js',
					'js/plugins-init/material-date-picker-init.js',
				],
				'VoraAdminController_post_details' => [
					'vendor/lightgallery/js/lightgallery-all.min.js',
					'js/custom.js',
					'js/dlabnav-init.js',
				],
				'VoraAdminController_chart_chartist' => [
					'vendor/chartist/js/chartist.min.js',
					'vendor/chartist-plugin-tooltips/js/chartist-plugin-tooltip.min.js',
					'js/plugins-init/chartist-init.js',
				],
				'VoraAdminController_chart_chartjs' => [
					'vendor/chart.js/Chart.bundle.min.js',
					'js/plugins-init/chartjs-init.js',
				],
				'VoraAdminController_chart_flot' => [
					'vendor/flot/jquery.flot.js',
					'vendor/flot/jquery.flot.pie.js',
					'vendor/flot/jquery.flot.resize.js',
					'vendor/flot-spline/jquery.flot.spline.min.js',
					'js/plugins-init/flot-init.js',
				],
				'VoraAdminController_chart_morris' => [
					'vendor/raphael/raphael.min.js',
					'vendor/morris/morris.min.js',
					'js/plugins-init/morris-init.js',
				],
				'VoraAdminController_chart_peity' => [
					'vendor/peity/jquery.peity.min.js',
					'js/plugins-init/piety-init.js',

				],
				'VoraAdminController_chart_sparkline' => [
					'vendor/jquery-sparkline/jquery.sparkline.min.js',
					'js/plugins-init/sparkline-init.js',
				],
				'VoraAdminController_ecom_checkout' => [
					'vendor/highlightjs/highlight.pack.min.js',
				],
				'VoraAdminController_ecom_customers' => [
					'vendor/highlightjs/highlight.pack.min.js',
				],
				'VoraAdminController_ecom_invoice' => [
					'vendor/highlightjs/highlight.pack.min.js',
				],
				'VoraAdminController_ecom_product_detail' => [
					'vendor/highlightjs/highlight.pack.min.js',
					'vendor/star-rating/jquery.star-rating-svg.js',
				],
				'VoraAdminController_ecom_product_grid' => [
					'vendor/highlightjs/highlight.pack.min.js',
				],
				'VoraAdminController_ecom_product_list' => [
					'vendor/highlightjs/highlight.pack.min.js',
					'vendor/star-rating/jquery.star-rating-svg.js',
				],
				'VoraAdminController_ecom_product_order' => [
					'vendor/highlightjs/highlight.pack.min.js',
				],
				'VoraAdminController_email_compose' => [
					'vendor/dropzone/dist/dropzone.js',
				],
				'VoraAdminController_email_inbox' => [
				],
				'VoraAdminController_email_read' => [
				],
				'VoraAdminController_form_ckeditor' => [
					'vendor/ckeditor/ckeditor.js',
				],
				'VoraAdminController_form_element' => [
				],
				'VoraAdminController_form_pickers' => [
					'vendor/moment/moment.min.js',
					'vendor/bootstrap-daterangepicker/daterangepicker.js',
					'vendor/clockpicker/js/bootstrap-clockpicker.min.js',
					'vendor/jquery-asColor/jquery-asColor.min.js',
					'vendor/jquery-asGradient/jquery-asGradient.min.js',
					'vendor/jquery-asColorPicker/js/jquery-asColorPicker.min.js',
					'vendor/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js',
					'vendor/pickadate/picker.js',
					'vendor/pickadate/picker.time.js',
					'vendor/pickadate/picker.date.js',
					'js/plugins-init/bs-daterange-picker-init.js',
					'js/plugins-init/clock-picker-init.js',
					'js/plugins-init/jquery-asColorPicker.init.js',
					'js/plugins-init/material-date-picker-init.js',
					'js/plugins-init/pickadate-init.js',
				],
				'VoraAdminController_form_validation_jquery' => [
					'vendor/jquery-validation/jquery.validate.min.js',
					'js/plugins-init/jquery.validate-init.js',
				],
				'VoraAdminController_form_wizard' => [
					'vendor/jquery-validation/jquery.validate.min.js',
					'js/plugins-init/jquery.validate-init.js',
					'vendor/jquery-smartwizard/dist/js/jquery.smartWizard.js',
				],
				'VoraAdminController_map_jqvmap' => [
					'vendor/jqvmap/js/jquery.vmap.min.js',
					'vendor/jqvmap/js/jquery.vmap.world.js',
					'vendor/jqvmap/js/jquery.vmap.usa.js',
					'js/plugins-init/jqvmap-init.js',
				],
				'VoraAdminController_page_error_400' => [
				],
				'VoraAdminController_page_error_403' => [
				],
				'VoraAdminController_page_error_404' => [
				],
				'VoraAdminController_page_error_500' => [
				],
				'VoraAdminController_page_error_503' => [
				],
				'VoraAdminController_page_forgot_password' => [
				],
				'VoraAdminController_page_lock_screen' => [
					'vendor/dlabnav/dlabnav.min.js',
				],
				'VoraAdminController_page_login' => [
				],
				'VoraAdminController_page_register' => [
				],
				'VoraAdminController_table_bootstrap_basic' => [
				],
				'VoraAdminController_table_datatable_basic' => [
					'vendor/datatables/js/jquery.dataTables.min.js',
					'js/plugins-init/datatables.init.js',
				],
				'VoraAdminController_uc_lightgallery' => [
					'vendor/lightgallery/js/lightgallery-all.min.js',
				],
				'VoraAdminController_uc_nestable' => [
					'vendor/nestable2/js/jquery.nestable.min.js',
					'js/plugins-init/nestable-init.js',
				],
				'VoraAdminController_uc_noui_slider' => [
					'vendor/nouislider/nouislider.min.js',
					'vendor/wnumb/wNumb.js',
					'js/plugins-init/nouislider-init.js',
				],
				'VoraAdminController_uc_select2' => [
					'vendor/select2/js/select2.full.min.js',
					'js/plugins-init/select2-init.js',
				],
				'VoraAdminController_uc_sweetalert' => [
					'vendor/sweetalert2/dist/sweetalert2.min.js',
					'js/plugins-init/sweetalert.init.js',
				],
				'VoraAdminController_uc_toastr' => [
					'vendor/toastr/js/toastr.min.js',
					'js/plugins-init/toastr-init.js',
				],
				'VoraAdminController_ui_accordion' => [
				],
				'VoraAdminController_ui_alert' => [
				],
				'VoraAdminController_ui_badge' => [
				],
				'VoraAdminController_ui_button' => [
				],
				'VoraAdminController_ui_button_group' => [
				],
				'VoraAdminController_ui_card' => [
				],
				'VoraAdminController_ui_carousel' => [
				],
				'VoraAdminController_ui_dropdown' => [
				],
				'VoraAdminController_ui_grid' => [
				],
				'VoraAdminController_ui_list_group' => [
				],
				'VoraAdminController_ui_media_object' => [
				],
				'VoraAdminController_ui_modal' => [
				],
				'VoraAdminController_ui_pagination' => [
				],
				'VoraAdminController_ui_popover' => [
				],
				'VoraAdminController_ui_progressbar' => [
				],
				'VoraAdminController_ui_tab' => [
				],
				'VoraAdminController_ui_typography' => [
				],
				'VoraAdminController_widget_basic' => [
					'vendor/chartist/js/chartist.min.js',
					'vendor/chartist-plugin-tooltips/js/chartist-plugin-tooltip.min.js',
					'vendor/flot/jquery.flot.js',
					'vendor/flot/jquery.flot.pie.js',
					'vendor/flot/jquery.flot.resize.js',
					'vendor/flot-spline/jquery.flot.spline.min.js',
					'vendor/jquery-sparkline/jquery.sparkline.min.js',
					'js/plugins-init/sparkline-init.js',
					'vendor/peity/jquery.peity.min.js',
					'js/plugins-init/piety-init.js',
					'vendor/chart.js/Chart.bundle.min.js',
					'js/plugins-init/widgets-script-init.js',
				]

			]
		],
	]
];
