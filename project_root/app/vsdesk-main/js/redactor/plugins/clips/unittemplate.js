(function($)
{
    $.Redactor.prototype.unittemplate = function()
    {
        return {
            init: function()
            {
                var items = [
                    ['№', '{id}'],
                    ['Название', '{name}'],
                    ['Статус', '{status}'],
                    ['Тип', '{type}'],
                    ['Имя заказчика', '{username}'],
                    ['Подразделение', '{department}'],
                    ['Дата ввода в эксплуатацию', '{startexpdate}'],
                    ['Дата вывода из эксплуатации', '{endexpdate}'],
                    ['Инвентарный №', '{inventory}'],
                    ['Компания', '{company}'],
                    ['Местоположение', '{location}'],
                    ['Стоимость', '{cost}'],
                    ['Дата', '{date}'],
                    ['QR code', '{QRCODE}'],
                    ['Активы', '{assets}'],

                ];

                this.unittemplate.template = $('<ul id="redactor-modal-list">');

                for (var i = 0; i < items.length; i++)
                {
                    var li = $('<li>');
                    var a = $('<a href="#" class="redactor-clips-link">').text(items[i][0]);
                    var div = $('<div class="redactor-clips">').hide().html(items[i][1]);

                    li.append(a);
                    li.append(div);
                    this.unittemplate.template.append(li);
                }

                this.modal.addTemplate('clips', '<div class="modal-section">' + this.utils.getOuterHtml(this.unittemplate.template) + '</div>');

                var button = this.button.add('clips', 'Шаблоны');

                this.button.addCallback(button, this.unittemplate.show);

            },
            show: function()
            {
                this.modal.load('clips', 'Выберите шаблон', 500);

                $('#redactor-modal-list').find('.redactor-clips-link').each($.proxy(this.unittemplate.load, this));

                this.modal.show();
            },
            load: function(i,s)
            {
                $(s).on('click', $.proxy(function(e)
                {
                    e.preventDefault();
                    this.unittemplate.insert($(s).next().html());

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

