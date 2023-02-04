<?php
	// Barebones CMS demo site plugin.
	// Add a custom CSS class to the ContentTools editor.
	// (C) 2023 CubicleSoft.  All Rights Reserved.

	class Plugin_CustomCSSClasses
	{
		public static function OutputCSS()
		{
?>
<style type="text/css">
p.detour { border-left: 3px solid #0093EC; padding: 0.3em 0 0.3em 1.5em; color: #666666; font-style: italic; }
</style>
<?php
		}

		public static function PreEditor()
		{
?>
ContentTools.StylePalette.add([
	new ContentTools.Style('Mental detour', 'detour', ['p'])
]);
<?php
		}
	}

	// Register to receive callbacks for the various events.
	if (class_exists("EventManager", false) && isset($em))
	{
		$em->Register("addeditasset_story_css", "Plugin_CustomCSSClasses::OutputCSS");
		$em->Register("addeditasset_story_pre_editor", "Plugin_CustomCSSClasses::PreEditor");
	}
?>