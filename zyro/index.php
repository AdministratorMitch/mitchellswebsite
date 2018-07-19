<?php
	error_reporting(E_ALL); @ini_set('display_errors', true);
	@session_start();
	$tz = @date_default_timezone_get(); @date_default_timezone_set($tz ? $tz : 'UTC');
	require_once dirname(__FILE__).'/polyfill.php';
	$pages = array(
		'0'	=> array('id' => '1', 'alias' => '', 'file' => '1.php','controllers' => array()),
		'1'	=> array('id' => '2', 'alias' => 'products', 'file' => '2.php','controllers' => array()),
		'2'	=> array('id' => '3', 'alias' => 'contactme', 'file' => '3.php','controllers' => array())
	);
	$forms = array(
		'3'	=> array(
			'b6af36de' => Array( 'email' => 'webmaster@mitchellswebsite.com', 'emailFrom' => 'webserver@mitchellswebsite.com', 'subject' => 'MitchellsWebsite.com - Contact Me ', 'sentMessage' => unserialize('s:54:"Your message was sent successfully! Thank you Mitchell";'), 'object' => '', 'objectRenderer' => '', 'loggingHandler' => '', 'smtpEnable' => false, 'smtpHost' => null, 'smtpPort' => null, 'smtpEncryption' => null, 'smtpUsername' => null, 'smtpPassword' => null, 'recSiteKey' => null, 'recSecretKey' => null, 'maxFileSizeTotal' => '2', 'fields' => array( array( 'fidx' => '0', 'name' => 'Your Name:', 'type' => 'input', 'required' => 1, 'options' => '' ), array( 'fidx' => '1', 'name' => 'Your E-mail:', 'type' => 'input', 'required' => 1, 'options' => '' ), array( 'fidx' => '2', 'name' => 'Subject:', 'type' => 'input', 'required' => 1, 'options' => '' ), array( 'fidx' => '3', 'name' => 'How did you find me?', 'type' => 'select', 'required' => 1, 'options' => 'Found on google;Followed link from other site;Friend told me' ), array( 'fidx' => '4', 'name' => 'Are you a business owner?', 'type' => 'select', 'required' => 1, 'options' => 'Yes;No;May be' ), array( 'fidx' => '5', 'name' => 'Message', 'type' => 'textarea', 'required' => 1, 'options' => '' ), array( 'fidx' => '6', 'name' => 'Attachments', 'type' => 'file', 'required' => 0, 'options' => '' ) ) )
		)
	);
	$langs = null;
	$def_lang = null;
	$base_lang = 'en';
	$site_id = "4085319c";
	$base_dir = dirname(__FILE__);
	$base_url = '/';
	$user_domain = 'mitchellswebsite.com';
	$home_page = '1';
	$mod_rewrite = true;
	$show_comments = false;
	$comment_callback = "http://uk.zyro.com/comment_callback/";
	$user_key = "pzWgzFtBdC6NyL0F/DZnP1YdJ4c=";
	$user_hash = "5708334d6014648d";
	$ga_code = (is_file($ga_code_file = dirname(__FILE__).'/ga_code') ? file_get_contents($ga_code_file) : null);
	require_once dirname(__FILE__).'/src/SiteInfo.php';
	require_once dirname(__FILE__).'/src/SiteModule.php';
	require_once dirname(__FILE__).'/functions.inc.php';
	$siteInfo = SiteInfo::build(array('siteId' => $site_id, 'domain' => $user_domain, 'homePageId' => $home_page, 'baseDir' => $base_dir, 'baseUrl' => $base_url, 'defLang' => $def_lang, 'baseLang' => $base_lang, 'userKey' => $user_key, 'userHash' => $user_hash, 'commentsCallback' => $comment_callback, 'langs' => $langs, 'pages' => $pages, 'forms' => $forms, 'modRewrite' => $mod_rewrite, 'gaCode' => $ga_code, 'gaAnonymizeIp' => false, 'port' => null, 'pathPrefix' => null,));
	SiteModule::init(null, $siteInfo);
	list($page_id, $lang, $urlArgs, $route) = parse_uri($siteInfo);
	$preview = false;
	$requestInfo = SiteRequestInfo::build(array('page' => (isset($pages[$page_id]) ? $pages[$page_id] : null), 'lang' => $lang, 'urlArgs' => $urlArgs, 'route' => $route,));
	SiteModule::setLang($requestInfo->{'lang'});
	$hr_out = '';
	$page = $requestInfo->{'page'};
	if (!is_null($page)) {
		handleComments($page['id'], $siteInfo);
		if (isset($_POST["wb_form_id"])) handleForms($page['id'], $siteInfo);
	}
	ob_start();
	if ($page) {
		$fl = dirname(__FILE__).'/'.$page['file'];
		if (is_file($fl)) {
			ob_start();
			include $fl;
			$out = ob_get_clean();
			$ga_out = '';
			if ($lang && $langs) {
				foreach ($langs as $ln => $default) {
					$pageUri = getPageUri($page['id'], $ln, $siteInfo);
					$out = str_replace(urlencode('{{lang_'.$ln.'}}'), $pageUri, $out);
				}
			}
			if (is_file($ga_tpl = dirname(__FILE__).'/ga.php')) {
				ob_start(); include $ga_tpl; $ga_out = ob_get_clean();
			}
			$out = str_replace('<ga-code/>', $ga_out, $out);
			$out = str_replace('{{base_url}}', getBaseUrl(), $out);
			$out = str_replace('{{curr_url}}', getCurrUrl(), $out);
			$out = str_replace('{{hr_out}}', $hr_out, $out);
			header('Content-type: text/html; charset=utf-8', true);
			echo $out;
		}
	} else {
		header("Content-type: text/html; charset=utf-8", true, 404);
		if (is_file(dirname(__FILE__).'/404.html')) {
			include '404.html';
		} else {
			echo "<!DOCTYPE html>\n";
			echo "<html>\n";
			echo "<head>\n";
			echo "<title>404 Not found</title>\n";
			echo "</head>\n";
			echo "<body>\n";
			echo "404 Not found\n";
			echo "</body>\n";
			echo "</html>";
		}
	}
	ob_end_flush();

?>