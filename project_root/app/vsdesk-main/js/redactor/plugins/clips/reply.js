(function($)
{
	$.Redactor.prototype.reply = function()
	{
		return {
			langs: {
				en: {
					"reply": "Quote"
				},
				ru: {
					"reply": "Цитировать"
				}
			},
			init: function()
			{
				var button = this.button.add('reply', this.lang.get('reply'));
                this.button.setIcon(button, '<i class="fa-solid fa-quote-right fa-xl"></i>');
				this.button.addCallback(button, this.reply.show);

			},
			show: function()
			{
                var text = $('.mailbox-read-message').html();
				if ($("#redactor-uuid-1").length) {
					$("#redactor-uuid-1").append("<blockquote>"+text+"</blockquote>");
				} else {
					$("#redactor-uuid-0").append("<blockquote>"+text+"</blockquote>");
				}
                $("textarea").append("<blockquote>"+text+"</blockquote>");

			},
		};
	};
})(jQuery);

