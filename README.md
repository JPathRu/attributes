# Attributes

### EN

The component implements additional attributes for system elements, menus, materials, categories, modules and plugins.

Attribute values are stored in the parameters of the corresponding element, the params field (for materials, the attribs field).

Each attributed attribute name is prefixed with `attrs_`. You can get the attribute value in the standard way indicated below.

### RU

Компонент реализует дополнительные атрибуты для элементов системы, меню, материалов, категорий, модулей и плагинов.

Значения атрибутов хранятся в параметрах соответствующего элемента, поле params (для материалов поле attribs).

К каждому указанному системному имени атрибута добавляется префикс `attrs_`. Вы можете получить значение атрибута стандартным нижеуказанным способом.

---

Using helper. It is recommended because checks the status of the attribute (published / unpublished), 
it is not necessary to specify the prefix `attrs_`.

```
/*
AttrsHelper::ATTR_DEST_SYSTEM = 'sytems'
AttrsHelper::ATTR_DEST_MENU = 'menu'
AttrsHelper::ATTR_DEST_ARTICLES = 'articles'
AttrsHelper::ATTR_DEST_CATEGORIES = 'categories'
AttrsHelper::ATTR_DEST_MODULES = 'modules'
AttrsHelper::ATTR_DEST_PLUGINS = 'plugins'
*/

JLoader::register('AttrsHelper', JPATH_ADMINISTRATOR . '/components/com_attrs/helpers/attrs.php');
$attrValue = AttrsHelper::getAttr("{$attrName}", AttrsHelper::ATTR_DEST_ARTICLES, $article->id);
```

Without helper (not recommended)
```
// default
$attrValue = $item->params->get("arrts_{$attrName}", '');

// for system
$attrValue = Factory::getConfig->get("arrts_{$attrName}", '');

// for articles
$attribs = json_decode($article->attribs, true);
$attrValue = $attribs["arrts_{$attrName}"];

```
