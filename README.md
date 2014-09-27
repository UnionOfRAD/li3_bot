# li₃ bot

##  About

`li3_bot` is an IRC bot and webinterface plugin for li3. 

## Installation & Configuration

The logging, karma and tell plugins need a working (My)SQL database connection. The schema to initialize the tables can be found under `config/schema.sql`.

```
Connections::add('default', array(
	'type' => 'database',
	'adapter' => 'MySql',
	// ...
));
```

For perfomance reasons (log tables can't get huge) you should also have caching configured in your li3 app.

The plugin can be configured by passing additional options when registering the plugin.

```
Libraries::add('li3_bot', array(
	'host' => 'irc.freenode.net',
	'port' => 6667,
	'nick' => 'li3bot',
	'channels' => ['#li3', '#li3-core'],
	'rewriters' => [
		'(example\.(org|com))' => function($inner, $outer) {
			return str_replace($inner, '<strike>' . $inner . '</strike>', $outer);
		},
		'(.*)' => function($inner, $outer) {
			return '<a href="' . $inner . '" rel="nofollow">' . $outer . '</a>';
		}
	]
));
```

## Usage

Run `li3 bot` from within your li3 application in order to statup the bot. 
The bot will join the configured channels and stay there until you hit `STRG`+`C` or the script terminates otherwise.

## Plugins

The bot can extended through plugins. Such plugins are required to subclass `\li3_bot\extensions\command\bot\Plugin`. 

Following plugins are already builtin and can be found in `extensions/command/bot/plugins`: 

 * Feed - pulls from RSS feeds.
 * Karma - maintains a karma highscore board.
 * Logging - logs channel messages. 
 * Tell - stores and retrieves socalled tells.
 * Weather - allows for retrieving the weather.

