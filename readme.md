

Использовал VueJS.

Для http запросов использовал vue-resource.

В миграции для создания таблицы коментариев сразу вставляю дефолтную запись для корня дерева.

INSERT INTO comments (left, right, level, message) VALUES (1, 2, 0, 'root');
