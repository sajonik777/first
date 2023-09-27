(function($)
{
    $.Redactor.prototype.messages = function()
    {
        return {
            init: function()
            {
                var items = [
                    ['№ заявки', '{id}'],
                    ['Название заявки', '{name}'],
                    ['Статус заявки', '{status}'],
                    ['Имя заказчика', '{fullname}'],
                    ['Телефон заказчика', '{phone}'],
                    ['Подразделение', '{department}'],
                    ['Должность', '{position}'],
                    ['Наблюдатели', '{watchers}'],
                    ['Имя исполнителя', '{manager_name}'],
                    ['Телефон исполнителя', '{manager_phone}'],
                    ['Внутренний телефон исполнителя', '{manager_intphone}'],
                    ['E-mail исполнителя', '{manager_email}'],
                    ['Категория заявки', '{category}'],
                    ['Приоритет заявки', '{priority}'],
                    ['Дата создания заявки', '{created}'],
                    ['Комментарии', '{comment_message}'],
                    ['Конфигурационная единица', '{unit}'],
                    ['Конфигурационная единица', '{unit}'],
                    ['Начало работ (факт)', '{fStartTime}'],
                    ['Окончание работ (план)', '{EndTime}'],
                    ['Окончание работ (факт)', '{fEndTime}'],
                    ['Cервис', '{service_name}'],
                    ['Компания', '{company}'],
                    ['Адрес', '{address}'],
                    ['Содержание', '{content}'],
                    ['URL', '{url}'],
                    ['Оценка', '{voting}'],
                    ['Открыть заявку повторно', '{reopen}'],

                ];

                this.messages.template = $('<ul id="redactor-modal-list">');

                for (var i = 0; i < items.length; i++)
                {
                    var li = $('<li>');
                    var a = $('<a href="#" class="redactor-clips-link">').text(items[i][0]);
                    var div = $('<div class="redactor-clips">').hide().html(items[i][1]);

                    li.append(a);
                    li.append(div);
                    this.messages.template.append(li);
                }

                this.modal.addTemplate('clips', '<div class="modal-section">' + this.utils.getOuterHtml(this.messages.template) + '</div>');

                var button = this.button.add('clips', 'Шаблоны');
                this.button.setIcon(button, '<i class="re-icon-clips"></i>');
                this.button.addCallback(button, this.messages.show);

            },
            show: function()
            {
                this.modal.load('clips', 'Выберите шаблон', 500);

                $('#redactor-modal-list').find('.redactor-clips-link').each($.proxy(this.messages.load, this));

                this.modal.show();
            },
            load: function(i,s)
            {
                $(s).on('click', $.proxy(function(e)
                {
                    e.preventDefault();
                    this.messages.insert($(s).next().html());

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
