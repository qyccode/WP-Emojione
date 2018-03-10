(function() {
	tinymce.create('tinymce.plugins.wp_emojione', {
		
		init : function(ed, url) {
		// Register the command so that it can be invoked by using tinyMCE.activeEditor.execCommand('mceExample');
			if (document.body.clientWidth < 640) {
				ed.addCommand('wp_emojione', function() {
					ed.windowManager.open({
						file : url + '../../emoji.php',
						width : 300,
						height : 313,
						inline : 1
					}, {
						plugin_url : url // 插件绝对路径
					});
				});
			}else{
				ed.addCommand('wp_emojione', function() {
					ed.windowManager.open({
						file : url + '../../emoji.php',
						width : 543,
						height : 356,
						inline : 1
					}, {
						plugin_url : url // 插件绝对路径
					});
				});
			}

			// 在编辑器中添加按钮
			ed.addButton('wp_emojione', {
				title : 'WP-Emojione',
				cmd : 'wp_emojione',
				image : url + '../../icons/1f642.png'
			});
			
			// 添加节点更改处理程序，在选择图像时在UI中选择按钮。
			ed.onNodeChange.add(function(ed, cm, n) {
				cm.setActive('wp-emoji_one', n.nodeName == 'IMG');
			});
		},
		createControl : function(n, cm) {
			return null;
		},
		getInfo : function() {
			return {
					longname  : 'wp-emoji_one',
					author 	  : 'Yaw Zhou',
					authorurl : 'https://www.yawzhou.com',
					infourl   : 'https://www.yawzhou.com/emojione/',
					version   : "1.1.3"
			};
		}
	});

	// 注册插件
	tinymce.PluginManager.add('wp_emojione', tinymce.plugins.wp_emojione);
})();


