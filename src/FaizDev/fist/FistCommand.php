<?php

namespace FaizDev\fist;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\command\Command;
use pocketmine\plugin\PluginOwned;
use pocketmine\plugin\Plugin;

use pocketmine\utils\{TextFormat as TF, Config};

use pocketmine\player\Player;

class FistCommand extends Command implements PluginOwned
{
	/** @var Main */
	private Main $plugin;
	
	public function init(Main $plugin) : void{
		$this->plugin = $plugin;
		//$this->setPermission("fist.command.admin");
	}
	
	public function getOwningPlugin() : Plugin{
		return $this->plugin;
	}
	
	public function execute(CommandSender $sender, string $cmdLabel, array $args): bool{
		if(!($sender instanceof Player)){
			$sender->sendMessage("§l§2»§r§c Please run this command in-game");
			return false;
		}
		
		if(!isset($args[0])){
			$sender->sendMessage(TF::RED . "§l§2»§r§c Usage: /" . $cmdLabel . " help");
			return false;
		}
		
		switch ($args[0]){
			case "help":
				$sender->sendMessage(TF::YELLOW . "§l§e========================");
				//if($this->testPermission($sender)){
				if($sender->hasPermission("fist.command.admin")){
					$sender->sendMessage(TF::GREEN  . "- /" . $cmdLabel . " help");
					$sender->sendMessage(TF::GREEN  . "- /" . $cmdLabel . " create");
					$sender->sendMessage(TF::GREEN  . "- /" . $cmdLabel . " remove");
					$sender->sendMessage(TF::GREEN  . "- /" . $cmdLabel . " setlobby");
					$sender->sendMessage(TF::GREEN  . "- /" . $cmdLabel . " setrespawn");
					$sender->sendMessage(TF::GREEN  . "- /" . $cmdLabel . " list");
				}
				$sender->sendMessage(TF::GREEN  . "- /" . $cmdLabel . " join");
				$sender->sendMessage(TF::GREEN  . "- /" . $cmdLabel . " quit");
				$sender->sendMessage(TF::YELLOW . "§l§e========================");
			break;
			
			case "create":
				if(!$sender->hasPermission("fist.command.admin"))
					return false;
				if(!isset($args[1])){
					$sender->sendMessage(TF::RED . "§l§2»§r§c Usage: /" . $cmdLabel . " create <arenaName>");
					return false;
				}
				
				$arenaName = $args[1];
				$level = $sender->getWorld();
				
				if($level->getFolderName() == $this->plugin->getServer()->getWorldManager()->getDefaultWorld()->getFolderName()){
					$sender->sendMessage(TF::RED . "§l§2»§r§c You cannot create a game in Hub!");
					return false;
				}
				
				$arenas = new Config($this->plugin->getDataFolder() . "arenas.yml", Config::YAML);
				
				if($arenas->get($arenaName)){
					$sender->sendMessage(TF::RED . "§l§2»§r§c Arena already exists!");
					return false;
				}
				
				$data = ["name" => $arenaName, "world" => $level->getFolderName(), "lobby" => [], "respawn" => []];
				if($this->plugin->addArena($data)){
					$sender->sendMessage(TF::YELLOW . "§l§2»§r§a Arena created!");
					return true;
				}
			break;
			
			case "remove":
				if(!$sender->hasPermission("fist.command.admin"))
					return false;
				
				if(!isset($args[1])){
					$sender->sendMessage(TF::RED . "§l§2»§r§c Usage: /" . $cmdLabel . " remove <arenaName>");
					return false;
				}
				
				$arenaName = $args[1];
				
				if(!isset($this->plugin->arenas[$arenaName])){
					$sender->sendMessage(TF::RED . "§l§2»§r§c Arena does not exist");
					return false;
				}
				
				if($this->plugin->removeArena($arenaName)){
					$sender->sendMessage(TF::GREEN . "§l§2»§r§a Arena deleted!");
					return true;
				}
			break;
			
			case "setlobby":
				if(!$sender->hasPermission("fist.command.admin"))
					return false;
				
				$level = $sender->getWorld();
				$arena = null;
				$arenaName = null;
				foreach ($this->plugin->getArenas() as $arena_){
					// if($arena_->getName() == $level->getFolderName()){
					if($arena_->getWorld() == $level->getFolderName()){// done fixed arena not exist, if the arena name not same world name
						$arenaName = $arena_->getName();
						$arena = $arena_;
					}
				}
				
				if($arenaName == null){
					$sender->sendMessage(TF::RED . "§l§2»§r§c Arena does not exist, please try:§e Usage: /" . $cmdLabel . " create" . "!");
					return false;
				}
				
				$arenas = new Config($this->plugin->getDataFolder() . "arenas.yml", Config::YAML);
				$data = $arenas->get($arenaName);
				$data["lobby"] = ["PX" => $sender->getLocation()->x, "PY" => $sender->getLocation()->y, "PZ" => $sender->getLocation()->z, "YAW" => $sender->getLocation()->yaw, "PITCH" => $sender->getLocation()->pitch];
				$arenas->set($arenaName, $data);
				$arenas->save();
				if($arena !== null)
					$arena->UpdateData($data);
				$sender->sendMessage(TF::YELLOW . "§l§2»§r§a Lobby has been set!");
			break;
			
			case "setrespawn":
				if(!$sender->hasPermission("fist.command.admin"))
					return false;
				
				$level = $sender->getWorld();
				$arena = null;
				$arenaName = null;
				foreach ($this->plugin->getArenas() as $arena_){
					// if($arena_->getName() == $level->getFolderName()){
					if($arena_->getWorld() == $level->getFolderName()){// done fixed arena not exist, if the arena name not same world name
						$arenaName = $arena_->getName();
						$arena = $arena_;
					}
				}
				
				if($arenaName == null){
					$sender->sendMessage(TF::RED . "§l§2»§r§c Arenadoes not exist, please try using:§e Usage: /" . $cmdLabel . " create" . "!");
					return false;
				}
				
				$arenas = new Config($this->plugin->getDataFolder() . "arenas.yml", Config::YAML);
				$data = $arenas->get($arenaName);
				$data["respawn"] = ["PX" => $sender->getLocation()->x, "PY" => $sender->getLocation()->y, "PZ" => $sender->getLocation()->z, "YAW" => $sender->getLocation()->yaw, "PITCH" => $sender->getLocation()->pitch];
				$arenas->set($arenaName, $data);
				$arenas->save();
				if($arena !== null)
					$arena->UpdateData($data);
				$sender->sendMessage(TF::YELLOW . "§l§2»§r§a Respawn has been set!");
			break;
			
			case "list":
				if(!$sender->hasPermission("fist.command.admin"))
					return false;
				
				$sender->sendMessage(TF::GREEN . "§l§2ARENAS:");
				foreach ($this->plugin->getArenas() as $arena){
					$sender->sendMessage(TF::YELLOW . "- " . $arena->getName() . " Players: " . count($arena->getPlayers()));
				}
			break;
			
			case "join":
				if(!$sender->hasPermission("fist.command.join"))
					return false;
				if(isset($args[1])){
					$player = $sender;
					
					if(isset($args[2])){
						if(($pp = $this->plugin->getServer()->getPlayerByPrefix($args[2])) !== null){
							$player = $pp;
						}
					}
					
					if($this->plugin->joinArena($player, $args[1])){
						return true;
					}
				} else {
					if($this->plugin->joinRandomArena($sender)){
						return true;
					}
				}
			break;
			
			case "quit":
				if(!$sender->hasPermission("fist.command.quit"))
					return false;
				if(($arena = $this->plugin->getPlayerArena($sender)) !== null){
					if($arena->quitPlayer($sender)){
						return true;
					}
				} else {
					$sender->sendMessage("§l§2»§r§c You're not currently in an arena!");
					return false;
				}
			break;
		}
		return false;
	}
}
