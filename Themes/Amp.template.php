<?php

function template_html_above(){

	global $scripturl, $context, $boardurl;

	echo '<!doctype html>
<html amp lang="en">
	<head>
		<meta charset="' . (empty($context['character_set']) ? 'ISO-8859-1' : strtolower($context['character_set'])).'">
		<script async src="https://cdn.ampproject.org/v0.js"></script>
		<title>', $context['page_title'], '</title>
		<link rel="canonical" href="', $context['canonical'], '" />
		<meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">
		<script type="application/ld+json">{
			"@context": "http://schema.org",
			"@type": "Article",
			"mainEntityOfPage":{"@type":"WebPage","@id":"', $context['canonical'], '"},
			"headline": "', $context['page_title'], '",
			"datePublished": "', $context['posted'], '",
			"dateModified":"', $context['modified'], '",
			"image":{"@type": "ImageObject","url": "'.$context['rel_img'].'","width": "auto","height": "auto"},
			"author":{"@type":"Person","name":"'.$context['amp']['poster_name'].'"},
			"publisher":{"@type": "Organization","name":"SI Community","logo":{"@type":"ImageObject","url":"'.$boardurl.'/img/viral/site_thumb.png","width":"auto","height":"auto"}}
		}</script>
		<script async custom-template="amp-mustache" src="https://cdn.ampproject.org/v0/amp-mustache-0.1.js"></script>
		<style amp-custom>.logo {background-color:#28D;font-variant:small-caps;color:#ffc;text-align:left;padding:6px;box-shadow:0 1px 2px rgba(10,10,10,0.4)}.logo > a {color:#DEF;font:1.1em trebuchet,Helvetica,sans-serif;font-weight: bold;text-decoration:none}.logo amp-img{float:left}.heading {padding:5px;margin-top:0.83em}.who, .who a{color:#999;text-decoration:none}.sep{border:none;height:1px;background-color:#aaa;width:100px;}h3 a{text-decoration:none;color:#000;}.ampbutton{border: 0;background-color:#28D;font-weight:bold;color:#FFF;cursor:pointer;padding: 5px 10px;font-size:large}.ampbutton:hover{background-color:#4AF}blockquote,body,dd,dl,figure,hr,html,ol,pre{margin:0;padding:0}blockquote>:last-child{margin-bottom:0}body,html{font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif;overflow-x:hidden}img{border:0;max-width:100%}b,strong{font-weight:600}h1,h2,h3,h4,h5{color:#0379C4;font-weight:300;margin-top:1.1em;margin-bottom:.5em}h1{font-size:43px}h2{font-size:36px}h3{font-size:26px}a{color:#0379C4;text-decoration:none}a:hover{text-decoration:underline}a.button{padding:1em;display:block;text-align:center}blockquote{color:#797c82;border-left:4px solid #f5f5f5;padding-left:15px;font-size:18px;letter-spacing:-1px;font-style:italic}ol li,ul li{color:#424242;font-size:16px;font-weight:300;line-height:24px}@media only screen and (max-width:956px){ol li,ul li{font-size:16px;line-height:28px}}code,kbd,pre,samp,tt{font-family:Menlo,Monaco,Consolas,"Courier New",monospace;font-size:.9em;background:#f2f9ff;border:1px solid #b2e7ff;border-radius:3px;color:#222}code{font-size:13px;padding:2px 5px;display:block}a code{color:#035386;white-space:nowrap;border-bottom-width:2px;border-color:#29aae3}a:hover code{color:#fff;background-color:#29aae3}h1 code,h2 code,h3 code,h4 code,h5 code{font-size:.9em}pre{overflow:auto;-moz-tab-size:2;-o-tab-size:2;tab-size:2;word-break:normal;line-height:1.4;padding:1em}pre>code{white-space:pre;word-wrap:initial;display:block}pre>code td{border:0}table{border-collapse:collapse;width:100%}td,th{border:1px solid #FFF;background:#F7F7F7;padding:.5rem;text-align:left;color:#424242;font-size:16px;font-weight:300;line-height:24px;vertical-align:top}@media only screen and (max-width:956px){td,th{font-size:16px;line-height:28px}}th{background:#0379C4;color:#fff;font-weight:400}</style>
		<style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style><noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>
	</head>
	<body>';
}

function template_html_below() {
	echo '
	</body>
</html>';
}

function template_AmpTopic(){

	global $context, $scripturl, $boardurl;

	echo '
		<div class="logo">
			<a href="'.$scripturl.'"><amp-img src="'.$boardurl.'/img/viral/site_thumb.svg" alt="SI Community" title="SI Community" height="20px" width="20px"></amp-img></a>
			&nbsp;&nbsp;<a href="'.$scripturl.'?board=' . $context['amp']['id_board'].'">' . $context['amp']['board_name'] . '</a>
		</div>
		'.($context['head_img'] ? '<a href="'.$scripturl.'?topic='.$context['amp']['id_topic'].';topicseen#new"><amp-img width="600" height="500" layout="responsive" src="'.$context['rel_img'].'"></amp-img></a>' : '').'
		<div class="heading">
			<center>
				<a href="'.$scripturl.'"><amp-img src="'.$boardurl.'/img/viral/site_thumb50.png" alt="SI Community" title="SI Community" height="50px" width="50px"></amp-img></a>
				<h3><a href="'.$scripturl.'?topic='.$context['amp']['id_topic'].'">'.$context['page_title'].'</a></h3>
				<hr class="sep">
				<span>
					<b>SI Community</b><br>
					<small class="who">
						<a href="'.$scripturl.'?action=profile;u=' . $context['amp']['id_member'].'">' . $context['amp']['poster_name'] . '</a> • 
						'.date('M j, Y', $context['amp']['poster_time']).'
					</small>
				</span>
			</center>
		</div>
		<div class="heading">
			'.$context['amp']['body'].'
			<center>
				<div class="amp-ad-container">
					<amp-ad width=300 height=250 type="adsense" data-ad-client="ca-pub-3272970468196826" data-ad-slot="8230679555"></amp-ad>
				</div>
			</center>
			<p><a href="'.$scripturl.'?topic='.$context['amp']['id_topic'].'"><center><button class="ampbutton">Read More</button></center></a></p>
		</div>';
}
?>
