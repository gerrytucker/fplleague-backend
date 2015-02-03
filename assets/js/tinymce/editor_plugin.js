(function() {

	tinymce.create('tinymce.plugins.FPLLeague', {
		init: function(ed, url) {

			ed.addCommand('mceFPLLeague', function() {
				ed.windowManager.open({
					file: url + '/window.php',
					width: 500 + ed.getLang('FPLLeague.delta_width', 0),
					height: 210 + ed.getLang('FPLLeague.delta_height', 0),
					inline: 1
				}, {
					plugin_url: url
				});

			});

			// Register button
			ed.addButton('FPLLeague', {
				title: 'Flegg Pool League',
				cmd: 'mceFPLLeague',
				image: url + '/fplleague.png'
			});

			// Add a node change handler, selects the button in the UI when an image is selected
			ed.onNodeChange.add(function(ed, cm, n) {
				cm.setActive('FPLLeague', n.nodename == 'IMG');
			});

		},

		createControl: function(n, cm) {
			return null;
		},

		getInfo: function() {
			return {
				longname: 'FPLLeague',
				author: 'Gerry Tucker',
				authorurl: 'http://gerrytucker.co.uk/',
				infourl: 'http://gerrytucker.co.uk/',
				version: '1.0.0'
			};
		}

	});

	// Register plugin
	tinymce.PluginManager.add('FPLLeague', tinymce.plugins.FPLLeague);
	

})();