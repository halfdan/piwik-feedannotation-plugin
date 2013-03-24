# Piwik FeedAnnotation Plugin

This is a plugin for the Open Source Web Analytics platform Piwik. It allows you to automatically fetch RSS/Atom-Feeds from your website and create new Annotations from feed entries. 

Since the "Annotations" plugin was introduced in Piwik 1.10, this plugin requires at least Piwik 1.10.

![Annotations in Piwik](http://cdn.geekmonkey.org/assets/files/000/000/038/screen/annotations-view.png)

## Documentation

1. Clone the plugin into the plugins directory of your Piwik installation.

   ```
   cd plugins/
   git clone https://github.com/halfdan/piwik-feedannotation-plugin.git FeedAnnotation
   ```

2. Login as superuser into your Piwik installation and activate the plugin under Settings -> Plugins

3. You can add new feeds for sites you have admin access to under Settings -> Feed Annotations

Feeds are fetched once a day using "scheduled tasks". After adding a feed you can manually force the plugin to process your feed by clicking the "Process now" link.

## Contribute 

If you are interested in contributing to this plugin, feel free to send pull requests!
