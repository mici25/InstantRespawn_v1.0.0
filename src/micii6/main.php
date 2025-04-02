<?php

namespace micii6;

use pocketmine\event\Listener;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\player\GameMode;
use pocketmine\player\Player;

class main extends PluginBase implements Listener {

    public function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->saveDefaultConfig();
    }

    public function onDamage(EntityDamageEvent $event): void {
        $entity = $event->getEntity();

        if ($entity instanceof Player) {
            $player = $entity;

            if ($player->getHealth() - $event->getFinalDamage() <= 0) {
                $event->cancel();

                $originalGamemode = $player->getGamemode();

                $defaultWorld = $this->getServer()->getWorldManager()->getDefaultWorld();
                if ($defaultWorld !== null) {
                    $spawnPosition = $defaultWorld->getSpawnLocation();
                } else {
                    $spawnPosition = $player->getWorld()->getSpawnLocation();
                }

                $player->teleport($spawnPosition);

                $player->setGamemode($originalGamemode);
                $player->setAllowFlight($originalGamemode === GameMode::CREATIVE() || $originalGamemode === GameMode::SPECTATOR());
                $player->setFlying(false);
                $player->setHealth($player->getMaxHealth());
                $player->getHungerManager()->setFood(20);
                $player->setSprinting(false);
                $player->setSneaking(false);

                $player->getEffects()->clear();
                $player->getArmorInventory()->clearAll();
                $player->getInventory()->clearAll();
            }
        }
    }
}
