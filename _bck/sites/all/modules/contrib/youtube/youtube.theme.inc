<?php
/**
 * @file
 * Theme functions for the YouTube field module.
 */

/**
 * Theme function for videos.
 */
function theme_youtube_video($variables) {
  $video_id = $variables['video_id'];

  // Get field display settings.
  $size = $variables['size'];
  $width = array_key_exists('width', $variables) ? $variables['width'] : NULL;
  $height = array_key_exists('height', $variables) ? $variables['height'] : NULL;
  $autoplay = array_key_exists('autoplay', $variables) ? $variables['autoplay'] : FALSE;
  $loop = array_key_exists('loop', $variables) ? $variables['loop'] : FALSE;
  $showinfo = array_key_exists('showinfo', $variables) ? $variables['showinfo'] : FALSE;
  $controls = array_key_exists('controls', $variables) ? $variables['controls'] : FALSE;
  $autohide = array_key_exists('autohide', $variables) ? $variables['autohide'] : FALSE;
  $iv_load_policy = array_key_exists('iv_load_policy', $variables) ? $variables['iv_load_policy'] : FALSE;

  // Get YouTube settings.
  $suggest = variable_get('youtube_suggest', TRUE);
  $privacy = variable_get('youtube_privacy', FALSE);
  $modestbranding = variable_get('youtube_modestbranding', FALSE);
  $theme = variable_get('youtube_theme', FALSE);
  $color = variable_get('youtube_color', FALSE);
  $enablejsapi = variable_get('youtube_enablejsapi', FALSE);
  $player_class = variable_get('youtube_player_class', 'youtube-field-player');
  $wmode = variable_get('youtube_wmode', TRUE);
  $privacy = variable_get('youtube_privacy', FALSE);
  $dimensions = youtube_get_dimensions($size, $width, $height);

  // Query string changes based on setings.
  $query = array();
  if (!$suggest) {
    $query['rel'] = '0';
  }
  if ($modestbranding) {
    $query['modestbranding'] = '1';
  }
  if ($theme) {
    $query['theme'] = 'light';
  }
  if ($color) {
    $query['color'] = 'white';
  }
  if ($enablejsapi) {
    global $base_root;
    $query['enablejsapi'] = '1';
    $query['origin'] = $base_root;
  }
  if ($wmode) {
    $query['wmode'] = 'opaque';
  }
  if ($autoplay) {
    $query['autoplay'] = '1';
  }
  if ($loop) {
    $query['loop'] = '1';
    $query['playlist'] = $video_id;
  }
  if ($showinfo) {
    $query['showinfo'] = '0';
  }
  if ($controls) {
    $query['controls'] = '0';
  }
  if ($autohide) {
    $query['autohide'] = '1';
  }
  if ($iv_load_policy) {
    $query['iv_load_policy'] = '3';
  }

  // Domain changes based on settings.
  $domain = ($privacy) ? 'youtube-nocookie.com' : 'youtube.com';

  $path = 'https://www.' . $domain . '/embed/' . $video_id;
  $src = url($path, array('query' => $query));

  $player_title = t('Embedded video');
  if (!empty($variables['entity_title'])) {
    $player_title .= t(' for @entity_title', array(
      '@entity_title' => $variables['entity_title'],
    ));
  }

  // Alternative content for browsers that don't understand iframes (WCAG).
  $alternative_content = l($player_title, $src);

  $output = '<iframe id="' . drupal_html_id($player_class) . '" class="' . $player_class . '"
    width="' . $dimensions['width'] . '" height="' . $dimensions['height'] . '"
    src="' . $src . '" title="' . $player_title . '" frameborder="0"
    allowfullscreen>' . $alternative_content . '</iframe>';

  if ($size == 'responsive') {
    $output = '<div class="youtube-container--responsive">' . $output . '</div>';
  }

  return $output;
}


/**
 * Theme function for thumbnails.
 */
function theme_youtube_thumbnail($variables) {
  $video_id = $variables['video_id'];
  $style = $variables['image_style'];
  $uri = youtube_build_thumbnail_uri($video_id);

  // Check to see if a thumbnail exists locally.
  if (!file_exists($uri)) {
    // Retrieve the image from YouTube.
    if (!youtube_get_remote_image($video_id)) {
      // Use the remote source if local copy fails.
      $src = youtube_build_remote_image_path($video_id);
      return theme('image', array('path' => $src));
    }
  }
  
  $alt = t('Embedded thumbnail');
  if (!empty($variables['entity_title'])) {
    $alt .= t(' for @entity_title', array(
      '@entity_title' => $variables['entity_title'],
    ));
  }

  if ($style) {
    $image = theme('image_style', array(
      'style_name' => $style,
      'path' => $uri,
      'alt' => $alt,
    ));
  }
  else {
    $image = theme('image', array(
      'path' => $uri,
      'alt' => $alt
    ));
  }

  // Check if an url path is provided
  if ($variables['image_link'] != NULL) {
    $url_path = $variables['image_link']['path'];
    $options = $variables['image_link']['options'];
    $image = l($image, $url_path, $options);
  }

  return $image;
}

/**
 * Theme function for thumbnails.
 */
function theme_youtube_lazy_thumbnail($variables) {

  drupal_add_js(drupal_get_path('module', 'youtube').'/lazy_youtube.js');
  drupal_add_css(drupal_get_path('module', 'youtube').'/css/lazy_youtube.css');

  $video_id = $variables['video_id'];

  $uri = youtube_build_thumbnail_uri($video_id);
  $use_youtube_thumb = FALSE;
  // Check to see if a thumbnail exists locally.
  if (!file_exists($uri)) {
    // Retrieve the image from YouTube.
    if (!youtube_get_remote_image($video_id)) {
      // Use the remote source if local copy fails.
      //$src = youtube_build_remote_image_path($video_id);
      $use_youtube_thumb = TRUE;
      //return theme('image', array('path' => $src));
    }
  }

  // Get field display settings.
  $size = $variables['size'];
  $width = array_key_exists('width', $variables) ? $variables['width'] : NULL;
  $height = array_key_exists('height', $variables) ? $variables['height'] : NULL;
  
  $dimensions = youtube_get_dimensions($size, $width, $height);
  $background_style = ""; $need_thumb = ' need-thumb="1" ';
  if (!$use_youtube_thumb) {
    $background_style = "background-image: url(".file_create_url($uri).");";
    $need_thumb = "";
  }

  return '<div class="youtube" id="'.$video_id.'" '.$need_thumb.' style="width: '.$dimensions['width'].'px; height: '.$dimensions['height'].'px; '.$background_style.
  '"></div>';


  // drupal_add_js(drupal_get_path('module', 'youtube').'/lazy_youtube.js');
  // drupal_add_css(drupal_get_path('module', 'youtube').'/css/lazy_youtube.css');

  $video_id = $variables['video_id'];

  $uri = youtube_build_thumbnail_uri($video_id);
  $use_youtube_thumb = FALSE;
  // Check to see if a thumbnail exists locally.
  if (!file_exists($uri)) {
    // Retrieve the image from YouTube.
    if (!youtube_get_remote_image($video_id)) {
      // Use the remote source if local copy fails.
      //$src = youtube_build_remote_image_path($video_id);
      $use_youtube_thumb = TRUE;
      //return theme('image', array('path' => $src));
    }
  }

  // Get field display settings.
  $size = $variables['size'];
  $width = array_key_exists('width', $variables) ? $variables['width'] : NULL;
  $height = array_key_exists('height', $variables) ? $variables['height'] : NULL;
  
  $dimensions = youtube_get_dimensions($size, $width, $height);
  $background_style = ""; $need_thumb = ' need-thumb="1" ';
  /*if (!$use_youtube_thumb) {
    $background_style = "background-image: url(".file_create_url($uri).");";
    $need_thumb = "";
  }*/
  $need_thumb = "";

  return '<a class="youtube fancybox.iframe" target="_blank" href="https://www.youtube.com/embed/'.$video_id.'?autoplay=1&autohide=1"><span class="play"></span></a>';

  /*return '<div class="youtube" id="'.$video_id.'" ><a class="youtube autodialog" href="https://www.youtube.com/embed/'.$video_id.'?autoplay=1&autohide=1"></a></div>';

  return '<div class="youtube" id="'.$video_id.'" '.$need_thumb.' style="width: '.$dimensions['width'].'px; height: '.$dimensions['height'].'px; '.$background_style.
  '"></div>';*/
}
