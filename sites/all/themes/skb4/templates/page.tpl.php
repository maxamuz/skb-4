<?php

/**
 * @file
 * Default theme implementation to display a single Drupal page.
 *
 * The doctype, html, head and body tags are not in this template. Instead they
 * can be found in the html.tpl.php template in this directory.
 *
 * Available variables:
 *
 * General utility variables:
 * - $base_path: The base URL path of the Drupal installation. At the very
 *   least, this will always default to /.
 * - $directory: The directory the template is located in, e.g. modules/system
 *   or themes/bartik.
 * - $is_front: TRUE if the current page is the front page.
 * - $logged_in: TRUE if the user is registered and signed in.
 * - $is_admin: TRUE if the user has permission to access administration pages.
 *
 * Site identity:
 * - $front_page: The URL of the front page. Use this instead of $base_path,
 *   when linking to the front page. This includes the language domain or
 *   prefix.
 * - $logo: The path to the logo image, as defined in theme configuration.
 * - $site_name: The name of the site, empty when display has been disabled
 *   in theme settings.
 * - $site_slogan: The slogan of the site, empty when display has been disabled
 *   in theme settings.
 *
 * Navigation:
 * - $main_menu (array): An array containing the Main menu links for the
 *   site, if they have been configured.
 * - $secondary_menu (array): An array containing the Secondary menu links for
 *   the site, if they have been configured.
 * - $breadcrumb: The breadcrumb trail for the current page.
 *
 * Page content (in order of occurrence in the default page.tpl.php):
 * - $title_prefix (array): An array containing additional output populated by
 *   modules, intended to be displayed in front of the main title tag that
 *   appears in the template.
 * - $title: The page title, for use in the actual HTML content.
 * - $title_suffix (array): An array containing additional output populated by
 *   modules, intended to be displayed after the main title tag that appears in
 *   the template.
 * - $messages: HTML for status and error messages. Should be displayed
 *   prominently.
 * - $tabs (array): Tabs linking to any sub-pages beneath the current page
 *   (e.g., the view and edit tabs when displaying a node).
 * - $action_links (array): Actions local to the page, such as 'Add menu' on the
 *   menu administration interface.
 * - $feed_icons: A string of all feed icons for the current page.
 * - $node: The node object, if there is an automatically-loaded node
 *   associated with the page, and the node ID is the second argument
 *   in the page's path (e.g. node/12345 and node/12345/revisions, but not
 *   comment/reply/12345).
 *
 * Regions:
 * - $page['help']: Dynamic help text, mostly for admin pages.
 * - $page['highlighted']: Items for the highlighted content region.
 * - $page['content']: The main content of the current page.
 * - $page['sidebar_first']: Items for the first sidebar.
 * - $page['sidebar_second']: Items for the second sidebar.
 * - $page['header']: Items for the header region.
 * - $page['footer']: Items for the footer region.
 *
 * @see template_preprocess()
 * @see template_preprocess_page()
 * @see template_process()
 * @see html.tpl.php
 *
 * @ingroup themeable
 */
?>

<div id="header">

  <div class="top-row-wrapper">
    <div class="top-row">
      <?php if ($work_time): ?>
        <div class="work-time"><?php print $work_time; ?></div>
      <?php endif; ?>
      <?php if ($phone): ?>
        <div class="phone"><?php print $phone; ?> </div>
      <?php endif; ?>
      <?php if ($email): ?>
        <div class="email"><?php print $email; ?></div>
      <?php endif; ?>
      <div class="social-links">
        <a href="/" class="facebook"></a>
        <a href="/" class="insta"></a>
        <a href="/" class="vk"></a>
        <a href="/" class="youtube"></a>
      </div>
      <?php if ($page['search']): ?>
        <div class="search-wrapper">
          <?php print render($page['search']); ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
  <?php if ($page['top_search']): ?>
    <div id="top_search" class="top_search">
      <?php print render($page['top_search']); ?>
    </div>
  <?php endif; ?>

  <div class="section clearfix">

    <div id="navigation">

      <?php if ($logo): ?>
        <div class="logo-wrapper">
          <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home" id="logo">
            <img src="<?php print $logo; ?>" alt="<?php print t('Home'); ?>" width="140" />
          </a>
        </div>
      <?php endif; ?>
      <?php if ($phone): ?>
        <div class="phone"><?php print $phone; ?> <?php print $phone_dop; ?></div>
      <?php endif; ?>

      <div id="menu-for-mobile">
        <?php if ($main_menu): ?>
          <div class="main-menu-wrapper">
            <?php /*print $main_menu*/ ?>
            <?php if ($page['mobile_search']): ?>
              <?php print render($page['mobile_search']); ?>
            <?php endif; ?>
            <?php //print render($main_menu_expanded);
            print $main_menu;
            /*print  theme('links__system_main_menu', array('links' => $main_menu_tree, 'attributes' => array('id' => 'main-menu', 'class' => array('links', 'inline', 'clearfix')), 'heading' => t('Main menu')));*/ ?>
          </div>
          <!-- <div class="feedback"><a class="autodialog" data-dialog-ajax="true" data-dialog-width="960" href="/ordering">Сделать заказ</a></div> -->
          <!-- Кнопка для открытия popup -->
          <button id="openPopup" style="padding: 10px 20px; background: #007cba; color: white; border: none; cursor: pointer;">
            Сделать заказ
          </button>

          <!-- Popup окно -->
          <div id="myModal" style="display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
            <div style="background-color: white; margin: 3% auto; padding: 20px; border-radius: 5px; width: 80%; max-width: 800px; position: relative;">
              <!-- Кнопка закрытия -->
              <span id="closePopup" style="position: absolute; right: 15px; top: 10px; font-size: 28px; cursor: pointer; color: #aaa;">&times;</span>

              <!-- Здесь будет содержимое popup -->
              <div id="popupContent">
                <h2>Заказ оборудования</h2>
                <!-- Форма Bitrix24 будет загружаться сюда -->
                <div id="bitrixFormContainer">
                  <script data-b24-form="inline/18/69uzz4" data-skip-moving="true">
                    (function(w, d, u) {
                      var s = d.createElement('script');
                      s.async = true;
                      s.src = u + '?' + (Date.now() / 180000 | 0);
                      var h = d.getElementsByTagName('script')[0];
                      h.parentNode.insertBefore(s, h);
                    })(window, document, 'https://cdn-ru.bitrix24.ru/b34340734/crm/form/loader_18.js');
                  </script>
                </div>
              </div>
            </div>
          </div>

          <script>
            // JavaScript для управления popup
            document.getElementById('openPopup').onclick = function() {
              document.getElementById('myModal').style.display = 'block';
              // Переинициализация формы после открытия
              setTimeout(function() {
                if (typeof window.Bitrix24FormLoader !== 'undefined') {
                  window.Bitrix24FormLoader.reload();
                }
              }, 300);
            };

            document.getElementById('closePopup').onclick = function() {
              document.getElementById('myModal').style.display = 'none';
            };

            // Закрытие по клику вне popup
            window.onclick = function(event) {
              var modal = document.getElementById('myModal');
              if (event.target == modal) {
                modal.style.display = 'none';
              }
            };
          </script>
          <div class="mobile-bottom-wrapper">
            <?php if ($phone): ?>
              <div class="phone"><?php print $phone; ?> <?php print $phone_dop; ?></div>
            <?php endif; ?>
            <?php if ($email): ?>
              <div class="email"><?php print $email; ?></div>
            <?php endif; ?>
            <div class="langs"></div>
            <div class="social-links">
              <a href="/" class="facebook"></a>
              <a href="/" class="insta"></a>
              <a href="/" class="vk"></a>
              <a href="/" class="youtube"></a>
            </div>
          </div>
      </div>
      <?php if ($page['catalog_menu']): ?>
        <div class="catalog-menu-wrapper">
          <?php print render($page['catalog_menu']); ?>
        </div>
      <?php endif; ?>
    <?php endif; ?>

    </div> <!-- //#navigation -->

  </div>
</div> <!-- /.section, /#header -->

<div id="page-wrapper">
  <div id="page"><a id="top"></a>

    <?php if ($page['slider']): ?>
      <div id="slider" class="clearfix">
        <?php print render($page['slider']); ?>
      </div>
    <?php endif; ?>

    <?php if ($page['content_top']): ?>
      <div id="content-top" class="clearfix">
        <?php print render($page['content_top']); ?>
      </div>
    <?php endif; ?>



    <?php if ($is_product_page || $is_category_page): ?>
      <div id="main-wrapper">
        <div id="<?php $is_category_page ? print 'category-main' : print 'product-main' ?>" class="clearfix">

          <a id="main-content"></a>
          <?php if ($breadcrumb): ?>
            <?php print $breadcrumb; ?>
          <?php endif; ?>

          <?php if ($is_category_page): ?>
            <?php print render($title_prefix); ?>
            <?php if ($title): ?><h1 class="title" id="page-title"><?php print $title; ?></h1><?php endif; ?>
            <?php print render($title_suffix); ?>
          <?php endif; ?>

          <div class="section clearfix messages-and-tabs">
            <div class="clearfix">
              <?php print $messages; ?>
            </div>
            <?php if ($tabs): ?><div class="tabs"><?php print render($tabs); ?></div><?php endif; ?>
            <?php print render($page['help']); ?>
            <?php if ($action_links): ?><ul class="action-links"><?php print render($action_links); ?></ul><?php endif; ?>
          </div>

          <div class="content-wrapper">
            <div class="content">
              <?php print render($page['content']); ?>
            </div>
          </div> <!-- /#content -->

        </div> <!-- /#main, /#main-wrapper -->

        <?php if ($page['content_bottom']): ?>
          <div id="content-bottom" class="clearfix">
            <?php print render($page['content_bottom']); ?>
          </div>
        <?php endif; ?>

      </div> <!-- /.main-wrapper -->
    <?php else: ?>
      <div id="main-wrapper">
        <div id="main" class="clearfix">

          <a id="main-content"></a>
          <?php if ($breadcrumb): ?>
            <?php print $breadcrumb; ?>
          <?php endif; ?>

          <?php print render($title_prefix); ?>
          <?php if ($title): ?><h1 class="title" id="page-title"><?php print $title; ?></h1><?php endif; ?>
          <?php print render($title_suffix); ?>

          <div class="section clearfix messages-and-tabs">
            <div class="clearfix">
              <?php print $messages; ?>
            </div>
            <?php if ($tabs): ?><div class="tabs"><?php print render($tabs); ?></div><?php endif; ?>
            <?php print render($page['help']); ?>
            <?php if ($action_links): ?><ul class="action-links"><?php print render($action_links); ?></ul><?php endif; ?>
          </div>

          <div class="content-wrapper">
            <div class="content">
              <?php print render($page['content']); ?>
            </div>
          </div> <!-- /#content -->

        </div> <!-- /#main, /#main-wrapper -->

        <?php if ($page['content_bottom']): ?>
          <div id="content-bottom" class="clearfix">
            <?php print render($page['content_bottom']); ?>
          </div>
        <?php endif; ?>

      </div> <!-- /.main-wrapper -->
    <?php endif; ?>


    <?php if ($page['footer_top']): ?>
      <div id="footer-top">
        <?php print render($page['footer_top']); ?>
      </div>
    <?php endif;
    /* sites/all/themes/skb4/svg/footer_logo.svg */
    ?>

    <div id="footer">
      <div class="bottom-row-wrapper">
        <div class="bottom-row">
          <?php if ($logo): ?>
            <div class="logo-wrapper">
              <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home" id="logo">
                <img src="/sites/all/themes/skb4/logo.svg" alt="<?php print t('Home'); ?>" width="167" />
              </a>
              <span>SKB-4.com - продажа оборудования<br> и запчастей
                из Европы в Россию и страны СНГ</span>
              <span><a href="https://skb-4.ru" target="_blank">SKB-4.ru</a> - поставка и продажа гибких<br>
                муфт немецких брендов Centa и Rexnord</span>
            </div>
          <?php endif; ?>
          <div class="contacts">
            <?php if ($phone): ?>
              <div class="phone"><?php print $phone; ?> <?php print $phone_dop; ?></div>
            <?php endif; ?>
            <?php if ($email): ?>
              <div class="email"><?php print $email; ?></div>
            <?php endif; ?>
            <?php if ($address): ?>
              <div class="address"><?php print $address; ?></div>
            <?php endif; ?>
            <div class="social-links">
              <a href="/" class="facebook"></a>
              <a href="/" class="insta"></a>
              <a href="/" class="vk"></a>
              <a href="/" class="youtube"></a>
            </div>
          </div>
        </div>
      </div>
      <div class="section">

        <?php if ($page['footer']): ?>

          <?php print render($page['footer']); ?>
        <?php endif; ?>
        <?php if ($main_menu): ?>
          <div class="secondary-menu-wrapper">
            <?php /*print $main_menu*/ ?>
            <?php print theme('links__system_secondary_menu', array('links' => $secondary_menu, 'attributes' => array('id' => 'secondary-menu', 'class' => array('links', 'inline', 'clearfix')))); ?>
            <div class="feedback"><a class="autodialog" data-dialog-ajax="true" data-dialog-width="960" href="/ordering">Заказ оборудования</a></div>
          </div> <!-- //#secondary-menu -->
        <?php endif; ?>
      </div>
    </div>

    <?php if ($page['footer_bottom']): ?>
      <div id="footer-bottom">
        <?php print render($page['footer_bottom']); ?>
      </div>
    <?php endif; ?>

  </div>
</div> <!-- /#page, /#page-wrapper -->