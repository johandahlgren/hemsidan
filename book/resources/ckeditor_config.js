/*
Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	config.toolbar = "custom";
	config.toolbar_custom =
	[
		['Font','FontSize','Bold','Italic','Underline','Strike','TextColor'],
		['NumberedList','BulletedList','Image','SpecialChar','PageBreak'],
		['Source'],
		['Find'],
		['Save']
	];
	config.ProcessHTMLEntities = false;
	config.skin = "kama";
};
