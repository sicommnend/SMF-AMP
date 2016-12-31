<?php

if (!defined('SMF'))
	die('Hacking attempt...');

function AmpMain() {
	if (isset($_REQUEST['t']))
		AmpTopic($_REQUEST['t']);
	elseif (isset($_REQUEST['m']))
		AmpMedia($_REQUEST['m']);
	else
		si404();
}

function AmpTopic($id) {

	global $context, $smcFunc, $scripturl, $boardurl, $modSettings;

	loadTemplate('Amp');
	$context['template_layers'] = array('html');
	$context['sub_template'] = 'AmpTopic';
	$modSettings['disableQueryCheck'] = 1;
	$request = $smcFunc['db_query']('', '
		SELECT
			m.poster_time, m.subject, m.id_topic, m.id_member, m.id_msg, b.id_board, b.name AS board_name,
			IFNULL(mem.real_name, m.poster_name) AS poster_name, m.body, b.thumb_board as thumb, m.modified_time,
			(SELECT id_attach FROM {db_prefix}attachments WHERE id_thumb != 0 AND {db_prefix}attachments.id_msg = m.id_msg ORDER BY id_attach LIMIT 1) AS id_attach
		FROM {db_prefix}topics AS t
			INNER JOIN {db_prefix}messages AS m ON (m.id_msg = t.id_first_msg)
			INNER JOIN {db_prefix}boards AS b ON (b.id_board = t.id_board)
			LEFT JOIN {db_prefix}members AS mem ON (mem.id_member = m.id_member)
		WHERE {query_wanna_see_board} AND t.id_topic = {int:id}
		LIMIT 1' ,
		array(
			'id' => (int) $id
		)
	);
	$context['amp'] = $smcFunc['db_fetch_assoc']($request);

	if ($context['amp']) {
		$context['amp']['subject'] = censorText($context['amp']['subject']);
		$context['page_title'] = $context['amp']['subject'];
		$context['canonical'] = $scripturl . '?topic=' . $context['amp']['id_topic'];
		$context['posted'] = date('Y-m-d\TH:i:s', $context['amp']['poster_time']);
		$context['modified'] = date('Y-m-d\TH:i:s', empty($context['amp']['modified_time']) ? $context['amp']['poster_time'] : $context['amp']['modified_time']);
		$context['head_img'] = false;
		if (preg_match('[smg id=([0-9]+)(.*)]', $context['amp']['body'], $match))
			$context['head_img'] = $context['rel_img'] = $scripturl.'?action=media;sa=media;in='.$match[1].';preview';
		elseif (preg_match('#\[img(.*)\](.*)\[\/img\]#Ui', $context['amp']['body'], $match))
			$context['head_img'] = $context['rel_img'] = $match[2];
		elseif (isset($context['amp']['id_attach']) && $context['amp']['id_attach'] != 0)
			$context['head_img'] = $context['rel_img'] = $scripturl . '?action=dlattach;topic='.$context['amp']['id_topic'].';attach='.$context['amp']['id_attach'].';image';
		elseif (isset($context['amp']['thumb']) && $context['amp']['thumb'] != '')
			$context['rel_img'] = $boardurl.'/img/b/sm/'.$context['amp']['thumb'];
		else
			$context['rel_img'] = $boardurl.'/img/viral/site_thumb.png';

		$context['amp']['body'] = amp_rewrite(parse_bbc($context['amp']['body']));
	} else
		si404();

	// Add 1 to the number of views of this topic.
	if (empty($_SESSION['last_read_topic']) || $_SESSION['last_read_topic'] != $id) {
		$smcFunc['db_query']('', '
			UPDATE {db_prefix}topics
			SET num_views = num_views + 1
			WHERE id_topic = {int:current_topic}',
			array(
				'current_topic' => (int) $id,
			)
		);
		$_SESSION['last_read_topic'] = $id;
	}
}

function amp_rewrite($data) {

	/*
	 * This is resource intensive, especially since we parse the BCC and
	 * now we are doing more just to make sure the markup is up to AMP standard.
	 * Good thing it is just one post.
	 */

	// This does happen, empty media descriptions, so we return a empty string.
	if (empty($data))
		return('');

	// Lets use the DOM first, the server will thank us for it.
	libxml_use_internal_errors(true);
	$DOM = new DOMDocument();
	$DOM->loadHTML('<?xml encoding="utf-8" ?>'.$data);
	$XPath = new DOMXPath($DOM);

	// Got class but no style, no borders no limits :P
	foreach(array('style','border') as $attribute)
		foreach($XPath->query('//*[@'.$attribute.']') as $item)
			$item->removeAttribute($attribute);

	// Unallowed stuff, button is allowed but figured it looked ugly with it and without the other stuff
	foreach(array('script','frame','frameset','object','param','applet','embed','form','input','textarea','select','option','button','iframe','video','audio') as $item)
		while($node = $XPath->query('//'.$item)->item(0))
			$node->parentNode->removeChild($node);

	// Put the stuff back.
	$data = $DOM->saveHTML($XPath->query('//body')->item(0));
	foreach(array('body','/body') as $item)
		$data = str_replace('<'.$item.'>', '', $data);
	libxml_clear_errors();

	// Remove the code BBC select JavaScript.
	$data = preg_replace('~<a .*?href="javascript.*?>.*?</a>~si', '', $data);

	// Other stuff below, <amp- is not valid HTML, that is why we can't do it in the DOM. :(

	//Some stuff needs to be done one at a time, borrowed from SMF.
	$pos = -1;
	while ($pos !== false){
		$last_pos = isset($last_pos) ? max($pos, $last_pos) : $pos;
		$pos = strpos($data, '<', $pos + 1);
		if ($pos === false || $last_pos > $pos)
			$pos = strlen($data) + 1;
		if ($last_pos < $pos - 1){
			$last_pos = max($last_pos, 0);
			$str = substr($data, $last_pos, $pos - $last_pos);

			// Image is *
			$str = preg_replace('~<img (.*?)class="(emoji|smiley)\b.*?>~si', '<amp-img width="30" height="30" $1></amp-img>', $str);
			$str = preg_replace('~<img (.*?)>~si', '<center><amp-img width="300" height="250" $1></amp-img></center>', $str);

			if ($str != substr($data, $last_pos, $pos - $last_pos)){
				$data = substr($data, 0, $last_pos) . $str . substr($data, $pos);
				$old_pos = strlen($data) + $last_pos;
				$pos = strpos($data, '<', $last_pos);
				$pos = $pos === false ? $old_pos : min($pos, $old_pos);
			}
		}
		if ($pos >= strlen($data) - 1)
			break;
	}

	return $data;
}
?>
