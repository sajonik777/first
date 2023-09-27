(function ($) {
	$.Redactor.prototype.iconic = function () {
		return {
			init: function () {
				var icons = {
					'format':         '<i class="fa-solid fa-paragraph fa-xl"></i>',
					'bold':           '<i class="fa-solid fa-bold fa-xl"></i>',
					'italic':         '<i class="fa-solid fa-italic fa-xl"></i>',
					'deleted':        '<i class="fa-solid fa-strikethrough fa-xl"></i>',
					'lists':          '<i class="fa-solid fa-list-ul fa-xl"></i>',
					'link':           '<i class="fa-solid fa-link fa-xl"></i>',
					'horizontalrule': '<i class="fa-solid fa-minus fa-xl"></i>',
					'image':          '<i class="fa-solid fa-image fa-xl"></i>',
					'file':           '<i class="fa-solid fa-paperclip fa-xl"></i>',
					'clips':          '<i class="fa-solid fa-bookmark fa-xl"></i>',
					'video':          '<i class="fa-solid fa-video fa-xl"></i>',
					'table':          '<i class="fa-solid fa-table-cells-large fa-xl"></i>',
					'fullscreen':     '<i class="fa-solid fa-up-right-and-down-left-from-center fa-xl"></i>',
					'html':           '<i class="fa-solid fa-code fa-xl"></i>',
					'alignment':      '<i class="fa-solid fa-align-left fa-xl"></i>',
				};

				$.each(this.button.all(), $.proxy(function (i, s) {
					var key = $(s).attr('rel');

					if (typeof icons[key] !== 'undefined') {
						var icon   = icons[key];
						var button = this.button.get(key);
						this.button.setIcon(button, icon);
					}

				}, this));
			}
		};
	};

})(jQuery);