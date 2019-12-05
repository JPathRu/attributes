# Attributes

![Last Update](https://img.shields.io/badge/last_update-2019.12.05-28A5F5.svg?style=for-the-badge)
![Version](https://img.shields.io/badge/VERSION-1.3.1-0366d6.svg?style=for-the-badge)
![Joomla](https://img.shields.io/badge/joomla-3.7+-1A3867.svg?style=for-the-badge)
![Php](https://img.shields.io/badge/php-5.6+-8892BF.svg?style=for-the-badge)

_description in Russian [here](README.ru.md)_

The component implements additional attributes for:

- system params,
- menu,
- users,
- contacts,
- articles,
- categories,
- modules,
- plugins,
- fields,
- tags.

The following attribute types are implemented:

- text (`type="text"`),
- multiline text (`type="textarea"`),
- editor (`type="editor"`),
- list (`type="list"`),
- image (`type="media"`).

Type **list** supports the multiple value option.

Type **multiline text** supports the standard Joomla content filtering types: html, raw and others.

WARNING! Types **multiline text** and **editor** are not recommended for system parameters: when restoring a site using Akeeba Backup, parameter values may be lost.

Attribute values are stored in the parameters of the corresponding element, the `params` field (for articles in the `attribs` field).

Every attribute name is prefixed with `attrs_` prefix. You can get the attribute value by the standard way described below.

For each attribute, it is possible to specify an individual output layout. Layouts are created in the /templates/{your_template}/html/layouts/com_attrs/ folder and are assigned to each attribute separately. The attribute data inside the layout is contained in the `$displayData` structure. The default layout does not contain markup.

## Usage

### Shortcode, for use in content editor

```text
{attrs;dest;id;attrName[;layout]}
```

- `attrs` - reserved word
- `dest` - belonging to a specific type of record, one of: system, menu, users, contacts, articles, categories, modules, plugins
- `id` - ID of the corresponding entry for the specified property, specify 0 for systems
- `attrName` - attribute system name
- `layout` - output layout (optional), if **0** or **false** is specified, the assigned layout will be ignored and the attribute value will be displayed without layout; format for specifying the layout: `template:layoutname`, where _template_ is the main template of the site that contains the override (specify the underscore to indicate the default template), _layoutname_ is the name of the overridden layout file, without extension; when specifying a nonexistent layout, the attribute value will not be displayed

**Important**: Unpublished attributes are ignored. Images are output without markup, only the path. Arrays are displayed as a list of values separated by commas.

**Example**: You have created an attribute with the name test for a material with ID = 5, to get its value, insert the following line in the editor: `{attrs;articles;5;test}`.
If you want to display the attribute indicating the override of the template created with the name mytemplate in the main protostar site template, insert the following line into the editor: `{attrs;articles;5;test;protostar:mytemplate}`.

### With helper

It is recommended because it checks the status of the attribute (published / unpublished) and it is not necessary to specify the prefix `attrs_`. Do not specify the third parameter or set it to zero to get the attribute value from the system configuration.

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

// output with the layout specified in the attribute parameters
$attrValue = AttrsHelper::getAttr($attrName, AttrsHelper::ATTR_DEST_ARTICLES, $article->id);

// priority layout output
// the attribute layout will be loaded /templates/protostar/html/layouts/com_attrs/mytemplate.php
$attrValue = AttrsHelper::getAttr($attrName, AttrsHelper::ATTR_DEST_ARTICLES, $article->id, 'protostar:mytemplate');
```

### Without helper (not recommended)

This method does not support attribute standardization.

```php
// default
$attrValue = $item->params->get("arrts_$attrName", '');

// for system
$attrValue = Joomla\CMS\Factory::getConfig->get("arrts_$attrName", '');

// for articles
$attribs = json_decode($article->attribs, true);
$attrValue = $attribs["arrts_$attrName"];

```
