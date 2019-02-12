# Attributes

### EN

The component implements additional attributes for:

- system params,
- menu,
- users,
- contacts,
- articles,
- categories,
- modules,
- plugins.

Attribute values are stored in the parameters of the corresponding element, the `params` field (for articles in the `attribs` field).

Every attribute name is prefixed with `attrs_` prefix. You can get the attribute value by the standard way described below.

### RU

Компонент реализует дополнительные атрибуты для следующих элементов:

- системые параметры,
- меню,
- пользователи,
- контакты,
- материалы,
- категории,
- модули,
- плагины.

Значения атрибутов хранятся в параметрах соответствующего элемента, поле `params` (для материалов поле `attribs`).

К каждому указанному системному имени атрибута добавляется префикс `attrs_`. Вы можете получить значение атрибута стандартным нижеуказанным способом.

### Usage

#### With helper

It is recommended because it checks the status of the attribute (published / unpublished) and it is not necessary to specify the prefix `attrs_`. Skip the third parameter to get the attribute value from the system config.

```php
/*
AttrsHelper::ATTR_DEST_SYSTEM = 'sytems'
AttrsHelper::ATTR_DEST_MENU = 'menu'
AttrsHelper::ATTR_DEST_USERS = 'users'
AttrsHelper::ATTR_DEST_CONTACTS = 'contacts'
AttrsHelper::ATTR_DEST_ARTICLES = 'articles'
AttrsHelper::ATTR_DEST_CATEGORIES = 'categories'
AttrsHelper::ATTR_DEST_MODULES = 'modules'
AttrsHelper::ATTR_DEST_PLUGINS = 'plugins'
*/

JLoader::register('AttrsHelper', JPATH_ADMINISTRATOR . '/components/com_attrs/helpers/attrs.php');
$attrValue = AttrsHelper::getAttr("{$attrName}", AttrsHelper::ATTR_DEST_ARTICLES, $article->id);
```

#### Without helper (not recommended)

```php
// default
$attrValue = $item->params->get("arrts_{$attrName}", '');

// for system
$attrValue = Factory::getConfig->get("arrts_{$attrName}", '');

// for articles
$attribs = json_decode($article->attribs, true);
$attrValue = $attribs["arrts_{$attrName}"];

```

#### In the editor, for content plugin

```
{attrs|dest|id|attrName}
```

- `attrs` - reserved word
- `dest` - belonging to a specific type of record, one of: system, menu, users, contacts, articles, categories, modules, plugins
- `id` - ID of the corresponding entry for the specified property, specify 0 for systems
- `attrName` - attribute system name

**Important**: Unpublished attributes are ignored.