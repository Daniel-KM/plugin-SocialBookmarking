<?php

define('SOCIAL_BOOKMARKING_VERSION', 1.0);

add_plugin_hook('install', 'social_bookmarking_install');
add_plugin_hook('uninstall', 'social_bookmarking_uninstall');
add_plugin_hook('config', 'social_bookmarking_config');
add_plugin_hook('config_form', 'social_bookmarking_config_form');
add_plugin_hook('public_append_to_items_show', 'social_bookmarking_append_to_item');

function social_bookmarking_install() 
{
	$socialBookmarkingServices = array(
	'delicious' 		=> 	true,
	'digg' 				=> 	true,
	'furl' 				=> 	true,
	'blinklist'			=>	false,
	'reddit'			=> 	true,
	'feed_me'			=>	false,
	'technorati'		=>	true,
	'yahoo'				=>	true,
	'newsvine'			=>	true,
	'socializer'		=>	false,
	'magnolia'			=>	false,
	'stumbleupon'		=>	false,
	'google'			=>	false,
	'rawsugar'			=>	false,
	'squidoo'			=>	false,
	'blinkbits'			=>	false,
	'netvouz'			=>	false,
	'rojo'				=>	false,
	'blogmarks'			=>	false,
	'simpy'				=>	false,
	'comments'			=>	false,
	'scuttle'			=>	false,
	'bloglines'			=>	false,
	'tailrank'			=>	false,
	'scoopeo'			=>	false,
	'blogmemes'			=>	false,
	'blogspherenews'	=>	false,
	'blogsvine'			=>	false,
	'mixx'				=>	false,
	'netscape'			=>	false,
	'ask'				=>	false,
	'linkagogo'			=>	false,
	'delirious'			=>	false,
	'socialdust'		=>	false,
	'live'				=>	false,
	'slashdot'			=>	false,
	'sphinn'			=>	false,
	'facebook'			=>	false,
	'myspace'			=>	false,
	'connotea'			=>	false,
	'misterwong'		=>	false,
	'barrapunto'		=>	false,
	'twitter'			=>	false,
	'indianpad'			=>	false,
	'bluedot'			=>	false,
	'segnalo'			=>	false,
	'oknotizie'			=>	false,
	'diggita'			=>	false,
	'seotribu'			=>	false,
	'upnews'			=>	false,
	'wikio'				=>	false,
	'notizieflash'		=>	false,
	'kipapa'			=>	false,
	'fai_informazione'	=>	false,
	'bookmark_it'		=>	false,
	'ziczac'			=>	false,
	'plim'				=>	false,
	'technotizie'		=>	false,
	'diggitsport'		=>	false
	);
	
	set_option('social_bookmarking_version', SOCIAL_BOOKMARKING_VERSION);
	set_option('social_bookmarking_services', serialize($socialBookmarkingServices));	
}

function social_bookmarking_uninstall()
{
	delete_option('social_bookmarking_version');
	delete_option('social_bookmarking_services');
}

function social_bookmarking_config() 
{
	$socialBookmarkingServices = social_bookmarking_get_services();
	
	unset($_POST['install_plugin']);
		
	$foo = serialize($_POST);
	
	set_option('social_bookmarking_services', $foo);
}

function social_bookmarking_config_form() 
{
    include 'config_form.php';
}

function social_bookmarking_append_to_item()
{
    echo '<h2>Social Bookmarking</h2>';
    $socialBookmarkingServices = social_bookmarking_get_services();
	foreach ($socialBookmarkingServices as $service => $value) {
		if ($value == false) continue;
		$site = social_bookmarking_get_service_props($service);
		$targetHref = str_replace('{title}', item('Dublin Core', 'Title'), $site->url);
		$targetHref = str_replace('{link}', abs_item_uri(), $targetHref);
		
		$image = img($site->img);
		
        $serviceIcon = '<a class="social-img" href="'.$targetHref.'" title="'.$site['name'].'"><img src="'.$image.'" /></a>';
        echo $serviceIcon;
	}
}

function social_bookmarking_get_services() 
{
	$services = unserialize(get_option('social_bookmarking_services'));
	return $services;
}

function social_bookmarking_get_service_props($service)
{
    static $xml = null;
    if (!$xml) {
        $file = file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'services.xml');
        $xml = new SimpleXMLElement($file);
    }

    foreach ($xml->site as $site) {
        if ($site->key != $service) continue;
        return $site;
    }
}