<?php

/* This file is part of Jeedom.
*
* Jeedom is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* Jeedom is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
*/

/* * ***************************Includes********************************* */

class gcast extends eqLogic {
	/*     * *************************Attributs****************************** */
	
	private $_collectDate = '';
	public static $_widgetPossibility = array('custom' => true);
	
	/*     * ***********************Methode static*************************** */
	
	public static function cronHourly(){//Cron toutes les heures
		$processes = array_merge(system::ps('gcast/resources/caster/stream2chromecast.py'),system::ps('gcast/core/class/../../resources/caster/stream2chromecast.py'), system::ps('gcast/core/class/../../resources/action.py'));
		foreach ($processes as $process) {
			$duration = shell_exec('ps -p ' . $process['pid'] . ' -o etimes -h');
			if ($duration < 600) {
				continue;
			}
			system::kill($process['pid']);
		}
	}
	
	public static function cron() {//Cron toutes les minutes
		foreach (self::byType('gcast') as $gcast) {//Parcours tous les équipements du plugin gCast
			if ($gcast->getIsEnable() == 1) {//Vérifie que l'équipement est actif
				$cmd = $gcast->getCmd(null, 'refresh');//Retourne la commande "refresh si elle existe
				if (!is_object($cmd)) {//Si la commande n'existe pas
					continue; //Continue la boucle
				}
				$cmd->execCmd(); //La commande existe donc on la lance
			}
		}
	}
	
	/*     * *********************Methode d'instance************************* */
	
	public function volume() {
		$ip = $this->getConfiguration('addr');
		$cmd='sudo /usr/bin/python ' . dirname(__FILE__) . '/../../resources/caster/stream2chromecast.py -devicename '. $ip;
		log::add('gcast','debug','Info_volume');
		$action = 'getvol';
		$cmd1 = $cmd . ' -' . $action ;
		log::add('gcast', 'debug', 'Commande exécutée : ' . $cmd1);
		$response = shell_exec($cmd1);
		$response = explode("\n", $response);// On sépare chaque ligne et crée un array
		$volume = round($response[1],2)*100;
		log::add('gcast', 'debug', 'Retour : ' . $volume);
		$this->checkAndUpdateCmd('volume_lvl', $volume);
		return $volume;
	}
	
	public function status() {
		$ip = $this->getConfiguration('addr');
		$cmd='sudo /usr/bin/python ' . dirname(__FILE__) . '/../../resources/caster/stream2chromecast.py -devicename '. $ip;
		log::add('gcast','debug','Info_statut');
		$action = 'isidle';
		$cmd1 = $cmd . ' -' . $action ;
		log::add('gcast', 'debug', 'Commande exécutée : ' . $cmd1);
		$response = shell_exec($cmd1);
		$response = explode("\n", $response);// On sépare chaque ligne et crée un array
		log::add('gcast', 'debug', 'Retour : ' . $response[1]);
		if ($response[1] == 'True') {$isidle = 'Inactif';} else {$isidle = 'Actif';}
		$this->checkAndUpdateCmd('status', $isidle);
	}
	
	public function application() {
		$ip = $this->getConfiguration('addr');
		$cmd='sudo /usr/bin/python ' . dirname(__FILE__) . '/../../resources/caster/stream2chromecast.py -devicename '. $ip;
		log::add('gcast','debug','Info_statut');
		$action = 'status';
		$cmd1 = $cmd . ' -' . $action ;
		log::add('gcast', 'debug', 'Commande exécutée : ' . $cmd1);
		$response = shell_exec($cmd1);
		$response = explode("\n", $response);// On sépare chaque ligne et crée un array
		
		$start = strpos($response[1],'[');
		$end = strpos($response[1],']');
		if ($end <> $start+1) {
			$value = substr($response[1],$start+2,$end-$start-3);
			$value = str_replace("'statusText': u'",'',$value);
			$value = str_replace("Casting:",'',$value);
			$value = str_replace("'displayName': u'",'',$value);
			$value = str_replace("'appId': u'",'',$value);
			$value = str_replace("'",'',$value);
			$values = explode(',',$value);
			if(count($values) > 1){
				$value = trim($values[1]) . ' - ' . trim($values[0]);
			}elseif(count($values) > 1){
				$value = trim($values[0]);
			}
		} else {
			$value = 'Néant';
		}
		log::add('gcast', 'debug', 'Retour : ' . $value);
		$this->checkAndUpdateCmd('application', $value);
	}
	
	public function postSave() {
		$refresh = $this->getCmd(null, 'refresh');
		if (!is_object($refresh)) {
			$refresh = new gcastcmd();
			$refresh->setLogicalId('refresh');
			$refresh->setName(__('Rafraichir', __FILE__));
		}
		$refresh->setType('action');
		$refresh->setSubType('other');
		$refresh->setEqLogic_id($this->getId());
		$refresh->save();
		
		$parle = $this->getCmd(null, 'parle');
		if (!is_object($parle)) {
			$parle = new gcastcmd();
			$parle->setLogicalId('parle');
			$parle->setIsVisible(1);
			$parle->setName(__('Parle', __FILE__));
		}
		$parle->setType('action');
		$parle->setSubType('message');
		$parle->setEqLogic_id($this->getId());
		$parle->setDisplay('title_disable', 1);
		$parle->setDisplay('message_placeholder', __('Phrase', __FILE__));
		$parle->save();
		
		$volplus = $this->getCmd(null, 'volup');
		if (!is_object($volplus)) {
			$volplus = new gcastcmd();
			$volplus->setLogicalId('volup');
			$volplus->setIsVisible(1);
			$volplus->setName(__('Vol+', __FILE__));
		}
		$volplus->setType('action');
		$volplus->setSubType('other');
		$volplus->setEqLogic_id($this->getId());
		$volplus->save();
		
		$volmoins = $this->getCmd(null, 'voldown');
		if (!is_object($volmoins)) {
			$volmoins = new gcastcmd();
			$volmoins->setLogicalId('voldown');
			$volmoins->setIsVisible(1);
			$volmoins->setName(__('Vol-', __FILE__));
		}
		$volmoins->setType('action');
		$volmoins->setSubType('other');
		$volmoins->setEqLogic_id($this->getId());
		$volmoins->save();
		
		$volume = $this->getCmd(null, 'volume');
		if (!is_object($volume)) {
			$volume = new gcastcmd();
			$volume->setLogicalId('volume');
			$volume->setIsVisible(1);
			$volume->setName(__('Volume', __FILE__));
		}
		$volume->setType('action');
		$volume->setSubType('slider');
		$volume->setEqLogic_id($this->getId());
		$volume->save();
		
		$mute = $this->getCmd(null, 'mute');
		if (!is_object($mute)) {
			$mute = new gcastcmd();
			$mute->setLogicalId('mute');
			$mute->setIsVisible(1);
			$mute->setName(__('Muet', __FILE__));
		}
		$mute->setType('action');
		$mute->setSubType('other');
		$mute->setEqLogic_id($this->getId());
		$mute->save();
		
		$status = $this->getCmd(null, 'status');
		if (!is_object($status)) {
			$status = new gcastcmd();
			$status->setLogicalId('status');
			$status->setIsVisible(1);
			$status->setName(__('Statut avec Jeedom', __FILE__));
		}
		$status->setType('info');
		$status->setSubType('string');
		$status->setEqLogic_id($this->getId());
		$status->save();
		
		$application = $this->getCmd(null, 'application');
		if (!is_object($application)) {
			$application = new gcastcmd();
			$application->setLogicalId('application');
			$application->setIsVisible(1);
			$application->setName(__('Application en cours', __FILE__));
		}
		$application->setType('info');
		$application->setSubType('string');
		$application->setEqLogic_id($this->getId());
		$application->save();
		
		$volume_lvl = $this->getCmd(null, 'volume_lvl');
		if (!is_object($volume_lvl)) {
			$volume_lvl = new gcastcmd();
			$volume_lvl->setLogicalId('volume_lvl');
			$volume_lvl->setIsVisible(1);
			$volume_lvl->setName(__('Niveau du volume', __FILE__));
			$volume_lvl->setTemplate('dashboard','line');
		}
		$volume_lvl->setType('info');
		$volume_lvl->setSubType('numeric');
		$volume_lvl->setEqLogic_id($this->getId());
		$volume_lvl->save();
	}
	
	/*     * **********************Getteur Setteur*************************** */
	
}

class gcastCmd extends cmd {
	/*     * *************************Attributs****************************** */
	
	/*     * ***********************Methode static*************************** */
	
	/*     * *********************Methode d'instance************************* */
	
	public function execute($_options = null) {
		if ($this->getType() == '') {
			return '';
		}
		$gcast = $this->getEqLogic();
		$action = $this->getLogicalId();
		$type = 'commande';
		$ip = $gcast->getConfiguration('addr');
		$cmd='sudo /usr/bin/python ' . dirname(__FILE__) . '/../../resources/caster/stream2chromecast.py -devicename '. $ip;
		
		if ($action == 'refresh') {
			$gcast->volume();
			$gcast->status();
			$gcast->application();
		} else if ($type == 'info') {
			if ($action == 'volume_lvl') {
				$gcast->volume();
			} else if ($action == 'status') {
				$gcast->status();
			} else if ($action == 'application') {
				$gcast->application();
			}
		} else if ($type == 'commande') {
			if ($action == 'parle') {
				$gcast->checkAndUpdateCmd('status', 'Actif');//On passe en actif car envoi d'un message
				$url = file_get_contents(network::getNetworkAccess('internal') . '/core/api/tts.php?apikey=' . config::byKey('api', 'core') . '&path=1&text=' . urlencode($_options['message']));
				$cmd .= ' "' . $url .'"' ;
			} else if ($action == 'volume') {
				if ($_options['slider'] < 0) {
					$_options['slider'] = 0;
				} else if ($_options['slider'] > 100) {
					$_options['slider'] = 100;
				}
				$volume = $_options['slider'] / 100;
				$cmd .= ' -setvol ' . $volume ;
			} else if ($action == 'mute') {
				if ($this->getConfiguration('volume_muted') == 0) {
					log::add('gcast','debug','Mute');
					$cmd .= ' -setvol ' . 0 ;
					$this->setConfiguration('volume_muted', $gcast->volume())->save() ;
				} else {
					log::add('gcast','debug','Unmute');
					$cmd .= ' -setvol ' . $this->getConfiguration('volume_muted')/100 ;
					$this->setConfiguration('volume_muted', 0)->save();
				}
			} else if ($action == 'volup' || $action == 'voldown') {
				$cmd .= ' -' . $action ;
			}
			log::add('gcast', 'debug', $cmd);
			if (log::convertLogLevel(log::getLogLevel('gcast')) == 'debug') {
				$cmd .= ' >> ' . log::getPathToLog('gcast') . ' 2>&1 &';
			} else {
				$cmd .= ' > /dev/null 2>&1 &';
			}
			$response = shell_exec($cmd);
			$response = explode("\n", $response);// On sépare chaque ligne et crée un array
			// Si action sur le volume avant, on raffraichi la valeur après
			if ($action == 'volume' || $action == 'voldown' || $action == 'volup' || $action == 'mute') {
				$gcast->volume();
			}
			if ($action == 'parle' && isset($response[count($response)-2])) {
				$response = $response[count($response)-2];// On prend l'avant avant dernière réponse
				log::add('gcast', 'debug', 'Retour : ' . $response);
				if ($response == 'done') {
					$gcast->checkAndUpdateCmd('status', 'Inactif');//On passe en inactif car envoi d'un message terminé
				}
			}
		}
	}
	
	/*     * **********************Getteur Setteur*************************** */
}
