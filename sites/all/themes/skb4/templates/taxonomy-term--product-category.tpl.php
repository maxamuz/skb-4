<?php

/**
 * @file
 * Default theme implementation to display a term.
 *
 * Available variables:
 * - $name: (deprecated) The unsanitized name of the term. Use $term_name
 *   instead.
 * - $content: An array of items for the content of the term (fields and
 *   description). Use render($content) to print them all, or print a subset
 *   such as render($content['field_example']). Use
 *   hide($content['field_example']) to temporarily suppress the printing of a
 *   given element.
 * - $term_url: Direct URL of the current term.
 * - $term_name: Name of the current term.
 * - $classes: String of classes that can be used to style contextually through
 *   CSS. It can be manipulated through the variable $classes_array from
 *   preprocess functions. The default values can be one or more of the following:
 *   - taxonomy-term: The current template type, i.e., "theming hook".
 *   - vocabulary-[vocabulary-name]: The vocabulary to which the term belongs to.
 *     For example, if the term is a "Tag" it would result in "vocabulary-tag".
 *
 * Other variables:
 * - $term: Full term object. Contains data that may not be safe.
 * - $view_mode: View mode, e.g. 'full', 'teaser'...
 * - $page: Flag for the full page state.
 * - $classes_array: Array of html class attribute values. It is flattened
 *   into a string within the variable $classes.
 * - $zebra: Outputs either "even" or "odd". Useful for zebra striping in
 *   teaser listings.
 * - $id: Position of the term. Increments each time it's output.
 * - $is_front: Flags true when presented in the front page.
 * - $logged_in: Flags true when the current user is a logged-in member.
 * - $is_admin: Flags true when the current user is an administrator.
 *
 * @see template_preprocess()
 * @see template_preprocess_taxonomy_term()
 * @see template_process()
 *
 * @ingroup themeable
 */
?>
<div id="taxonomy-term-<?php print $term->tid; ?>" class="<?php print $classes; ?>" data-sticky-container>

  <?php if (!$page): ?>
    <h2><a href="<?php print $term_url; ?>"><?php print $term_name; ?></a></h2>
  <?php endif; ?>

  <div class="content">
    <?php print render($content['field_category_image']); ?>
    <?php print render($content['field_category_dop_text']); ?>
  </div>

  <?php if (!empty($content['subcats'])) { print render($content['subcats']); } ?>
  
  <div class="content-bottom"><div>
  	<?php 
    
  	if (!empty($content['subcats']) || !empty($content['nav_links'])) {
  		print '<div class="category-navigation">';
      print '<div class="text-and-menu">';
      if (!empty($content['field_category_menu_prefix'])) { print render($content['field_category_menu_prefix']); }
      if (!empty($content['nav_links'])) { print render($content['nav_links']); }
      print "</div>";
  		print "</div>";
  	}
        print '<div class="button-wrapper">'.l('Заказ оборудования', 'ordering', array('attributes' => array('class' => array('button', 'autodialog')))).'Бесплатная консультация от наших менеджеров</div>';
    ?>
  </div>
  </div>

  <?php if($content['brands']) {  print render($content['brands']); } ?>
  <?php if($content['products']) {  print render($content['products']); } ?>

  <?php if (isset($content['field_category_video'])): ?>
    <div class="video-wrapper">
      <a id="video" class="anchor"></a>
      <h2>Видео</h2>
      <?php print render($content['field_category_video']); ?>
    </div>
    <?php endif; ?>

  <?php if(isset($content['description'])) { print '<div class="category-content"><a id="category-content" class="anchor"></a>'.render($content['description']).'</div>'; } ?>

</div>
