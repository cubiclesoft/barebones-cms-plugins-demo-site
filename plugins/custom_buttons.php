<?php
	// Barebones CMS demo site plugin.
	// Add a custom template to the Embed dialog.
	// (C) 2023 CubicleSoft.  All Rights Reserved.

	class Plugin_AddEditAsset_Story_CustomButtonsTemplate
	{
		public static function Display()
		{
?>
ContentTools.EMBED_TEMPLATES.push({
	'name': 'Big buttons',
	'fields': [
		{ 'title': 'Button 1 text', 'type': 'text', 'name': 'b1_text', 'default': '', 'required': true },
		{ 'title': 'Button 1 URL', 'type': 'text', 'name': 'b1_url', 'default': '', 'required': true },
		{ 'title': 'Button 2 text', 'type': 'text', 'name': 'b2_text', 'default': '' },
		{ 'title': 'Button 2 URL', 'type': 'text', 'name': 'b2_url', 'default': '' },
		{ 'title': 'Button 3 text', 'type': 'text', 'name': 'b3_text', 'default': '' },
		{ 'title': 'Button 3 URL', 'type': 'text', 'name': 'b3_url', 'default': '' },
		{ 'title': 'Visible', 'type': 'switch', 'name': 'visible', 'default': true }
	],
	'content': function(fieldmap) {
		var html = '<div class="buttonswrap"' + (fieldmap['visible'] ? '' : ' style="display: none;"') + '>\n';

		for (var x = 1; x <= 3; x++)
		{
			if (fieldmap['b' + x + '_url'].value != '' && fieldmap['b' + x + '_text'].value != '')  html += '<a href="' + fieldmap['b' + x + '_url'].value + '">' + fieldmap['b' + x + '_text'].value + '</a>\n';
		}

		html += '</div>';

		return html;
	}
});
<?php
		}
	}

	if (class_exists("EventManager", false) && isset($em))
	{
		$em->Register("addeditasset_story_pre_editor", "Plugin_AddEditAsset_Story_CustomButtonsTemplate::Display");
	}
?>