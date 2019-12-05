# Атрибуты

![Last Update](https://img.shields.io/badge/last_update-2019.12.05-28A5F5.svg?style=for-the-badge)
![Version](https://img.shields.io/badge/VERSION-1.3.1-0366d6.svg?style=for-the-badge)
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

Реализуются следующие типы атрибутов:

- текст (`type="text"`),
- многострочный текст (`type="textarea"`),
- редактор (`type="editor"`),
- список (`type="list"`),
- изображение (`type="media"`).

Тип **список** поддерживает опцию множественного выбора значений.

Тип **многострочный текст** подерживает стандартные типы фильтрации контента Joomla: html, raw и другие.

ВАЖНО! Типы **многострочный текст** и **редактор** не рекомендуется применять для системных параметров: при восстановлении сайта посредством Akeeba Backup значения параметров могут быть утеряны.

Значения атрибутов хранятся в параметрах соответствующего элемента, поле `params` (для материалов поле `attribs`).

К каждому указанному системному имени атрибута добавляется префикс `attrs_`. Вы можете получить значение атрибута стандартным нижеуказанным способом.

Для каждого атрибута имеется возможность указания индивидуального макета вывода. Макеты создаются в папке /templates/{ваш_шаблон}/html/layouts/com_attrs/ и назначаются каждому атрибуту отдельно. Данные атрибута внутри макета содержатся в структуре `$displayData`. Макеты по умолчанию не содержит разметки.

## Использование

### Шорткод, для использования в контент-редакторе

```text
{attrs;dest;id;attrName[;layout]}
```

- `attrs` - зарезервированное слово
- `dest` - принадлежность к определённому типу записи, одно из значений: system, menu, users, contacts, articles, categories, modules, plugins
- `id` - ID записи соответствующей принадлежности, указывать `0` для system
- `attrName` - системное имя атрибута
- `layout` - макет вывода (необязательный параметр), при указании значения **0** или **false** назначенный макет будет проигнорирован и значение атрибута будет выведено без шаблонизации; формат указания макета: `template:layoutname`, где _template_ – основной шаблон сайта, в котором содержится переопределение (укажите нижнее подчёркивание для указания шаблона по умолчанию), _layoutname_ – имя файла переопределённого макета, без расширения; при указании несуществующего макета значение атрибута выведено не будет

**Важно**: Шорткоды, ссылающиеся на неопубликованные атрибуты, игнорируются. Изображения выводятся без разметки, только путь. Массивы выводятся как список значений через запятую.

**Пример**: Вы создали для материала с ID = 5 атрибут с именем test, чтобы получить его значение, вставьте в редактор следующую строку: `{attrs;articles;5;test}`.
Если вы хотите вывести значение атрибута с указанием конкретного переопределенного макета, созданного с именем mytemplate в основном шаблоне сайта protostar, вставьте в редактор следующую строку: `{attrs;articles;5;test;protostar:mytemplate}`.

### Через хелпер компонета

Рекомендуемый метод, поскольку производится проверка состояния атрибута (опубликован / неопубликован) и нет необходимости указывать префикс `attrs_`.
Не указывайте третий параметр или укажите его равным нулю, чтобы получить значение атрибута из конфигурации системы.

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

// вывод с макетом, указанным в параметрах атрибута
$attrValue = AttrsHelper::getAttr($attrName, AttrsHelper::ATTR_DEST_ARTICLES, $article->id);

// вывод с макетом, заданным приоритетно
// будет загружен макет атрибута /templates/protostar/html/layouts/com_attrs/mytemplate.php
$attrValue = AttrsHelper::getAttr($attrName, AttrsHelper::ATTR_DEST_ARTICLES, $article->id, 'protostar:mytemplate');
```

### Без хелпера (не рекомендуется)

Этот метод не поддерживает шаблонизацию атрибутов.

```php
// default
$attrValue = $item->params->get("arrts_$attrName", '');

// for system
$attrValue = Joomla\CMS\Factory::getConfig->get("arrts_$attrName", '');

// for articles
$attribs = json_decode($article->attribs, true);
$attrValue = $attribs["arrts_$attrName"];

```
