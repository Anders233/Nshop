<?php

namespace magic\utils;

use magic\Nshop;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class NshopCommand
{

    /**
     * 插件主类
     * @var Nshop
     */
    private $plugin;

    public function __construct(Nshop $plugin)
    {
        $this->plugin = $plugin;
    }

    /**
     * 命令处理器
     * @param CommandSender $sender
     * @param Command $command
     * @param string $label
     * @param array $args
     * @return bool
     */
    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        switch ($command->getName()) {
            case "nshop":
                if ($sender->hasPermission("Nshop.Command.Nshop")) {
                    if ($sender instanceof Player) {
                        $this->plugin->getShopMenuItem($sender);
                    } else {
                        $sender->sendMessage($this->plugin->getPreFix() . "请在游戏中使用此命令!");
                    }
                    if (isset($args[0])) {
                        switch ($args[0]) {
                            case "reload":
                                if ($sender->hasPermission("Nshop.Command.Nshop.Relaod")) {
                                    $this->plugin->reloadConfig();
                                    $sender->sendMessage($this->plugin->getPreFix() . "配置文件重载完成!");
                                } else {
                                    $sender->sendMessage($this->plugin->getPreFix() . "你没有权限使用这条指令!");
                                }
                                break;
                            case "open":
                            case "Open":
                            case "打开":
                            case "Main":
                            case "main":
                            case "shop":
                            case "Shop":
                            case "商店":
                            case "ui":
                            case "UI":
                            case "nshop":
                                if ($sender instanceof Player) {
                                    if ($sender->hasPermission("Nshop.Command.Nshop.Shop")) {
                                        $this->plugin->getMainUI($sender);
                                    } else {
                                        $sender->sendMessage($this->plugin->getPreFix() . "你没有权限使用这条指令!");
                                    }
                                } else {
                                    $sender->sendMessage($this->plugin->getPreFix() . "请在游戏中使用此命令!");
                                }
                                break;
                        }
                    }
                    break;
                } else {
                    $sender->sendMessage($this->plugin->getPreFix() . "请在游戏中使用此命令!");
                }
        }
        return true;
    }
}