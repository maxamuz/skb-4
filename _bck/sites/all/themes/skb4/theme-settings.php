<?php

/**
 * @file
 * Theme setting callbacks for the garland theme.
 */

/**
 * Implements hook_form_FORM_ID_alter().
 *
 * @param $form
 *   The form.
 * @param $form_state
 *   The form state.
 */
function skb4_form_system_theme_settings_alter(&$form, &$form_state) {

  $form['skb4_phone'] = array(
    '#type' => 'textfield',
    '#title' => t('Phone number'),    
    '#default_value' => theme_get_setting('skb4_phone', 'skb4'),
    '#size' => 14,
  );

  $form['skb4_phone_dop'] = array(
    '#type' => 'textfield',
    '#title' => t('Phone number dop'),    
    '#default_value' => theme_get_setting('skb4_phone_dop', 'skb4'),
    '#size' => 14,
  );

  $form['skb4_address'] = array(
    '#type' => 'textfield',
    '#title' => t('Address'),    
    '#default_value' => theme_get_setting('skb4_address', 'skb4'),
  );

  $form['skb4_email'] = array(
    '#type' => 'textfield',
    '#title' => t('E-mail'),    
    '#default_value' => theme_get_setting('skb4_email', 'skb4'),
  ); 

  $form['skb4_work_time'] = array(
    '#type' => 'textfield',
    '#title' => 'Время работы',
    '#default_value' => theme_get_setting('skb4_work_time', 'skb4'),
  );
}
