<?php

namespace magic\utils;

use magic\Nshop;
use pocketmine\network\mcpe\protocol\ModalFormRequestPacket;
use pocketmine\Player;


class API
{
    private $plugin;

    const MainUI = 95270;
    const ShopUI = 95271;
    const SellUI = 95272;
    const CmdUI = 95273;
    const ExpUI = 95274;
    const MagicUI = 95275;
    const ShopUITow = 95276;
    const SellUITow = 95277;
    const SwopUI = 95278;
    const SwopUITow = 95279;

    public function __construct(Nshop $plugin)
    {
        $this->plugin = $plugin;
    }

    public function UIAPI(int $formId, Player $player)
    {
        switch ($formId) {
            case self::MainUI:
                $json = $this->MainUI($player);
                break;
            case self::ShopUI:
                $json = $this->ShopUI();
                break;
            case self::SellUI:
                $json = $this->SellUI();
                break;
            case self::CmdUI:
                $json = $this->CmdUI();
                break;
            case self::ExpUI:
                $json = $this->ExpUI();
                break;
            case self::MagicUI:
                $json = $this->MagicUI();
                break;
            case self::ShopUITow:
                $json = $this->ShopUITow();
                break;
            case self::SellUITow:
                $json = $this->SellUITow();
                break;
            case self::SwopUI:
                $json = $this->SwopUI();
                break;
            case self::SwopUITow:
                $json = $this->SwopUITow();
                break;
        }
        $pk = new ModalFormRequestPacket();
        $pk->formId = $formId;
        $pk->formData = $json;
        $player->dataPacket($pk);
    }

    public function MainUI()
    {
        $data = [];
        $ShopSwitch = $this->plugin->getSwitch("出售商店开关");
        $SellSwitch = $this->plugin->getSwitch("回收商店开关");
        $CmdShopSwitch = $this->plugin->getSwitch("指令商店开关");
        $ExpShopSwitch = $this->plugin->getSwitch("经验商店开关");
        $MagicSwitch = $this->plugin->getSwitch("附魔商店开关");
        $SwopSwitch = $this->plugin->getSwitch("兑换商店开关");
        $data["type"] = "form";
        $data["title"] = $this->plugin->getMainConfig("zhu_ui_title");
        $data["content"] = $this->plugin->getMainConfig("zhu_ui_content");
        if ($ShopSwitch) {
            $Text["text"] = $this->plugin->getMainConfig("zhu_ui_text1");
            $Text["image"]["type"] = "path";
            $Text["image"]["data"] = 'textures/items/minecart_furnace';
            $data["buttons"][] = $Text;
        } else {
            $Text["text"] = $this->plugin->getMainConfig("zhu_ui_text1") . '§c已关闭';
            $Text["image"]["type"] = "path";
            $Text["image"]["data"] = 'textures/items/minecart_furnace';
            $data["buttons"][] = $Text;
        }
        if ($SellSwitch) {
            $b["text"] = $this->plugin->getMainConfig("zhu_ui_text2");
            $b["image"]["type"] = "path";
            $b["image"]["data"] = 'textures/items/minecart_chest';
            $data["buttons"][] = $b;
        } else {
            $b["text"] = $this->plugin->getMainConfig("zhu_ui_text2") . '§c已关闭';
            $b["image"]["type"] = "path";
            $b["image"]["data"] = 'textures/items/minecart_chest';
            $data["buttons"][] = $b;
        }
        if ($CmdShopSwitch) {
            $c["text"] = $this->plugin->getMainConfig("zhu_ui_text3");
            $c["image"]["type"] = "path";
            $c["image"]["data"] = 'textures/items/minecart_hopper';
            $data["buttons"][] = $c;
        } else {
            $c["text"] = $this->plugin->getMainConfig("zhu_ui_text3") . '§c已关闭';
            $c["image"]["type"] = "path";
            $c["image"]["data"] = 'textures/items/minecart_hopper';
            $data["buttons"][] = $c;
        }
        if ($ExpShopSwitch) {
            $d["text"] = $this->plugin->getMainConfig("zhu_ui_text4");
            $d["image"]["type"] = "path";
            $d["image"]["data"] = 'textures/items/minecart_tnt';
            $data["buttons"][] = $d;
        } else {
            $d["text"] = $this->plugin->getMainConfig("zhu_ui_text4") . '§c已关闭';
            $d["image"]["type"] = "path";
            $d["image"]["data"] = 'textures/items/minecart_tnt';
            $data["buttons"][] = $d;
        }
        if ($MagicSwitch) {
            $e["text"] = $this->plugin->getMainConfig("zhu_ui_text5");
            $e["image"]["type"] = "path";
            $e["image"]["data"] = 'textures/items/minecart_normal';
            $data["buttons"][] = $e;
        } else {
            $e["text"] = $this->plugin->getMainConfig("zhu_ui_text5") . '§c已关闭';
            $e["image"]["type"] = "path";
            $e["image"]["data"] = 'textures/items/minecart_normal';
            $data["buttons"][] = $e;
        }
        if ($SwopSwitch) {
            $r["text"] = $this->plugin->getMainConfig("zhu_ui_text6");
            $r["image"]["type"] = "path";
            $r["image"]["data"] = 'textures/items/name_tag';
            $data["buttons"][] = $r;
        } else {
            $r["text"] = $this->plugin->getMainConfig("zhu_ui_text6") . '§c已关闭';
            $r["image"]["type"] = "path";
            $r["image"]["data"] = 'textures/items/name_tag';
            $data["buttons"][] = $r;
        }
        $json = $this->getEncodedJson($data);
        return $json;
    }


    public function ShopUI()
    {
        $key = array_keys($this->plugin->getShopAll());
        $data = [];
        $data["type"] = "form";
        $data["title"] = $this->plugin->getMainConfig("shou_ui_title");
        $data["content"] = $this->plugin->getMainConfig("shou_ui_content");
        if ($key !== null) {
            foreach ($key as $keys) {
                $Name = $this->plugin->getShop($keys)["显示"];
                if ($Name == null || $Name == "") {
                    $Name = Nshop::getNshop()->getItemName($keys);
                }
                $Text["text"] = "§b购买§d:" . $Name . "\n§d单价§r§e" . $this->plugin->getShop($keys)["单价"];
                $Text["image"]["type"] = "path";
                $Text["image"]["data"] = Nshop::getNshop()->getItemImage($keys);
                $data["buttons"][] = $Text;
            }
        } else {
            $data["content"] = "§c暂时没有在出售的物品";
            $Text["text"] = "§c关闭界面";
            $data["buttons"][] = $Text;
        }
        $json = $this->getEncodedJson($data);
        return $json;
    }

    public function SellUI()
    {
        $key = array_keys($this->plugin->getSellALL());
        $data = [];
        $data["type"] = "form";
        $data["title"] = $this->plugin->getMainConfig("mai_ui_title");
        $data["content"] = $this->plugin->getMainConfig("mai_ui_content");
        if ($key !== null) {
            foreach ($key as $keys) {
                $Name = $this->plugin->getSell($keys)["显示"];
                if ($Name == null || $Name == "") {
                    $Name = Nshop::getNshop()->getItemName($keys);
                }
                $Text["text"] = "§d出售§a" . $Name . "\n§e单价§b" . $this->plugin->getSell($keys)["单价"];
                $Text["image"]["type"] = "path";
                $Text["image"]["data"] = Nshop::getNshop()->getItemImage($keys);
                $data["buttons"][] = $Text;
            }
        } else {
            $data["content"] = "§c暂时没有可出售的物品";
            $Text["text"] = "§c关闭界面";
            $data["buttons"][] = $Text;
        }
        $json = $this->getEncodedJson($data);
        return $json;
    }

    public function CmdUI()
    {
        $key = array_keys($this->plugin->getCmdShopAll());
        $data = [];
        $data["type"] = "form";
        $data["title"] = $this->plugin->getMainConfig("sui_ui_title");
        $data["content"] = $this->plugin->getMainConfig("sui_ui_content");
        if ($key !== null) {
            foreach ($key as $keys) {
                $Text["text"] = $this->plugin->getCmdShop($keys)["显示"] . "\n§a价格§e" . $this->plugin->getCmdShop($keys)["单价"];
                $data["buttons"][] = $Text;
            }
        } else {
            $data["content"] = "§c暂时没有上架的货物";
            $Text["text"] = "§c关闭界面";
            $data["buttons"][] = $Text;
        }
        $json = $this->getEncodedJson($data);
        return $json;
    }

    public function ExpUI()
    {
        $key = array_keys($this->plugin->getExpShopAll());
        $data = [];
        $data["type"] = "form";
        $data["title"] = $this->plugin->getMainConfig("yan_ui_title");
        $data["content"] = $this->plugin->getMainConfig("yan_ui_content");
        if ($key !== null) {
            foreach ($key as $keys) {
                $Text["text"] = $this->plugin->getExpShop($keys)["显示"] . "\n§a价格§e" . $this->plugin->getExpShop($keys)["单价"];
                $data["buttons"][] = $Text;
            }
        } else {
            $data["content"] = "§c暂时没有上架的货物";
            $Text["text"] = "§c关闭界面";
            $data["buttons"][] = $Text;
        }
        $json = $this->getEncodedJson($data);
        return $json;
    }

    public function MagicUI()
    {
        $key = array_keys($this->plugin->getMagicShopAll());
        $data = [];
        $data["type"] = "form";
        $data["title"] = $this->plugin->getMainConfig("mo_ui_title");
        $data["content"] = $this->plugin->getMainConfig("mo_ui_content") . "\n§6记得将要附魔的物品放在§c第二个§6物品栏哦\n§6记得将要附魔的物品放在§4第二个§6物品栏哦\n§6记得将要附魔的物品放在§e第二个§6物品栏哦";
        if ($key !== null) {
            foreach ($key as $keys) {
                $Text["text"] = $this->plugin->getMagicShop($keys)["显示"] . "\n§l§3价格" . $this->plugin->getMagicShop($keys)["单价"];
                $data["buttons"][] = $Text;
            }
        } else {
            $data["content"] = "§c暂时没有可附魔的属性";
            $Text["text"] = "§c关闭界面";
            $data["buttons"][] = $Text;
        }
        $json = $this->getEncodedJson($data);
        return $json;
    }

    public function SwopUI()
    {
        $key = array_keys($this->plugin->getSwopShopAll());
        $data = [];
        $data["type"] = "form";
        $data["title"] = $this->plugin->getMainConfig("huan_ui_title");
        $data["content"] = $this->plugin->getMainConfig("huan_ui_content");
        if ($key !== null) {
            foreach ($key as $keys) {
                $Text["text"] = $this->plugin->getSwopShop($keys)["显示"] . "\n§d需要物品" . $this->plugin->getSwopShop($keys)["需要物品"];
                $data["buttons"][] = $Text;
            }
        } else {
            $data["content"] = "§c暂时没有可兑换的物品";
            $Text["text"] = "§c关闭界面";
            $data["buttons"][] = $Text;
        }
        $json = $this->getEncodedJson($data);
        return $json;
    }


    public function ShopUITow()
    {
        $data = [];
        $data["type"] = "custom_form";
        $data["title"] = "§e输入你要的数量";
        $content[0]["type"] = "input";
        $content[0]["text"] = "需要购买的数量:";
        $data["content"] = $content;
        $json = $this->getEncodedJson($data);
        return $json;
    }

    public function SellUITow()
    {
        $data = [];
        $data["type"] = "custom_form";
        $data["title"] = "§e输入你要的数量";
        $content[0]["type"] = "input";
        $content[0]["text"] = "需要出售的数量:";
        $data["content"] = $content;
        $json = $this->getEncodedJson($data);
        return $json;
    }

    public function SwopUITow()
    {
        $data = [];
        $data["type"] = "custom_form";
        $data["title"] = "§e输入你要的数量";
        $content[0]["type"] = "input";
        $content[0]["text"] = "需要兑换的数量:";
        $data["content"] = $content;
        $json = $this->getEncodedJson($data);
        return $json;
    }

    public function getEncodedJson($data)
    {
        return json_encode($data, JSON_PRETTY_PRINT | JSON_BIGINT_AS_STRING | JSON_UNESCAPED_UNICODE);
    }
}






	