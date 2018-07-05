<?php
/**
 * Plugin Name: WP-Emojione
 * Plugin URI: https://github.com/YaweeZhou/WP-Emojione
 * Description: Add vivid emojione expression to your wordpress.
 * Version: 1.1.3
 * Author: Yawee
 * Author URI: https://yaw.ee
 * Time: 2018.3.10 09:35
 * License: GPLv2
 * Text Domain: wp_emojione
 * Domain Path: /languages/
 */

/*  Copyright 2018 Yawee (email : zhou@yaw.ee)

	This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

defined('ABSPATH') or die("这里没有什么可看的！或者可以到我博客(yaw.ee)喝杯茶吖！");

#------------------------------------------------------------------------------
# 插件初始化
#------------------------------------------------------------------------------
load_plugin_textdomain('wp_emojione', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
$wp_emojione_plugin_url = plugin_dir_url( __FILE__ );
$wp_emojione_ver = "1.1.3";
$wp_emojione_setting_opt = get_option('wp_emojione_setting_opt');

#------------------------------------------------------------------------------
# 版本检查
#------------------------------------------------------------------------------
add_action('plugins_loaded', 'wp_emojione_check_ver');

function wp_emojione_check_ver(){
	global $wp_emojione_ver;
	if ($wp_emojione_ver != get_option('wp_emojione_checkver_stamp')) {
		wp_emojione_setting_init();
		add_action('admin_notices', 'wp_emojione_admin_updated_notice');
	}
}

#------------------------------------------------------------------------------
# 设置初始化
#------------------------------------------------------------------------------
function wp_emojione_setting_init(){
	global $wp_emojione_ver;

	$wp_emojione_setting_opt['wp_emojione_button_line'] = "1";

	update_option('wp_emojione_setting_opt', $wp_emojione_setting_opt);
	update_option('wp_emojione_checkver_stamp', $wp_emojione_ver);
}

#------------------------------------------------------------------------------
# 数据表更新消息
#------------------------------------------------------------------------------
function wp_emojione_admin_updated_notice(){
    echo '<div id="message" class="updated"><p>'.__("WP-Emojione已经成功地创建了一个新的数据库表。（如果你更新，插件的设置被重置为默认值。）<br />现在就进入设置面板<strong><a href=\"options-general.php?page=wp_emojione-button-options\">配置你的WP-Emojione</a></strong>吧。","wp_emojione").'</p></div>';
}

#------------------------------------------------------------------------------
# 插件底部
#------------------------------------------------------------------------------
function wp_emojione_admin_footer(){
	$wp_emojione_plugin_data = get_plugin_data(__FILE__);
	printf('%1$s by %2$s<br />', $wp_emojione_plugin_data['Title'].' '.$wp_emojione_plugin_data['Version'], $wp_emojione_plugin_data['Author'] . '<br><small>Emoji provided free by <a href="http://emojione.com" target="_blank">Emoji One</a>.</small>');
}

#------------------------------------------------------------------------------
# 在选项菜单添加链接
#------------------------------------------------------------------------------
add_action('admin_menu', 'wp_emojione_register_menu_item');

function wp_emojione_register_menu_item() {
	$wp_emojione_adv_page_hook = add_options_page('WP-Emojione Options', __("WP-Emojione","wp_emojione"), 'manage_options', 'wp_emojione-button-options', 'wp_emojione_options_panel');
	if ($wp_emojione_adv_page_hook != null) {
		$wp_emojione_adv_page_hook = '-'.$wp_emojione_adv_page_hook;
	}
}

#------------------------------------------------------------------------------
# 设置面板
#------------------------------------------------------------------------------
function wp_emojione_options_panel(){
	global $wp_emojione_plugin_url, $wp_emojione_ver;
	if (!function_exists('current_user_can') || !current_user_can('manage_options')) {
		die(__('安全性错误！'));
	}
	add_action('in_admin_footer', 'wp_emojione_admin_footer');

	if (isset($_POST['wp_emojione_Setting_Submit']) && $_POST['wp_emojione_hidden_value'] == "true") {
		echo "<div id='setting-error-settings_updated' class='updated fade'><p><strong>".__("设置已保存！","wp_emojione")."</strong></p></div>";
	} elseif (isset($_POST['wp_emojione_ADV_Reset']) && $_POST['wp_emojione_adv_reset']='true') {
		echo "<div id='setting-error-settings_updated' class='updated fade'><p><strong>".__("所有设置都重置. 请 <a href=\"options-general.php?page=wp_emojione-button-options\">重新载入页面</a>。","wp_emojione")."</strong></p></div>";
	}

	$wp_emojione_setting_opt = get_option('wp_emojione_setting_opt');

	if ((!is_array($wp_emojione_setting_opt) || $wp_emojione_ver != get_option('wp_emojione_checkver_stamp')) && !isset($_POST['SHTB_ADV_Reset'])) {
		echo "<div id='setting-error-settings_updated' class='updated settings-error'><p><strong>".__("错误：缺少数据库表，安装或更新时，该插件可能无法创建数据库表，请重新安装！","wp_emojione")."</strong></p></div>";
	}

	// 更新设置选项
	if (isset($_POST['wp_emojione_Setting_Submit']) && $_POST['wp_emojione_hidden_value'] == "true") {
		$wp_emojione_setting_opt['wp_emojione_button_line'] = $_POST['wp_emojione_button_line'];

		update_option('wp_emojione_setting_opt', $wp_emojione_setting_opt);
	}

	// 重置所有设置
	if (isset($_POST['wp_emojione_ADV_Reset']) && $_POST['wp_emojione_adv_reset']='true') {
		include_once('uninstall.php');
		wp_emojione_setting_init();
	}


	?> 
	<div class="wrap">
	<h2><?php _e("WP-Emojione选项", "wp_emojione")?></h2>
	<form method="post" action="<?php echo($_SERVER['REQUEST_URI']);?>">
	<input type="hidden" name="wp_emojione_hidden_value" value="true" />
	<h3><?php _e("1. 基本设置", 'wp_emojione') ?></h3> 
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><strong><?php _e('把Emojione按钮放在', 'wp_emojione') ?></strong></th> 
				<td>
				<?php _e("TinyMCE编辑器工具栏的", "wp_emojione") ?>
					<select name="wp_emojione_button_line">
						<option value="1" <?php if ($wp_emojione_setting_opt['wp_emojione_button_line'] == "1") {echo 'selected="selected"';} ?>><?php _e("第一行", "wp_emojione") ?></option>
						<option value="2" <?php if ($wp_emojione_setting_opt['wp_emojione_button_line'] == "2") {echo 'selected="selected"';} ?>><?php _e("第二行", "wp_emojione") ?></option>
						<option value="3" <?php if ($wp_emojione_setting_opt['wp_emojione_button_line'] == "3") {echo 'selected="selected"';} ?>><?php _e("第三行", "wp_emojione") ?></option>
					</select> 
					<p><small><?php _e("把WP-Emojione按钮放在你想要的编辑器工具栏的行。", "wp_emojione") ?></small></p>
				</td>
			</tr>
		</table>
		<p class="submit">
		<input type="submit" name="wp_emojione_Setting_Submit" value="<?php _e('保存更改', 'wp_emojione') ?>" />
		</p>
	</form>
	<h3><?php _e("2. WP-Emojione表情预览", 'wp_emojione') ?></h3>
	<p>
	<iframe width="300" height="350" scrolling="no" src="<?php echo $wp_emojione_plugin_url; ?>emojione.html"></iframe>
	</p>
	<h3><?php _e("3. 请我喝咖啡", 'wp_emojione') ?></h3>
	<p>
	<iframe width="320" height="210" scrolling="no" src="<?php echo $wp_emojione_plugin_url; ?>reward.html"></iframe>
	</p>
	<h3><?php _e("4. 你的系统信息", 'wp_emojione') ?></h3>
	<p>
	<?php _e('服务器操作系统：', 'wp_emojione') ?> <?php echo php_uname('s').' '.php_uname('r'); ?><br />
	<?php _e('PHP版本：', 'wp_emojione') ?> <?php echo phpversion(); ?><br />
	<?php _e('WordPress版本：', 'wp_emojione') ?> <?php bloginfo("version"); ?><br />
	<?php _e('站点地址：', 'wp_emojione') ?> <?php bloginfo("url"); ?><br />
	<?php _e('WordPress地址：', 'wp_emojione') ?> <?php bloginfo("wpurl"); ?><br />
	<?php _e('WordPress语言：', 'wp_emojione') ?> <?php bloginfo("language"); ?><br />
	<?php _e('WordPress的字符集：', 'wp_emojione') ?> <?php bloginfo("charset"); ?><br />
	<?php _e('WordPress主题：', 'wp_emojione') ?> <?php $wp_emojione_theme = wp_get_theme(); echo $wp_emojione_theme['Name'].' '.$wp_emojione_theme['Version']; ?><br />
	<?php _e('WP-Emojione版本：', 'wp_emojione') ?> <?php $wp_emojione_plugin_data = get_plugin_data(__FILE__); echo $wp_emojione_plugin_data['Version']; ?><br />
	<?php _e('WP-Emojione地址：', 'wp_emojione') ?> <?php echo $wp_emojione_plugin_url; ?><br />
	<?php _e('你的浏览器信息：', 'wp_emojione') ?> <?php echo $_SERVER['HTTP_USER_AGENT']; ?>
	</p>
	<h3><?php _e("5. 联系我", 'wp_emojione') ?></h3>
	<p>
	<?php _e("Yawee", 'wp_emojione') ?> [ <a href="https://yaw.ee" target="_blank">https://yaw.ee</a> ] 
	</p>
	<p>
	<?php _e("如果你在使用过程中遇到什么问题, ", 'wp_emojione') ?><?php _e('请 <a href="https://yaw.ee/guestbook" target="_blank">给我留言</a>！', 'wp_emojione') ?>
	</p>
	</div>
<?php } 

#------------------------------------------------------------------------------
# 插入WP-Emojione图标到tinyMCE编辑器
#------------------------------------------------------------------------------
function wp_emojione_addbuttons() {
	// 如果当前用户没有权限，请不要费心做这件事
	if ( !current_user_can('edit_posts') && !current_user_can('edit_pages') ) return;
	// 仅在富编辑模式下添加
	if ( get_user_option('rich_editing') == 'true') {
	// 换一种方式添加按钮
		add_filter("mce_external_plugins", 'add_wp_emojione_tinymce_plugin');
		$wp_emojione_setting_opt = get_option('wp_emojione_setting_opt');
		$button_line = $wp_emojione_setting_opt['wp_emojione_button_line'];
		if ($button_line == "2" || $button_line == "3" || $button_line == "4") {
			add_filter('mce_buttons_'.$button_line, 'register_wp_emojione_button');
		} else {
			add_filter('mce_buttons', 'register_wp_emojione_button');
		}
	}
}

#------------------------------------------------------------------------------
# 登记WP-Emojione按钮
#------------------------------------------------------------------------------
function register_wp_emojione_button($buttons) {
	array_push($buttons, "separator", "wp_emojione");
	return $buttons;
}

#------------------------------------------------------------------------------
# 加载editor_plugin.js
#------------------------------------------------------------------------------
function add_wp_emojione_tinymce_plugin($plugin_array) {
	global $wp_emojione_plugin_url;
	$wp_emojione_setting_opt = get_option('wp_emojione_setting_opt');
	$plugin_array['wp_emojione'] = $wp_emojione_plugin_url.'/js/editor_plugin.js';
	return $plugin_array;
}

#------------------------------------------------------------------------------
# 更改版本
#------------------------------------------------------------------------------
function wp_emojione_change_tinymce_version($version) {
	return ++$version;
}

#------------------------------------------------------------------------------
# WordPress filter and action
#------------------------------------------------------------------------------
add_filter('tiny_mce_version', 'wp_emojione_change_tinymce_version');
add_action('init', 'wp_emojione_addbuttons');
?>
