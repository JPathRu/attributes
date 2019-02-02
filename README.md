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

```
// default
$attrValue = $item->params->get("arrts_{$attrName}", '');

// for articles
$attribs = json_decode($item->attribs, true);
$attrValue = $attribs["arrts_{$attrName}"];
```
