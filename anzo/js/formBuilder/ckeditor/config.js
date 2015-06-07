/*
Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	
	config.toolbar = [
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline'] },
		{ name: 'links', items: [ 'Link', 'Unlink'] },
		{ name: 'insert', items: [ 'Image', 'Table', 'HorizontalRule' ] },
		{ name: 'styles', items: [ 'Format', 'Font', 'FontSize' ] },
		{ name: 'colors', items: [ 'TextColor'] },
		{ name: 'tools', items: ['ShowBlocks' ] },
		{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'JustifyLeft', 'JustifyCenter', '-', 'JustifyRight', 'JustifyBlock'] },
		{ name: 'document', items : [ 'Source' ] },
	];
	
	config.toolbarCanCollapse = true;
	config.toolbarStartupExpanded = false;
	config.toolbarLocation = 'top';
	config.resize_enabled = true;
	config.removePlugins = 'elementspath';
	
	config.resize_minHeight = 70;
	config.resize_minWidth = 70;
    //config.autogrow_maxHeight = 0;
    //config.autoGrow_minHeight = 700;

	
	config.sharedSpaces =
	{
		top : 'inlineToolbar',
		//bottom:'resizeAndElementPath'
	};
};
