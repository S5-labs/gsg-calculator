<?php
/**
 * Plugin Name: GrabScanGo Calculator
 * Description: Lobby Market ROI calculator with shortcode [gsg_calculator]. Builder-friendly (Breakdance, Elementor, Gutenberg). Prefilled defaults + typical range hints.
 * Version: 1.2.3
 * Author: GrabScanGo / Triaza
 * License: GPLv2 or later
 */
if (!defined('ABSPATH')) exit;

// Register assets
add_action('init', function () {
  $ver = '1.2.3';
  $url = plugin_dir_url(__FILE__) . 'assets/';
  wp_register_style('gsg-calculator', $url . 'gsg-calculator.css', [], $ver);
  wp_register_script('gsg-calculator', $url . 'gsg-calculator.js', [], $ver, true);
});

// Shortcode
// Define defaults in a way that they can be reused.
$GLOBALS['gsg_calculator_defaults'] = [
    'run_revenue'=>'5500','pct_alcohol'=>'15','pct_fresh'=>'0','pct_shrink'=>'14','pct_tax'=>'9.15',
    'cogs_bev_self'=>'50','cogs_alc_self'=>'40','cogs_fresh_self'=>'40',
    'hourly'=>'20','pct_burden'=>'15','hours_inventory'=>'4','hours_stocking'=>'31','avg_ticket'=>'7','pct_card_self'=>'3',
    'mr'=>'100','da'=>'166',
    'gsg_cogs_bev'=>'25','gsg_cogs_alc'=>'40','gsg_cogs_fresh'=>'40','gsg_card'=>'5.49','gsg_shrink'=>'6','gsg_tech'=>'12.9',
    'rooms'=>'144','pct_occ'=>'65','ppr'=>'1.5','pct_buy'=>'15','avg_ticket_est'=>'7.5',
    'expected_sales'=>'10000',

    // Your brand tokens (baked in; can still be overridden by shortcode)
    'brand_primary'=>'#399cd7',
    'brand_accent'=>'#f6821e',
    'brand_secondary'=>'#7aba42',
    'brand_muted'=>'#F4F6F8',
    'brand_text'=>'#0B1220',
    'font_stack'=>''
];

add_shortcode('gsg_calculator', function ($atts = [], $content = null) {
  // Load assets
  wp_enqueue_style('gsg-calculator');
  wp_enqueue_script('gsg-calculator');

  $a = shortcode_atts($GLOBALS['gsg_calculator_defaults'], $atts, 'gsg_calculator');

  static $instance = 0; $instance++;
  $id = 'gsgcalc_' . $instance . '_' . substr(uniqid('', true), -5);

  // Defaults for JS + server-side values
  $d = [
    'runRevenue'=>(float)$a['run_revenue'], 'pctAlcohol'=>(float)$a['pct_alcohol'], 'pctFresh'=>(float)$a['pct_fresh'],
    'pctShrink'=>(float)$a['pct_shrink'], 'pctTax'=>(float)$a['pct_tax'],
    'cogsBevSelf'=>(float)$a['cogs_bev_self'],'cogsAlcSelf'=>(float)$a['cogs_alc_self'],'cogsFreshSelf'=>(float)$a['cogs_fresh_self'],
    'hourly'=>(float)$a['hourly'],'pctBurden'=>(float)$a['pct_burden'],'hoursInventory'=>(float)$a['hours_inventory'],
    'hoursStocking'=>(float)$a['hours_stocking'],'avgTicket'=>(float)$a['avg_ticket'],'pctCardSelf'=>(float)$a['pct_card_self'],
    'mr'=>(float)$a['mr'],'da'=>(float)$a['da'],
    'gsgCogsBev'=>(float)$a['gsg_cogs_bev'],'gsgCogsAlc'=>(float)$a['gsg_cogs_alc'],'gsgCogsFresh'=>(float)$a['gsg_cogs_fresh'],
    'gsgCard'=>(float)$a['gsg_card'],'gsgShrink'=>(float)$a['gsg_shrink'],'gsgTechMgmt'=>(float)$a['gsg_tech'],
    'rooms'=>(float)$a['rooms'],'pctOcc'=>(float)$a['pct_occ'],'ppr'=>(float)$a['ppr'],'pctBuy'=>(float)$a['pct_buy'],
    'avgTicketEst'=>(float)$a['avg_ticket_est'],'expectedSalesInput'=>(float)$a['expected_sales']
  ];
  $brand = [
    'primary'=>sanitize_text_field($a['brand_primary']),
    'accent'=>sanitize_text_field($a['brand_accent']),
    'secondary'=>sanitize_text_field($a['brand_secondary']),
    'muted'=>sanitize_text_field($a['brand_muted']),
    'text'=>sanitize_text_field($a['brand_text']),
    'font'=>sanitize_text_field($a['font_stack']),
  ];
  $v = $d; // for server-side value="" attributes

  // Data for JS
  $config = ['id'=>$id,'defaults'=>$d,'brand'=>$brand, 'nonce'=>wp_create_nonce('gsg-calculator-nonce')];
  wp_localize_script('gsg-calculator', 'gsg_calculator_config_' . $instance, $config);

  // Inline CSS variables from brand tokens
  $style_vars = '--gsg-primary: ' . $brand['primary'] . '; '
              . '--gsg-accent: ' . $brand['accent'] . '; '
              . '--gsg-muted: ' . $brand['muted'] . '; '
              . '--gsg-text: ' . $brand['text'] . '; '
              . '--gsg-secondary: ' . $brand['secondary'] . '; ';
  if (!empty($brand['font'])) {
    $style_vars .= '--gsg-font: ' . $brand['font'] . '; ';
  }

  ob_start();
  include(plugin_dir_path(__FILE__) . 'templates/calculator-form.php');
  return ob_get_clean();
});

// Admin settings page
if (is_admin()) {
    require_once plugin_dir_path(__FILE__) . 'admin/settings-page.php';
}