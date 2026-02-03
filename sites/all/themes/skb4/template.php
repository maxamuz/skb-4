<?php

/**
 *
 */
function skb4_preprocess_page(&$variables) {

  $current_path = current_path();
  // dsm($current_path);
  if ($current_path == 'node/8609') {
    header("HTTP/1.0 404 Not Found");
    exit();
  }
  if ($current_path == 'node/8610') {
    header("HTTP/1.0 403 Forbidden");
    exit();
  }

	global $user, $language;
	
  $phone = theme_get_setting('skb4_phone', 'skb4');
  $variables['phone'] 	= '<a href="tel:'.preg_replace("/[ ()-]+/", "", $phone).'">'.$phone.'</a>';
  $phone_dop = theme_get_setting('skb4_phone_dop', 'skb4');
  if ($phone_dop !== '') {
  	$variables['phone_dop'] = '<a href="tel:'.preg_replace("/[ ()-]+/", "", $phone_dop).'">'.$phone_dop.'</a>';
  } else {
  	$variables['phone_dop'] = '';
  }
  
	
  $variables['address'] = theme_get_setting('skb4_address', 'skb4');
  $variables['work_time'] = theme_get_setting('skb4_work_time', 'skb4');
  $email = theme_get_setting('skb4_email', 'skb4');
  $variables['email'] = '<a href="mailto:'.$email.'">'.$email.'</a>';

  // $variables['logo'] = file_create_url(drupal_get_path('theme', 'skb4') . '/logo.svg');
  $variables['logo'] = file_create_url(drupal_get_path('theme', 'skb4') . '/logo.svg');

  //drupal_add_js('https://www.google.com/recaptcha/api.js', array('type' => 'external'));

  if(isset($variables['node'])) {
    $breadcrumb = array();
    switch ($variables['node']->type) {
      case 'news':
        $breadcrumb[] = l(t('Home'), '<front>');
        $breadcrumb[] = l('Новости', 'novosti');
        break;      
      case 'document':
        $breadcrumb[] = l(t('Home'), '<front>');
        $breadcrumb[] = l('Документация', 'documents');
        break;      
      case 'brand':
        $breadcrumb[] = l(t('Home'), '<front>');
        $breadcrumb[] = l('Поставщики', 'manufactures');
      break;
      case 'product':
        $breadcrumb[] = l(t('Home'), '<front>');
        $breadcrumb[] = l('Каталог', 'catalog');
        if (!empty($variables['node']->field_product_brand)) {
          $brand = $variables['node']->field_product_brand[LANGUAGE_NONE][0]['node'];
          $breadcrumb[] = l($brand->title, 'node/'.$brand->nid);
        }
      break;
      case 'current_offer':
        
        $breadcrumb[] = l(t('Home'), '<front>');
        $breadcrumb[] = l('Каталог', 'catalog');
        if (!empty($variables['node']->field_offer_brand)) {
          $brand = $variables['node']->field_offer_brand[LANGUAGE_NONE][0]['node'];
          $breadcrumb[] = l($brand->title, 'node/'.$brand->nid);
        }
        
      break;
    }
    if ($breadcrumb) {
      drupal_set_breadcrumb($breadcrumb);
    }
  }

  $variables['is_product_page'] = FALSE;
  if (!empty($variables['node']) && $variables['node']->type == 'product') {
    $variables['is_product_page'] = TRUE;
  }
  if (!empty($variables['node']) && $variables['node']->type == 'current_offer') {
    $variables['is_product_page'] = TRUE;
    $path = libraries_get_path('jquery.jcarousel');
    if (file_exists($path . '/lib/jquery.jcarousel.min.js')) drupal_add_js($path . '/lib/jquery.jcarousel.min.js');
    elseif (file_exists($path . '/lib/jquery.jcarousel.js')) drupal_add_js($path . '/lib/jquery.jcarousel.js');
  } else {
    drupal_add_js(drupal_get_path('theme', 'skb4').'/js/jquery.jcarousel.min.js');
  }
  $variables['is_category_page'] = FALSE;
  $args = arg();
  if (count($args) == 3 && $args[0] == 'taxonomy' && $args[1] == 'term' && is_numeric($args[2])) {
    $variables['is_category_page'] = TRUE;
  }
  
  if (count($args) == 3 && $args[0] == 'novosti' && $args[1] == 'manufactures' && is_numeric($args[2])) {
  	$breadcrumb = array();
  	$breadcrumb[] = l(t('Home'), '<front>');
    $breadcrumb[] = l('Новости', 'novosti');
    drupal_set_breadcrumb($breadcrumb);
  }

  // $variables['main_menu']['menu-444']['#below'][] = $variables['main_menu']['menu-424'];

  $menu_name = variable_get('menu_main_links_source', '');

  if ($menu_name) {

    $main_menu_tree = menu_tree($menu_name);
		//dsm($main_menu_tree);
    $tree = taxonomy_get_tree(1, 0);

    $categories = taxonomy_get_tree(1, 0, 1);
    $items = array();
    foreach($categories as $t) {
      // $term_wrapper = entity_metadata_wrapper('taxonomy_term',$t->tid);
      /*$items[] = array(
        '#title' => $t->name, 
        '#href' => 'taxonomy/term/'.$t->tid,
        '#theme' => 'menu_link__main_menu',
        '#localized_options' => '',
      );*/
      $items[] = l($t->name, 'taxonomy/term/'.$t->tid);
    }

    if ($items) {
    	/*$main_menu_tree['444']['#below'] = array(
    	 	'#sorted' => TRUE, 
    	 	'#theme_wrappers' => array('menu_tree__main_menu'),
    	 	//'#below' => ,
			);*/
			// $main_menu_tree['444']['#theme'] = 'skb4_menu_link__main_menu';
			// $main_menu_tree['444']['#below'] += $items;
			$main_menu_tree['444']['#below_render'] = theme('item_list', array('items' => $items));
			// $variables['main_menu']['#theme'] = 'menu_link__main_menu';
			// $variables['main_menu']['444']['#below_render'] = theme('item_list', array('items' => $items));
    }
    // $main_menu_tree['#attributes'] = array('id' => 'main-menu', 'class'=> array('links'));
    // $main_menu_tree['#theme'] = 'links__system_main_menu';
    // dsm($main_menu_tree);
    $variables['main_menu'] = render($main_menu_tree);
    /*$variables['main_menu'] = theme('links__system_main_menu', array('links' => $main_menu_tree, 'attributes' => array('id' => 'main-menu', 'class' => array('links', 'inline', 'clearfix')), 'heading' => t('Main menu')));*/
    $variables['main_menu_tree'] = $main_menu_tree;
    // dsm($variables['main_menu']);


    $variables['primary_nav'] = theme('links__system_main_menu', array(
      'links' => $variables['main_menu'],
      'attributes' => array(
        'class' => array('links', 'inline', 'main-menu'),
      )
    ));
    $main_menu = variable_get('menu_main_links_source', 'main-menu'); 
    $main_menu_expanded = menu_tree_page_data($main_menu); //dsm($main_menu_expanded);

    // $menu0 = menu_tree_page_data('menu-services-main');
    
    /*$belows = array(583 => 'menu-services', 584 => 'menu-exhibitions', 585 => 'menu-inspect', 586 => 'menu-special');
    foreach ($menu0 as $key => &$menu) {
      $menu0[$key]['below'] = menu_tree_page_data($belows[$menu['link']['mlid']]);
      $menu0[$key]['link']['has_children'] = 1;
      $menu0[$key]['link']['expanded'] = 1;
    }*/
    
    /*$link = array(
      'link' => array(
        'menu_name' => 'main_menu',
        'access' => 1,
        'hidden' => 0,
        'has_children' => 1,
        'title' => 'Услуги',
        'href' => 'services',
        'mlid' => 1000001,
        'in_active_trail' => arg(0) == 'services' ? 1 : 0,
      ),
      'below' => $menu0
    );*/
    // array_unshift($main_menu_expanded, $link);
    $variables['main_menu_expanded'] = menu_tree_output($main_menu_expanded);
  }

  if (isset($variables['node'])) {
    $variables['theme_hook_suggestions'][] = 'page__type__'. $variables['node']->type; //для определенного типа ноды
    $variables['theme_hook_suggestions'][] = "page__node__" . $variables['node']->nid; //для определенного номера ноды
  }
  
  /**/
	
	// главное меню
  /*if (isset($variables['main_menu'])) {

    $menu_name = variable_get('menu_main_links_source', '');

    if ($menu_name) {
      $main_menu_tree = menu_tree($menu_name);

      $mobile_menu = $main_menu_tree;
      unset($mobile_menu['347']);

      // левое меню
      $menu_items_with_below = array();
      foreach($main_menu_tree as $mid => $item) {
      	if (is_numeric($mid) && !empty($item['#below']) && $item['#below']) {
      		$menu_items_with_below[$mid] = $item['#below'];
      	}
      }
      $active_trail = menu_get_active_trail();

      if (!empty($active_trail[1]) && !empty($active_trail[1]['mlid']) && !empty($menu_items_with_below[$active_trail[1]['mlid']])) {
      	$variables['show_content_column'] = TRUE;
      	$variables['page']['content_column'] = '<div class="sub-main-menu"><h2>'.$active_trail[1]['link_title'].'</h2>'.render($menu_items_with_below[$active_trail[1]['mlid']]).'</div>';
      }
      //

      $main_menu_tree[347]['#attributes']['class'][] = 'products-menu-item';
      
      // формируем меню для продукции
      $variables['product_menu'] = '<a class="tabs active" tabid="1">Системы очистки</a>
      <a class="tabs" tabid="2">Очистители</a>
      <div class="tabs-content-wrapper">
      	<div class="tab1-content tab-content">';

      $terms = taxonomy_get_tree(1); 
	    
	    // Build the menu.
	    $term_count = count($terms);
	    $tree = array();
	    foreach($terms as $term) {
	    	if ($term->depth == 0) {
	    		$term->children = array();
	    		$tree[$term->tid] = $term;
	    	} else {
	    		$tree[$term->parents[0]]->children[] = $term;
	    	}
	   	}
	   	$class = 'opened with-items'; $mobile_product1 = $mobile_product2 = array();
	   	foreach($tree as $t) {
	   		$part_content = '';
	   		if (count($t->children)) {
	   			
	   			$part_content = '<h4 class="'.$class.'" tid="'.$t->tid.'">'.l($t->name, 'taxonomy/term/'.$t->tid).'</h4>';
	   			$sub_items = array();
	   			foreach ($t->children as $ch) {
	   				$t_wrapper = entity_metadata_wrapper('taxonomy_term', $ch->tid);
   					$image = $t_wrapper->field_image->value();
            if (isset($image[0])) {
              $image = $image[0];
            }
   					if (!empty($image)) {
              // $link = l($ch->name, 'products', array('query' => array('type' => $ch->tid)));
              $link = l($ch->name, 'taxonomy/term/'.$ch->tid);
	   					$sub_items[] = '<div class="sub-menu-item">
	   					<div class="icon-wrapper">
	   						'.theme('image_style', array('style_name' => 'product_type_icon', 'path' => $image['uri'])).'
	   					</div>
	   					'.$link.' '.$t_wrapper->description->value().'</div>';
   					}
	   			}
	   			$part_content .= '<div class="list-container">'.theme('item_list', array('items' => $sub_items)).'</div>';
          // $link1 = l($t->name, 'products', array('query' => array('type' => $t->tid)));
          $link1 = l($t->name, 'taxonomy/term/'.$t->tid);
	   			$mobile_product1[] = $link1.' '.theme('item_list', array('items' => $sub_items));
	   			$class = "with-items";
	   		} else {
          // $link2 = l($t->name, 'products', array('query' => array('type' => $t->tid)));
          $link2 = l($t->name, 'taxonomy/term/'.$t->tid);
	   			$part_content = '<h4 tid="'.$t->tid.'">'.$link2.'</h4>';
	   			// $mobile_product1[] = l($t->name, 'products', array('query' => array('type' => $t->tid)));
          $mobile_product1[] = l($t->name, 'taxonomy/term/'.$t->tid);
	   		}
	   		$variables['product_menu'] .= $part_content;
	   	}
    	$variables['product_menu'] .= '</div>
    	 <div class="tab2-content tab-content hidden">';
    	//dsm($mobile_product1);
      $terms = taxonomy_get_tree(2); 
	    
	    // Build the menu.
	    $term_count = count($terms);
	    $items = array();
	    foreach($terms as $t) {
	    	// $items[] = l($t->name, 'products', array('query' => array('category' => $t->tid)));
        $items[] = l($t->name, 'taxonomy/term/'.$t->tid);
	    }
	    $mobile_product2 = $items;
      $variables['product_menu'] .= theme('item_list', array('items' => $items)).'</div></div>';

      $main_menu_tree[347]['#below_render'] = '<div class="product-menu">'.$variables['product_menu'].'</div>';
      $variables['main_menu'] = render($main_menu_tree);

      // мобильное меню
      $mobile_product = array(
      	0 => array(
      			'#theme' => 'menu_link__main_menu',
      			'#attributes' => array(
      				'class' => array(
      					0 => 'expanded',
      					1 => 'active-trail',
      				),
      			),
      			'#title' => 'Системы очистки',
      			'#href' => 'products',
      			'#below_render' => theme('item_list', array('items' => $mobile_product1)),
      			'#localized_options' => array(),
      		),
      	1 => array(
      			'#theme' => 'menu_link__main_menu',
      			'#attributes' => array(
      				'class' => array(
      					0 => 'expanded',
      					1 => 'active-trail',
      				),
      			),
      			'#title' => 'Очистители',
      			'#href' => 'products',
      			'#below_render' => theme('item_list', array('items' => $mobile_product2)),
      			'#localized_options' => array(),
      		),
      );  //dsm($mobile_product);

      // array_unshift($mobile_menu, $mobile_product[0], $mobile_product[1]);
      $mobile_menu = array_merge($mobile_product, $mobile_menu);;
      $mobile_menu[] = array(
        '#theme' => 'menu_link__main_menu',
        '#localized_options' => array(),
        '#attributes' => array(),
        '#item_type' => 'contacts',
        '#title' => '',
        '#href' => '',
        '#markup' => '<div class="menu-contacts"><div class="phone"><div><img src="'.drupal_get_path('theme', 'ameks').'/images/phone_icon.png" width="27" /></div><div>'.$variables['phone_number'].'</div></div>
      <div class="email"><a href="mailto:'.$variables['email'].'">'.$variables['email'].'</a></div>
      <div class="distr">'.$variables['distr'].'</div>
      </div>');
      $variables['mobile_menu'] = $mobile_menu;

    } else {
      $variables['main_menu'] = FALSE;
    }
  }
  else {
    $variables['main_menu'] = FALSE;
  }

  // нижнее меню
  if (isset($variables['secondary_menu'])) {

    $menu_name = variable_get('menu_secondary_links_source', '');

    if ($menu_name) {

      $second_menu_tree = menu_tree($menu_name);
      // уберём все подпункты
      $items = array();
      foreach (element_children($second_menu_tree) as $key) {
      	$menu_item = $second_menu_tree[$key];
      	$items[] = l($menu_item['#title'], $menu_item['#href'], array('attributes' => $menu_item['#attributes']));
      }
      $variables['secondary_menu'] = theme('item_list', array('items' => $items));
      
    } else {
      $variables['secondary_menu'] = FALSE;
    }
  }
  else {
    $variables['secondary_menu'] = FALSE;
  }*/
}

/**
 *
 */
function skb4_preprocess_node(&$variables) {
	
	/*$variables['webform_already_paste'] = FALSE;
	if (!empty($variables['content']['body']) && preg_match("/\{webform\}/", $variables['content']['body'][0]['#markup'])) {
		$webform_html = "<div class=\"webform-wrapper\">".render($variables['content']['webform'])."</div>";
		$variables['webform_already_paste'] = TRUE;
		$variables['content']['body'][0]['#markup'] = preg_replace("/\{webform\}/", $webform_html, $variables['content']['body'][0]['#markup']);
	}*/
	
	$variables['classes_array'][] = 'node-view-mode-'.$variables['view_mode'];	
  

	if ($variables['type'] == 'news') {
		$variables['submitted'] = format_date($variables['created'], 'custom', 'd.m.Y');
	}

	/*if ($variables['type'] == 'product' && $variables['view_mode'] == 'teaser') {		
		foreach (element_children($variables['content']['field_image']) as $key) {
			if ($key > 0) {
				unset($variables['content']['field_image'][$key]);
			}
		}

		$variables['content']['links']['node']['#links']['set-request'] = array(
			'title' => 'Оставить запрос',
			'href' => 'get-webform/'.$variables['nid'],
			'html' => TRUE,
			'attributes' => array(
				'class' => array('autodialog'),
				'data-dialog-width' => '630',
				'data-dialog-ajax-disable-redirect' => 'true',
			),
		);

	} elseif ($variables['type'] == 'product' && $variables['view_mode'] == 'full') {
		$node_wrapper = entity_metadata_wrapper('node', $variables['nid']);
		$variables['featured_products'] = $variables['grands'] = '';
		if (!empty($variables['field_featured_products'][LANGUAGE_NONE]) && count($variables['field_featured_products'][LANGUAGE_NONE])) {
			foreach($variables['field_featured_products'][LANGUAGE_NONE] as $n) {
				$node = node_load($n['nid']);
				$view = node_view($node, 'teaser');
				$variables['featured_products'] .= render($view);
			}
		}
		if ($node_wrapper->field_product_grands->value()) {
			foreach($node_wrapper->field_product_grands->value() as $term) {
				$w_term = entity_metadata_wrapper('taxonomy_term', $term);
				$img = $w_term->field_image->value(); 
        if (isset($img[0])) {
          $img = $img[0];
        }
				$variables['grands'] .= theme('image_style', array('style_name' => 'grand', 'path' => $img['uri']));
			}
			$variables['grands'] = '<div class="product-grands">'.$variables['grands'].'</div>';
		}
    
    $variables['image_wrapper_class'] = '';
    $images = $node_wrapper->field_image->value();
    if (count($images) > 1) {
      $variables['image_wrapper_class'] = 'more_then_one_image';
    }
	}*/

  if ($variables['type'] == 'product' && $variables['view_mode'] == 'teaser') {
    // проставляем заголовок для списков товаров
    if (!empty($variables['field_product_list_title']) && !empty($variables['field_product_list_title'][LANGUAGE_NONE][0]['value']) && $variables['field_product_list_title'][LANGUAGE_NONE][0]['value'] != '') {
      $variables['title'] = $variables['field_product_list_title'][LANGUAGE_NONE][0]['value'];
    }
  }

  if ($variables['type'] == 'product' && $variables['view_mode'] == 'full') {
    
    $node_wrapper = entity_metadata_wrapper('node', $variables['nid']);
    if ($brend = $node_wrapper->field_product_brand->value()/*$node_wrapper->field_product_brand->value()*/) {
      //; dsm($brend);
      $brend_wrapper = entity_metadata_wrapper('node', $brend->nid);
      if ($brend_wrapper->field_brend_logo->value()) {
        $variables['brand_logo'] = theme('image_style', array('style_name' => 'brand-logo-block', 'path' => $brend_wrapper->field_brend_logo->value()['uri']));
      }    
      // $variables['catalog_brand_page'] = l('Каталог бренда', 'node/'.$brend->nid, array('fragment' => 'products'));
    }
    
    // проверять наличие документов
    /*if ($brend_wrapper->field_group_brand_docs->value()) {
      $variables['brand_licenses'] = l('Лицензии', 'node/'.$brend->nid, array('fragment' => 'docs'));
    }*/    
    
    $variables['order_form'] = '<div class="button-wrapper">'.l('Сделать заказ оборудования', 'catalog/order/'.$variables['nid'], 
      array('attributes' => array('class' => array('autodialog', 'button'), 'data-dialog-ajax' => 'true',
      'data-dialog-width' => '960'))).'</div>';
    
    //dsm($variables);
    //dsm($node_wrapper->field_product_brand->value());
  } elseif($variables['type'] == 'product' && $variables['nid'] == 1524) {
    //dsm($variables);
    if (!empty($variables['content']['field_product_image'])) {
      $images_count = count(element_children($variables['content']['field_product_image']));
      //dsm($images_count);
      if ($images_count > 1) {
        for ($i=1; $i < $images_count; $i++) { 
          unset($variables['content']['field_product_image'][$i]);
        }
      }
    }
  }


  if ($variables['type'] == 'brand' && $variables['view_mode'] == 'full') {

    // brand products block
//    $blockObject = block_load('views', 'products-block_1');
//    $block = _block_get_renderable_array(_block_render_blocks(array($blockObject)));
//    //$output = drupal_render($block);
//
//    if ($block) {
//      //$block['title'] = 'Товары поставщика';
//      $variables['content']['brand_products'] = [
//        '#markup' => drupal_render($block),
//        '#prefix' => '<div id="brand-products-block">',
//        '#suffix' => '</div>',
//      ];
//    }

    if (empty($variables['content']['field_brend_intro'])) {
      $variables['content']['field_brend_intro'] =
        [[
          '#markup' => '<h1>'.$variables['title'].'</h1>',
          '#prefix' => '<div class="field-name-field-brend-intro">',
          '#suffix' => '</div>'
        ]];
    } else {
      $source_text = $text = $variables['content']['field_brend_intro'][0]['#markup'];
      if (drupal_strlen($text) > 160) {
        $splice_pos = mb_strrpos(drupal_substr($source_text, 0, 160), ' ');
        if ($splice_pos === false) {
          $splice_pos = 160;
        }
        $text = drupal_substr($source_text, 0, $splice_pos).' <span class="arrow">→</span><span class="hidden">'.
          drupal_substr($source_text, $splice_pos, drupal_strlen($text)).'</span>';

      }
      $variables['content']['field_brend_intro'][0]['#markup'] = '<h1>'.$variables['title'].'</h1>'.$text;
    }

    $variables['content']['field_brend_intro'][0]['#markup'] .=
      '<div class="button-wrapper">'.l('Заказ оборудования', 'catalog/order/'.$variables['nid'], array('attributes' => array('class' => array('autodialog', 'button')))).'</div>';

    $nav_links = array();
    //$variables['content']['categories'] = '';
    $category_content = array();
    // $nav_links[] = l('Категории', 'node/'.$variables['nid'], array('fragment' => 'categories'));
    
    /*$query = db_select('field_data_field_brand_category', 'c')->fields('c', array('field_brand_category_tid'))
              ->condition('c.bundle', 'brand')->condition('c.entity_id', $variables['nid']);
    $query->leftJoin('taxonomy_term_data', 't', "t.tid = c.field_brand_category_tid ");
    $query->addField('t', 'name', 'title');
    $query->orderBy('t.name', 'ASC');
    $cats = $query->execute()->fetchAllKeyed();*/

    $query = db_select('field_data_field_brand_category', 'c')->condition('c.bundle', 'brand')->condition('c.entity_id', $variables['nid']);
    //->fields('c', array('field_brand_category_tid'))
    $query->leftJoin('taxonomy_term_data', 't', "t.tid = c.field_brand_category_tid ");
    $query->addField('c', 'field_brand_category_tid', 'tid');
    $query->addField('t', 'name', 'title');
    $query->leftJoin('field_data_field_docs_product_category', 'bc', "bc.entity_type='field_collection_item' AND bc.bundle='field_brand_category_products' AND bc.field_docs_product_category_tid = c.field_brand_category_tid ");
//    $query->addField('bc', 'delta', 'delta');
    $query->leftJoin('field_data_field_brand_category_products', 'bcp', "bcp.bundle='brand' AND bcp.field_brand_category_products_value = bc.entity_id ");
    $query->addField('bcp', 'delta', 'delta');
  //$query->orderBy('c.field_brand_category_tid');
    //$query->orderBy('c.field_brand_category_tid');
    $query->groupBy('t.name');
    $query->orderBy('delta', 'ASC'); //->orderBy('t.name', 'ASC');
    $cats = $query->execute()->fetchAll();

    $brand_category_descs = $brand_category_titles = $brand_category_prod_titles = $anchor = array();
    if (!empty($variables['field_brand_category_products'])) { //dsm($variables['field_brand_category_products']);
    	foreach($variables['field_brand_category_products'] as $pc) { //dsm($pc);
        $field_collection = entity_load('field_collection_item', array($pc['value']));

        if (!empty($field_collection[$pc['value']]->field_docs_product_category)) {
          
          $fc_cat_tid = $field_collection[$pc['value']]->field_docs_product_category[LANGUAGE_NONE][0]['tid'];

          if (!empty($field_collection[$pc['value']]->field_brand_category_desc) 
              && !empty($field_collection[$pc['value']]->field_brand_category_desc[LANGUAGE_NONE][0]['value'])) {
          $brand_category_descs[$fc_cat_tid] = $field_collection[$pc['value']]->field_brand_category_desc[LANGUAGE_NONE][0]['value'];
          }
          
          if (!empty($field_collection[$pc['value']]->field_brand_category_title) 
              && !empty($field_collection[$pc['value']]->field_brand_category_title[LANGUAGE_NONE][0]['value'])) {
            $brand_category_titles[$fc_cat_tid] = $field_collection[$pc['value']]->field_brand_category_title[LANGUAGE_NONE][0]['value'];
          }
          
          if (!empty($field_collection[$pc['value']]->field_brand_category_prod_title) 
              && !empty($field_collection[$pc['value']]->field_brand_category_prod_title[LANGUAGE_NONE][0]['value'])) {
            $brand_category_prod_titles[$fc_cat_tid] = $field_collection[$pc['value']]->field_brand_category_prod_title[LANGUAGE_NONE][0]['value'];
          }
          
          if (!empty($field_collection[$pc['value']]->field_brand_cat_prod_title_hide) 
              && !empty($field_collection[$pc['value']]->field_brand_cat_prod_title_hide[LANGUAGE_NONE][0]['value']) && $field_collection[$pc['value']]->field_brand_cat_prod_title_hide[LANGUAGE_NONE][0]['value']) {
            $brand_category_prod_titles[$fc_cat_tid] = 'hide';
          }
          if (!empty($field_collection[$pc['value']]->field_field_brand_cat_anchor) 
              && !empty($field_collection[$pc['value']]->field_field_brand_cat_anchor[LANGUAGE_NONE][0]['value'])) {
            $anchor[$fc_cat_tid] = $field_collection[$pc['value']]->field_field_brand_cat_anchor[LANGUAGE_NONE][0]['value'];
          }
        }
    	}
    }

    // документация
    $doc_nids = db_select('field_data_field_document_brand', 'b')->fields('b', array('entity_id'))
            ->condition('b.bundle', 'document')->condition('b.field_document_brand_nid', $variables['nid'])
            ->execute()->fetchCol();
    $brand_docs_category = array();
    foreach($doc_nids as $dnid) {
      $dnw = entity_metadata_wrapper('node', $dnid);
      $document_view = node_view($dnw->value()); //dsm($document_view);
      //$brand_docs_category = drupal_render($document_view['field_group_brand_docs']);
      if ($dnw->field_group_brand_docs->value()) { //dsm($dnw->field_group_brand_docs->value());      	
        $document_cats = array();
        foreach($dnw->field_group_brand_docs->value() as $fc) { //dsm($fc);
        	if(!empty($fc->field_docs_product_category)) {
        		$document_cats[$fc->field_docs_product_category[LANGUAGE_NONE][0]['tid']][] = $fc->item_id;
        	}


        }
        foreach($document_cats as $cat_tid => $fc_ids) {
        	$brand_docs_category[$cat_tid] = $document_view['field_group_brand_docs'];
        	foreach (element_children($brand_docs_category[$cat_tid]) as $key) {
        	 	if(!in_array(key($brand_docs_category[$cat_tid][$key]['entity']['field_collection_item']), $fc_ids)) {
        	 		unset($brand_docs_category[$cat_tid][$key]);
        	 	}
        	 } 
        } 
        /*$fc_view = $document_view['field_group_brand_docs'];


          //$field_collection = entity_load('field_collection_item', array($dc['value']));
          if (!empty($fc->field_docs_product_category)) {
          	$cat_tid = $fc->field_docs_product_category[LANGUAGE_NONE][0]['tid'];
          	if (!isset($brand_docs_category[$cat_tid]))
          		$brand_docs_category[$cat_tid] = $fc_view;
          	foreach (element_children($brand_docs_category[$cat_tid]) as $key) {
          		if($brand_docs_category[$cat_tid][$key]->)
          	}
          	//$fc_entity = field_collection_item_load($fc->item_id); dsm($fc_entity);
            $fv = field_view_field('field_collection_item', $fc, 'field_group_brand_docs'); // field_collection_item           
            dsm($fv);
            $brand_docs_category[$fc->item_id] = drupal_render_children($fv);//render($fc);
            // dsm($fc);
            // $field_collection[$pc['value']]->field_brand_category_desc[LANGUAGE_NONE][0]['value'];
          } */
         
      }
    }
    //dsm($brand_docs_category);

    foreach ($cats as $cat) {

      $tid = $cat->tid;
      $cat_title = $cat->title;

      $cat_data = '';


      $q2 = db_select('field_data_field_product_brand', 'b')->fields('b', array('entity_id'));
      $q2->condition('field_product_brand_nid', $variables['nid']);
      $q2->join('field_data_field_product_category', 'c', " c.bundle = 'product' AND c.entity_id = b.entity_id ");      
      $q2->condition('c.field_product_category_tid', $tid);
      $q2->range(0,20);
      //dsm($q2->__toString().' nid='.$variables['nid'].' tid='.$tid);
      $nids = $q2->execute()->fetchCol(); //dsm($nids);
      if ($nids) {
        $products = node_load_multiple($nids);
        $products_views  = node_view_multiple($products, 'teaser');

        $products_title = '<h3>Товары поставщика в категории</h3>';
        if (!empty($brand_category_prod_titles[$tid]) && $brand_category_prod_titles[$tid] != '') { 
          if ($brand_category_prod_titles[$tid] == 'hide') {
            $products_title = '';
          } else {
            $products_title = '<h3>'.$brand_category_prod_titles[$tid].'</h3>';
          }
        }
        $cat_data .= $products_title . '<div class="products-content">' . render($products_views).'</div>';
      }

      if (!empty($brand_category_descs[$tid])) {
        $cat_data .= '<div class="category-desc">'.$brand_category_descs[$tid]."</div>";
      }

      if (!empty($brand_docs_category[$tid])) {
        $cat_data .= '<h3>Документация поставщика</h3><div class="category-docs">'.drupal_render_children($brand_docs_category[$tid])."</div>";
      }
      if ($cat_data != '') {
        if (!empty($brand_category_titles[$tid])) {
          $cat_title = $brand_category_titles[$tid];
        } 
        module_load_include('inc', 'pathauto');
        if (!empty($anchor[$tid])) {
          $anchor_link = pathauto_cleanstring($anchor[$tid]);
        } else {
          $anchor_link = pathauto_cleanstring($cat_title);
        }
        $nav_links[] = l($cat_title, 'node/'.$variables['nid'], array('fragment' => $anchor_link/* 'category-'.$tid*/));

        $cat_data = '<div class="brand-category-row"><a id="'.$anchor_link.'"></a><h2>'.$cat_title.'</h2>'.$cat_data.'</div>';
        $category_content[] = $cat_data;
      } else {
        //$nav_links[] = l($cat_title, 'taxonomy/term/'.$tid);
      }
    }

    if ($category_content) {
      $variables['content']['categories'] = implode('', $category_content);
    }
    
    // актуальные предложения
    $query = db_select('node', 'n')->fields('n', array('nid'))->orderBy('n.title', 'ASC')
               ->condition('n.type', 'current_offer')->condition('n.status', 1);
    $query->leftJoin('field_data_field_offer_brand', 'ob', " ob.bundle = 'current_offer' AND ob.entity_id = n.nid ");
    $query->condition('ob.field_offer_brand_nid', $variables['nid']);
    $nids = $query->execute()->fetchCol();
    
    $rows = array();
    $header = array('№', /*'Бренд/Производитель',*/ 'Наименование/описание', 'Ед. изм.', 'Цена за ед. с НДС', 'Минимальная партия/сумма', 'Срок поставки');
    $i = 1;
    $link_image = theme('image', array('path' => drupal_get_path('theme', 'skb4').'/svg/link.svg'));
    foreach($nids as $nid) {
      $nw = entity_metadata_wrapper('node', $nid);
      $rows[] = array(
        $i,
        /*l($nw->field_offer_brand->value()->title, 'node/'.$nw->field_offer_brand->value()->nid),*/
        l($nw->title->value().' '.$link_image, 'node/'.$nid, array('html' => TRUE)), 
        array('data' => $nw->field_offer_units->value(), 'class' => array('center')),
        array('data' => $nw->field_offer_price->value(), 'class' => array('right')),
        array('data' => $nw->field_offer_min_amount->value(), 'class' => array('right')),
        array('data' => $nw->field_offer_delivery_time->value(), 'class' => array('right')),
      );
      $i++;
    }

    if ($rows) {
      $variables['content']['current_offer'] = theme('table', array('header' => $header, 'rows' => $rows, 'sticky' => FALSE, 'attributes' => array('id' => 'current-offers')));
    }
    //dsm($variables['content']);
    /*$query = db_select('field_data_field_product_brand', 'b')->fields('b', array('entity_id'))
              ->condition('b.bundle', 'product')
              ->condition('b.entity_type', 'node')
              ->condition('b.field_product_brand_nid', $variables['nid']);
    $query->leftJoin('node', 'n', 'n.nid = b.entity_id');
    $query->condition('n.status', 1);
    $query->orderBy('n.title', 'ASC');
    $nids = $query->execute()->fetchCol();
    $variables['content']['products'] = $variables['content']['field_brand_gallery'] = '';
    if ($nids) {
      $products = node_load_multiple($nids);
      $variables['content']['products'] = node_view_multiple($products, 'teaser');
      $nav_links[] = l('Продукция', 'node/'.$variables['nid'], array('fragment' => 'products'));
    }*/
    
    /*if ($variables['field_group_brand_docs']) {
      $nav_links[] = l('Документы', 'node/'.$variables['nid'], array('fragment' => 'docs'));    
    }*/

    // if ($variables['field_brand_gallery']) {
    //   $nav_links[] = l('Галерея', 'node/'.$variables['nid'], array('fragment' => 'gallery'));
    // }
    
    // $nav_links[] = l('Заказ оборудования', 'node/'.$variables['nid'], array('fragment' => 'brand-form'));
    if ($nav_links) {
      $variables['content']['nav_links'] = array(
        '#theme' => 'item_list',
        '#items' => $nav_links,
        '#prefix' => '<div class="brand-nav-links">',
        '#suffix' => '</div>',
      );
    }

    /*$form_node = node_load(98); //dsm($form_node);
    foreach ($form_node->webform['components'] as $key => $component) {
      if ($component['form_key'] == '_brand') {
        $form_node->webform['components'][$key]['value'] = $variables['title'];
      }
    }
    
    $form_view = node_view($form_node, 'full');
    $form_view['webform']['#page'] = TRUE;
    $variables['content']['form'] = $form_view;*/
    
  }
}

/**
 *
 */
function skb4_breadcrumb($variables) {

  $breadcrumb = $variables['breadcrumb'];

  if (!empty($breadcrumb)) {
  	if (empty($breadcrumb['hide_page_title'])) {
  		$breadcrumb[] = drupal_get_title();
  	}
  	if (!empty($breadcrumb['hide_page_title'])) {
  		unset($breadcrumb['hide_page_title']);
  	}
    
    $output = '<div class="breadcrumb">' . implode('<span class="breadcrumb-delimeter"><em>)</em>(</span>', $breadcrumb) . '</div>';
    return $output;
  }
}

/** 
 *
 */
function skb4_preprocess_html(&$variables) {
  $element = array(
    '#tag' => 'meta',
    '#attributes' => array(
      'http-equiv' => 'X-UA-Compatible',
      'content' => 'IE=edge',
    ),
    '#weight' => -5,
  );
  drupal_add_html_head($element, 'system_meta_ie_browser');

  $element = array(
    '#tag' => 'meta',
    '#attributes' => array(
      'name' => 'viewport',
      'content' => 'width=device-width, initial-scale=1',
    ),
    '#weight' => -4,
  );
  drupal_add_html_head($element, 'viewport'); 
  //dsm($variables);
  drupal_add_html_head_link(array('rel' => 'icon', 'href' => '/sites/all/themes/skb4/favicon.svg', 'type' => 'image/svg+xml'));
}

/**
 * Implements hook_form_alter().
 */
function skb4_form_alter(&$form, &$form_state, $form_id) {

  if ($form_id == 'search_block_form') {
  	$form['search_block_form']['#attributes']['placeholder'] = 'Поиск по каталогу';
  	$form['#attributes']['class'][] = 'hidden-form';
  }
}

/**
 *
 */
/*function skb4_file_icon($variables) {
  $file = $variables['file'];
  $alt = $variables['alt'];
  //$icon_directory = drupal_get_path('theme', 'ameks').'/icons/';

  $mime = check_plain($file->filemime);
  $icon_url = file_icon_url($file, $icon_directory);
  return '<img class="file-icon" alt="' . check_plain($alt) . '" title="' . $mime . '" src="' . $icon_url . '" />';
}*/

/**
 * Implements hook_theme().
 */
function skb4_theme($existing, $type, $theme, $path) {
  return array(
    'webform_managed_file' => array(
      'render element' => 'element',
      'function' => 'skb4_file_managed_file',
    ),
    //    добавляем шаблон menu_link для меню Test menu
    'menu_link__main_menu' => array(
        'render element' => 'element',
    ),
  );
}

/**
 * Implements hook_theme_registry_alter().
 */
function skb4_theme_registry_alter(&$theme_registry) {
  // Kill the next/previous forum topic navigation links.
  /*
   *foreach ($theme_registry['forum_topic_navigation']['preprocess functions'] as $key => $value) {
   *  if ($value = 'template_preprocess_forum_topic_navigation') {
   *    unset($theme_registry['forum_topic_navigation']['preprocess functions'][$key]);
   *  }
   *}
   */
  /* Your code here */
  // $theme_registry['file_managed_file']['type'] = 'theme';
  // $theme_registry['theme path'] = drupal_get_path('theme', 'skb4');
  // $theme_registry['function'] = 'skb4_file_managed_file';
}

/**
 *
 */
function skb4_file_managed_file($variables) { 
  $element = $variables['element']; //dsm($element);
  $element['upload']['#prefix'] = '<div class="file-input-container file-input-container-init">
    <button class="btn btn-default">'.'Прикрепить файл'.'</button>'; // <mark>'.t('No file selected').'</mark>
  $element['upload']['#suffix'] = '</div>';

  $attributes = array();
  if (isset($element['#id'])) {
    $attributes['id'] = $element['#id'].'-wrapper';
  }
  if (!empty($element['#attributes']['class'])) {
    $attributes['class'] = (array) $element['#attributes']['class'];
  }
  $attributes['class'][] = 'form-managed-file';  

  // This wrapper is required to apply JS behaviors and CSS styling.
  $output = '';
  $output .= '<div' . drupal_attributes($attributes) . '>';
  $output .= drupal_render_children($element);
  $output .= '</div>';
  return $output;
}

/**
 *
 */
function _skb4_format_bytes($size, $precision = 2) {
    $base = log($size, 1024);
    $suffixes = array('', 'Kb', 'Mb', 'G', 'T');   

    return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
}

/**
 *
 */
function skb4_menu_link__main_menu(array $variables) {
  $element = $variables['element'];
  $sub_menu = $output = '';  
  $element['#attributes']['class'][] = 'menu-'.$element['#original_link']['mlid'];

  // if (!empty($element['#item_type']) && $element['#item_type'] == 'contacts') {
  //   return '<li>'.$element['#markup'].'</li>';
  // }
  
  if(!empty($element['#below'])) {
    $sub_menu = '<div class="sub-menu-wrapper">'.drupal_render($element['#below']).'</div>';
  }

  if(!empty($element['#below_render'])) {
  	$sub_menu = '<div class="sub-menu-wrapper">'.$element['#below_render'].'</div>';
    //$sub_menu = $element['#below_render'];
  }
  
  $output = l($element['#title'], $element['#href'], $element['#localized_options']);  

  return '<li' . drupal_attributes($element['#attributes']) . '>' . $output . $sub_menu . "</li>\n";
}

/**
agree|Согласен с условиями <a href="/politica" target="_blank">пользовательского соглашения</a>
*/