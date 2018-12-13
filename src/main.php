<?php
/*
MADE BY:
 __    __                                          __        __  __  __                     
/  |  /  |                                        /  |      /  |/  |/  |                    
$$ |  $$ |  ______   _______    ______    ______  $$ |____  $$/ $$ |$$/   _______  __    __ 
$$  \/$$/  /      \ /       \  /      \  /      \ $$      \ /  |$$ |/  | /       |/  |  /  |
 $$  $$<  /$$$$$$  |$$$$$$$  |/$$$$$$  |/$$$$$$  |$$$$$$$  |$$ |$$ |$$ |/$$$$$$$/ $$ |  $$ |
  $$$$  \ $$    $$ |$$ |  $$ |$$ |  $$ |$$ |  $$ |$$ |  $$ |$$ |$$ |$$ |$$ |      $$ |  $$ |
 $$ /$$  |$$$$$$$$/ $$ |  $$ |$$ \__$$ |$$ |__$$ |$$ |  $$ |$$ |$$ |$$ |$$ \_____ $$ \__$$ |
$$ |  $$ |$$       |$$ |  $$ |$$    $$/ $$    $$/ $$ |  $$ |$$ |$$ |$$ |$$       |$$    $$ |
$$/   $$/  $$$$$$$/ $$/   $$/  $$$$$$/  $$$$$$$/  $$/   $$/ $$/ $$/ $$/  $$$$$$$/  $$$$$$$ |
                                        $$ |                                      /  \__$$ |
                                        $$ |                                      $$    $$/ 
                                        $$/                                        $$$$$$/            
*/

namespace XenoTransfer;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat as Color;
use pocketmine\scheduler\PluginTask;
use pocketmine\command\CommandExecuter;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\Server;
use pocketmine\Player;

use jojoe77777\FormAPI;
use jojoe77777\FormAPI\SimpleForm;

class Main extends PluginBase implements Listener{
    //LOAD
    public function onLoad(){
        $this->getLogger()->info("§eXenoTransfer by §6Xenophilicy §eis loading...");
    }
    //ENABLE
    public function onEnable(){
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        @mkdir($this->getDataFolder());
        $this->saveResource("config.yml");
        $this->getLogger()->info("§aXenoTransfer by §6Xenophilicy §ahas been enabled! ".$this->getConfig()->get("Enable_Message"));
    }
    //DISABLE
    public function onDisable(){
        $this->getLogger()->info("§cXenoTransfer by §6Xenophilicy §chas been disabled! ".$this->getConfig()->get("Disable_Message"));
    }
    //COMMAND-SENT
    public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
        $player = $sender->getPlayer();
        switch($command->getName()){
        case'servers':
            $this->serverList($sender);
            break;
        case'servers-info':
            $sender->sendMessage("§7-========= §6XenoTransfer §7=========-");
            $sender->sendMessage("§eAuthor: §aXenophillicy");
            $sender->sendMessage("§eDescription: §aLets you transfer between your server network with forms!");
            $sender->sendMessage("§7-===============================-");
            break;
    }
    return true;
}
    //SERVER-LIST-FORM
    public function serverList($player){
        $formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = new SimpleForm(function (Player $player, $data){
            if($data === null){
                return;
            }
                switch($data){
                    case 0:
                        $this->getServer()->getCommandMap()->dispatch($player, 'transferserver '.$this->getConfig()->get("Server_1_IP").' '.$this->getConfig()->get("Server_1_Port"));
                        break;
                    case 1:
                        $this->getServer()->getCommandMap()->dispatch($player, 'transferserver '.$this->getConfig()->get("Server_2_IP").' '.$this->getConfig()->get("Server_2_Port"));
                        break;
                    case 2:
                        $this->getServer()->getCommandMap()->dispatch($player, 'transferserver '.$this->getConfig()->get("Server_3_IP").' '.$this->getConfig()->get("Server_3_Port"));
                        break;
                    case 3:
                        $this->getServer()->getCommandMap()->dispatch($player, 'transferserver '.$this->getConfig()->get("Server_4_IP").' '.$this->getConfig()->get("Server_4_Port"));
                        break;
                    case 4:
                        $this->getServer()->getCommandMap()->dispatch($player, 'transferserver '.$this->getConfig()->get("Server_5_IP").' '.$this->getConfig()->get("Server_5_Port"));
                        break;
                }
            return true;
        });
        //MAKE-FORM
        $form->setTitle("§6Server List");
        $form->setContent("§aChoose a server to transfer to!");
        $form->addButton($this->getConfig()->get("Server_1_Label"));
        $form->addButton($this->getConfig()->get("Server_2_Label"));
        $form->addButton($this->getConfig()->get("Server_3_Label"));
        $form->addButton($this->getConfig()->get("Server_4_Label"));
        $form->addButton($this->getConfig()->get("Server_5_Label"));
        $form->sendToPlayer($player);
    }
}        
