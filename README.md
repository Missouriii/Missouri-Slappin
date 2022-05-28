# Mssouri SLapping
- (Fist) is a survival and fighting game where the game begins and you have to fight to survive.


# How to create a arena
- Teleport to the world who need make an arena in it.
- frist type `/fist create "your arena name"` to create the arena.
- now go to arena lobby and type `/fist setlobby` to set it.
- ok, now go to the respawn position and type `/fist setrespawn` that will return to it after death (you can turn on/off from config)
- Great, you are now ready to play type `/fist join "your arena name"` enjoy. if you want leave the game type `/fist quit`

# the configure
- `scoreboardIp` : you can set to your server ip to show it in the game scoreboard
- `banned-commands`: you can add the commands who want to banned in the game
- `death-respawn-inMap` : that's will return the player to respawn position after death, you can set to `true` or `false`
- `join-and-respawn-protected` : that's will protect the player for 3 seconds after join and respawn
- `death-attack-message` : here you can set the death message when killed by someone
- `death-void-message` : and here you can set the death message when killed by void

# Commands
Command | Description | Permission
--- | --- | ---
`/fist join <ArenaName:optional>` | `To join a specific or random arena` | `No permission`
`/fist quit` | `To leave the arena` | `No permission`
`/fist help` | `To see commands list` | `fist.command.admin`
`/fist create` | `To create a new arena` | `fist.command.admin`
`/fist remove` | `To delete a specific arena` | `fist.command.admin`
`/fist setlobby` | `To set lobby position in arena` | `fist.command.admin`
`/fist setrespawn` | `To set respawn position in arena` | `fist.command.admin`
`/fist list` | `To see arenas list` | `fist.command.admin`
