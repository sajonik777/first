(function($)
{
    $.Redactor.prototype.crm = function()
    {
        return {
            init: function()
            {
                var items = [
                    ['Название сделки', '{name}'],
                    ['Этап сделки', '{status}'],
                    ['Имя заказчика', '{contact}'],
                    ['Имя ответственного', '{manager}'],
                ];

                this.crm.template = $('<ul id="redactor-modal-list">');

                for (var i = 0; i < items.length; i++)
                {
                    var li = $('<li>');
                    var a = $('<a href="#" class="redactor-clips-link">').text(items[i][0]);
                    var div = $('<div class="redactor-clips">').hide().html(items[i][1]);

                    li.append(a);
                    li.append(div);
                    this.crm.template.append(li);
                }

                this.modal.addTemplate('clips', '<div class="modal-section">' + this.utils.getOuterHtml(this.crm.template) + '</div>');

                var button = this.button.add('clips', 'Шаблоны');
                this.button.setIcon(button, '<i class="re-icon-clips"></i>');
                this.button.addCallback(button, this.crm.show);

            },
            show: function()
            {
                this.modal.load('clips', 'Выберите шаблон', 500);

                $('#redactor-modal-list').find('.redactor-clips-link').each($.proxy(this.crm.load, this));

                this.modal.show();
            },
            load: function(i,s)
            {
                $(s).on('click', $.proxy(function(e)
                {
                    e.preventDefault();
                    this.crm.insert($(s).next().html());

                }, this));
            },
            insert: function(html)
            {
                this.buffer.set();
                this.air.collapsedEnd();
                this.insert.html(html);
                this.modal.close();
            }
        };
    };
})(jQuery);



