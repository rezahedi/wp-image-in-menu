# WP Plugin: Add Image to Menu
Contributors: Reza
Tags: image, menu, icon
Requires at least: 5
Tested up to: 6.0.1
Stable tag: 


## Description

This is a simple plugin to add custom field to wordpress menu items. So for each menu items in WP admin panel > Appearance > Menus, you can select an image from WP media for each menu items.

### Frontend Styles

This plugin doesn't have any frontend styles or import any js files. So you need to add your own styles to show/style the menu images in your theme. You can use the following CSS code to show the menu images in your theme.

First way is using the default WP menu classes:
```css
.menu-item img
{
   /* some styles */
}
```

Or you can use plugin's added classes:
```css
.wp-menu-img img,
.wp-menu-img-after img,
.wp-menu-img-before img
{
   /* some styles */
}
```

## Features that need to work on it

- Add 'Add image' ability to Apearance > Customizer > Menus
- Lazy loading and responsive images
- Add settings page to plugin to set values like custom class names, image inline default sizes, etc.