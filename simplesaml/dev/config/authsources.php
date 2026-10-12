<?php
$idp_host = getenv('SAML_IDP_PRIMARY_HOST');

$config = [
  // This is a authentication source which handles admin authentication.
  'admin' => [
    'core:AdminPassword',
  ],
  // This the the auth source that handles the drupal authentication
  'drupal-userpass' => array(
    'drupalauth:External',
    'drupalroot' => '/var/www/html/web',
    'debug' => true,
    'drupal_logout_url' => 'https://' .  $idp_host  . '/user/logout',
    'drupal_login_url' => 'https://' .  $idp_host  . '/user/login',
    'attributes' => array(
        array('field_name' => 'uid', 'attribute_name' => 'uid'),
        array('field_name' => 'roles', 'attribute_name' => 'roles', 'field_property' => 'target_id'),
        array('field_name' => 'name', 'attribute_name' => 'cn'),
        array('field_name' => 'mail', 'attribute_name' => 'mail'),
        array('field_name' => 'field_first_name', 'attribute_name' => 'givenName'),
        array('field_name' => 'field_last_name', 'attribute_name' => 'sn'),
        array('field_name' => 'field_organization', 'attribute_name' => 'ou', 'field_property' => 'target_id'),
    ),
  ),
  // 'ssp-userpass' => array(
  //    'drupalauth:UserPass',
  //   //  'drupalroot' => '/var/www/html/[site root]/web',
  //    'drupalroot' => '/var/www/html/web',
  //    'debug' => true,
  //    'attributes' => array(
  //         array('field_name' => 'uid', 'attribute_name' => 'uid'),
  //         array('field_name' => 'roles', 'attribute_name' => 'roles', 'field_property' => 'target_id'),
  //         array('field_name' => 'name', 'attribute_name' => 'cn'),
  //         array('field_name' => 'mail', 'attribute_name' => 'mail'),
  //         array('field_name' => 'field_first_name', 'attribute_name' => 'givenName'),
  //         array('field_name' => 'field_last_name', 'attribute_name' => 'sn'),
  //         array('field_name' => 'field_organization', 'attribute_name' => 'ou', 'field_property' => 'target_id'),
  //     ),
  // ),
];

// ensure that drupal auth is what is used by default
$config['default-sp'] = &$config['drupal-userpass'];
