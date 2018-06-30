<?php
	// Barebones CMS demo site plugin.
	// Add SDK information to the home screen and session expiration tracking across the admin interface.
	// (C) 2018 CubicleSoft.  All Rights Reserved.

	class Plugin_BarebonesCMSDemo
	{
		public static function Loaded()
		{
			global $bb_layouthead, $config;

			$startts = filemtime($config["rootpath"] . "/..");
			$endts = $startts + 30 * 60;
			$left = $endts - time();

			ob_start();
?>
<script type="text/javascript">
$(function() {
	// Calculate the end timestamp.
	var endts = Math.floor(Date.now() / 1000) + <?php echo $left; ?>;

	var UpdateSessionInfo = function() {
		var ts = Math.floor(Date.now() / 1000);
		var elem = document.getElementById('demo-timeleft');

		if (ts < endts)
		{
			if (elem)
			{
				var diff = endts - ts;
				var min = Math.floor(diff / 60);
				var sec = diff % 60;

				if (min < 5)  elem.innerHTML = 'Session time left:  ' + min + ' min, ' + sec + ' sec';
				else if (elem.innerHTML !== 'Session time left:  ' + min + ' min')  elem.innerHTML = 'Session time left:  ' + min + ' min';
			}
		}
		else
		{
			clearInterval(expiretrack);

			if (elem)  elem.innerHTML = 'Session has expired.';
			alert('Session has expired.');

			window.location.href = 'https://demo.barebonescms.com/?expired=1&ts=' + ts;
		}
	};

	var expiretrack = setInterval(UpdateSessionInfo, 1000);

	UpdateSessionInfo();
});
</script>
<?php
			$bb_layouthead .= ob_get_contents();
			ob_end_clean();
		}

		public static function AllContentOpts()
		{
			$params = func_get_args();
			if (strrpos($params[0], "_contentopts") !== false)
			{
				global $contentopts;

				if (isset($contentopts) && is_array($contentopts))
				{
					if (!isset($contentopts["htmldesc"]))  $contentopts["htmldesc"] = "";

					ob_start();
?>
<div id="demo-timeleft"></div>
<?php
					$contentopts["htmldesc"] .= ob_get_contents();
					ob_end_clean();
				}
			}
		}

		public static function FallbackDisplay(&$contentopts)
		{
			$rootpath = $GLOBALS["config"]["rootpath"];

			if (!file_exists($rootpath . "/../api/config.php"))  return;
			require_once $rootpath . "/../api/config.php";

			if (!isset($contentopts["fields"]))  $contentopts["fields"] = array();

			$rooturl = str_replace("http://", "https://", $config["rooturl"]);
			$custom = "<b>Host:</b> " . htmlspecialchars($rooturl) . "<br>\n";
			$custom .= "<b>Read only API key:</b> " . htmlspecialchars($config["read_apikey"]) . "<br>\n";
			$custom .= "<b>Read/write API key:</b> " . htmlspecialchars($config["readwrite_apikey"]) . "<br>\n";
			$custom .= "<b>Read/write secret:</b> " . htmlspecialchars($config["readwrite_secret"]) . "<br>\n";

			$contentopts["fields"][] = array(
				"title" => "API Access",
				"type" => "custom",
				"value" => "<div class=\"staticwrap\">" . $custom . "</div>",
				"htmldesc" => "<a href=\"../demo_php_sdk_example.zip\">Download prepared PHP SDK</a> | <a href=\"https://github.com/cubiclesoft/barebones-cms/blob/master/docs/sdk.md\" target=\"_blank\">SDK documentation</a>"
			);

			$contentopts["fields"][] = array(
				"title" => "cURL Example",
				"type" => "custom",
				"value" => "<div class=\"staticwrap\">curl -H 'X-APIKey: " . htmlspecialchars($config["read_apikey"]) . "' '" . htmlspecialchars($rooturl) . "?ver=1&api=assets&start=1&end=" . time() . "&limit=50'</div>",
				"htmldesc" => "Copy and paste into a terminal.  Requires cURL to be installed."
			);

			if ($config["db_select"] === "sqlite")
			{
				$contentopts["fields"][] = array(
					"title" => "SQLite Database",
					"type" => "custom",
					"value" => "<div class=\"staticwrap\"><a href=\"" . htmlspecialchars($rooturl . substr($config["db_dsn"], strrpos($config["db_dsn"], "/db/"))) . "\">Download</a></div>"
				);
			}
		}
	}

	// Register to receive callbacks for the various events.
	if (class_exists("EventManager", false) && isset($em))
	{
		$em->Register("plugins_loaded", "Plugin_BarebonesCMSDemo::Loaded");
		$em->Register("", "Plugin_BarebonesCMSDemo::AllContentOpts");
		$em->Register("fallback_contentopts", "Plugin_BarebonesCMSDemo::FallbackDisplay");
	}
?>