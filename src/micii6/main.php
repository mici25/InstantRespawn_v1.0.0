<?php

namespace micii6;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\ClosureTask;

class main extends PluginBase implements Listener {

    public function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->saveDefaultConfig();
    }

    public function onDeath(PlayerDeathEvent $event): void {
        $player = $event->getPlayer();

        $this->getScheduler()->scheduleDelayedTask(new ClosureTask(function () use ($player): void {
            if (!$player->isOnline()) {
                return;
            }
            $player->respawn();
        }), 1);
    }
}