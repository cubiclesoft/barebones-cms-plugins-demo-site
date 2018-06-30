<?php
	// Example Barebones CMS PHP SDK usage for retrieving assets.
	// This file is part of a dynamically generated package produced by demo.barebonescms.com.

	require_once "config.php";

	require_once "support/sdk_barebones_cms_api.php";

	$cms = new BarebonesCMS();
	$cms->SetAccessInfo($config["api_host"], $config["api_readonly_key"]);
//	$cms->SetAccessInfo($config["api_host"], $config["api_readwrite_key"], $config["api_readwrite_secret"]);
//	$cms->SetDebug(true);

	// Find the most recently published assets.
	$options = array(
		"start" => 1,
		"end" => time()
	);

	$result = $cms->GetAssets($options, 50);
	if (!$result["success"])
	{
		echo "Failed to load assets.  Error:  " . $result["error"] . " (" . $result["errorcode"] . ")\n";
		var_dump($result["info"]);

		exit();
	}

	$assets = $result["assets"];
	echo "Retrieved assets:  " . count($assets) . "\n\n";

	foreach ($assets as $asset)
	{
		$asset = $cms->NormalizeAsset($asset);

		$lang = $cms->GetPreferredAssetLanguage($asset, "", "en-us");
		echo $asset["id"] . " | " . $asset["uuid"] . " | " . $asset["langinfo"][$lang]["title"] . "\n";
	}

	echo "Done.\n";
?>