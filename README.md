# li3 bot

##  About

`li3_bot` is an IRC bot and webinterface. 

## Configuration

The plugin can be configured by editing `config/li3_bot.ini`.

## Usage

Run `li3 bot` from within your Lithium application in order to statup the bot. 
The bot will join the configured channels and stay there until you hit `STRG`+`C` or the script terminates otherwise.

## Plugins

The bot can extended through plugins. Such plugins are required to subclass `\li3_bot\extensions\command\bot\Plugin`. 

Following plugins are already builtin and can be found in `extensions/command/bot/plugins`: 

 * Feed - pulls from RSS feeds.
 * Karma - maintains a karma highscore board.
 * Logging - logs channel messages. 
 * Tell - stores and retrieves socalled tells.
 * Weather - allows for retrieving the weather.
