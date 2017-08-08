(function() {
	tinymce.create('tinymce.plugins.wp_emojione', {
		
		init : function(ed, url) {
		// Register the command so that it can be invoked by using tinyMCE.activeEditor.execCommand('mceExample');

			ed.addCommand('wp_emojione', function() {
				ed.windowManager.open({
					file : url + '../../emoji.php',
					width : 300,
					height : 245,
					inline : 1
				}, {
					plugin_url : url // 插件绝对路径
				});
			});

			// 在编辑器中添加按钮
			ed.addButton('wp_emojione', {
				title : 'WP-EmojiOne',
				cmd : 'wp_emojione',
				image : 'http://file.yawzhou.com/emojione/img/wp-emoji-icon.png'
				// image : url + '../../icons/1F600.png'
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
					author 	  : '杳无宗',
					authorurl : 'http://yawzhou.com',
					infourl   : 'http://yawzhou.com/emojione/',
					version   : "1.0.1"
			};
		}
	});

	// 注册插件
	tinymce.PluginManager.add('wp_emojione', tinymce.plugins.wp_emojione);
})();


