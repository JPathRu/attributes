# Атрибуты

![Last Update](https://img.shields.io/badge/last_update-2019.08.13-28A5F5.svg?style=for-the-badge)
![Version](https://img.shields.io/badge/VERSION-1.2.4-0366d6.svg?style=for-the-badge)
![Joomla](https://img.shields.io/badge/joomla-3.7+-1A3867.svg?style=for-the-badge)
![Php](https://img.shields.io/badge/php-5.6+-8892BF.svg?style=for-the-badge)

Компонент реализует дополнительные атрибуты для следующих элементов:

- системые параметры,
- меню,
- пользователи,
- контакты,
- материалы,
- категории,
- модули,
- плагины,
- поля,
- метки.

Значения атрибутов хранятся в параметрах соответствующего элемента, поле `params` (для материалов поле `attribs`).

К каждому указанному системному имени атрибута добавляется префикс `attrs_`. Вы можете получить значение атрибута стандартным нижеуказанным способом.

## Использование

### Шорткод, для использования в контент-редакторе

```
{attrs;dest;id;attrName}
```

- `attrs` - зарезервированное слово
- `dest` - принадлежность к определённому типу записи, одно из значений: system, menu, users, contacts, articles, categories, modules, plugins
- `id` - ID записи соответствующей принадлежности, указывать `0` для system
- `attrName` - системное имя атрибута

**Важно**: Шорткоды, ссылающиеся на неопубликованные атрибуты, игнорируются. Изображения выводятся без разметки, только путь. Массивы выводятся как список значений через запятую.

**Пример**: Вы создали для материала с ID = 5 атрибут с именем test, чтобы получить его значение, вставьте в редактор следующую строку: `{attrs;articles;5;test}`.

### Через хелпер компонета

Рекомендуемый метод, поскольку производится проверка состояния атрибута (опубликован / неопубликован) и нет необходимости указывать префикс `attrs_`.
Пропустите третий параметр, чтобы получить значение атрибута из конфигурации системы.

```php
/*
AttrsHelper::ATTR_DEST_SYSTEM = 'system'
AttrsHelper::ATTR_DEST_MENU = 'menu'
AttrsHelper::ATTR_DEST_USERS = 'users'
AttrsHelper::ATTR_DEST_CONTACTS = 'contacts'
AttrsHelper::ATTR_DEST_ARTICLES = 'articles'
AttrsHelper::ATTR_DEST_CATEGORIES = 'categories'
AttrsHelper::ATTR_DEST_MODULES = 'modules'
AttrsHelper::ATTR_DEST_PLUGINS = 'plugins'
AttrsHelper::ATTR_DEST_FIELDS = 'fields'
AttrsHelper::ATTR_DEST_TAGS = 'tags'
*/

JLoader::register('AttrsHelper', JPATH_ADMINISTRATOR . '/components/com_attrs/helpers/attrs.php');
$attrValue = AttrsHelper::getAttr($attrName, AttrsHelper::ATTR_DEST_ARTICLES, $article->id);
```

### Без хелпера (не рекомендуется)

```php
// default
$attrValue = $item->params->get("arrts_$attrName", '');

// for system
$attrValue = Joomla\CMS\Factory::getConfig->get("arrts_$attrName", '');

// for articles
$attribs = json_decode($article->attribs, true);
$attrValue = $attribs["arrts_$attrName"];

```
