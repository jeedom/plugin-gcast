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
require_once __DIR__  . '/../../../../core/php/core.inc.php';

if (!class_exists('Chromecast')) {
	require_once __DIR__ . '/../../3rdparty/cast/Chromecast.php';
}

class gcast extends eqLogic {
	/*     * *************************Attributs****************************** */

	private $_collectDate = '';
	public static $_widgetPossibility = array('custom' => true);

	/*     * ***********************Methode static*************************** */

	public static function cron5() {
		foreach (self::byType('gcast', true) as $gcast) {
			$gcast->updateData();
		}
	}

	/*     * *********************Methode d'instance************************* */

	public function getChromecast() {
		try {
			return new Chromecast($this->getConfiguration('addr'), '8009');
		} catch (\Exception $e) {
		}
		sleep(15);
		try {
			return new Chromecast($this->getConfiguration('addr'), '8009');
		} catch (\Exception $e) {
		}
		sleep(30);
		try {
			return new Chromecast($this->getConfiguration('addr'), '8009');
		} catch (\Exception $e) {
		}
		sleep(60);
		try {
			return new Chromecast($this->getConfiguration('addr'), '8009');
		} catch (\Exception $e) {
		}
		sleep(120);
		return new Chromecast($this->getConfiguration('addr'), '8009');
	}

	public function updateData() {
		$cc = $this->getChromecast();
		try {
			$cc->cc_connect();
		} catch (\Exception $e) {
			sleep(2);
			try {
				$cc->cc_connect();
			} catch (\Exception $e) {
				sleep(5);
				$cc->cc_connect();
			}
		}
		preg_match_all('/\{.*?\}$/m', $cc->getStatus(), $matches);
		if (isset($matches[0][0])) {
			$status = json_decode($matches[0][0], true);
		}
		if (!is_array($status) || count($status) == 0) {
			return;
		}
		$this->checkAndUpdateCmd('volume_lvl', $status['status']['volume']['level'] * 100);
		$this->checkAndUpdateCmd('mute_state', $status['status']['volume']['muted']);
		if (isset($status['status']['applications']) && isset($status['status']['applications'][0])) {
			$this->checkAndUpdateCmd('application', $status['status']['applications'][0]['displayName']);
			$this->checkAndUpdateCmd('status', $status['status']['applications'][0]['statusText']);
		} else {
			$this->checkAndUpdateCmd('application', '');
			$this->checkAndUpdateCmd('status', '');
		}
	}

	public function postSave() {
		$cmd = $this->getCmd(null, 'refresh');
		if (!is_object($cmd)) {
			$cmd = new gcastcmd();
			$cmd->setLogicalId('refresh');
			$cmd->setName(__('Rafraichir', __FILE__));
		}
		$cmd->setType('action');
		$cmd->setSubType('other');
		$cmd->setEqLogic_id($this->getId());
		$cmd->save();

		$cmd = $this->getCmd(null, 'parle');
		if (!is_object($cmd)) {
			$cmd = new gcastcmd();
			$cmd->setLogicalId('parle');
			$cmd->setIsVisible(1);
			$cmd->setName(__('Parle', __FILE__));
		}
		$cmd->setType('action');
		$cmd->setSubType('message');
		$cmd->setEqLogic_id($this->getId());
		$cmd->setDisplay('title_disable', 1);
		$cmd->setDisplay('message_placeholder', __('Phrase', __FILE__));
		$cmd->save();

		$cmd = $this->getCmd(null, 'volume');
		if (!is_object($cmd)) {
			$cmd = new gcastcmd();
			$cmd->setLogicalId('volume');
			$cmd->setIsVisible(1);
			$cmd->setName(__('Volume', __FILE__));
		}
		$cmd->setType('action');
		$cmd->setSubType('slider');
		$cmd->setEqLogic_id($this->getId());
		$cmd->save();

		$cmd = $this->getCmd(null, 'mute_state');
		if (!is_object($cmd)) {
			$cmd = new gcastcmd();
			$cmd->setLogicalId('mute_state');
			$cmd->setIsVisible(1);
			$cmd->setName(__('Muet status', __FILE__));
			$cmd->setConfiguration('repeatEventManagement', 'never');
		}
		$cmd->setType('info');
		$cmd->setSubType('binary');
		$cmd->setEqLogic_id($this->getId());
		$cmd->save();

		$cmd = $this->getCmd(null, 'mute');
		if (!is_object($cmd)) {
			$cmd = new gcastcmd();
			$cmd->setLogicalId('mute');
			$cmd->setIsVisible(1);
			$cmd->setName(__('Muet', __FILE__));
		}
		$cmd->setType('action');
		$cmd->setSubType('other');
		$cmd->setEqLogic_id($this->getId());
		$cmd->save();

		$cmd = $this->getCmd(null, 'unmute');
		if (!is_object($cmd)) {
			$cmd = new gcastcmd();
			$cmd->setLogicalId('unmute');
			$cmd->setIsVisible(1);
			$cmd->setName(__('Désactiver muet', __FILE__));
		}
		$cmd->setType('action');
		$cmd->setSubType('other');
		$cmd->setEqLogic_id($this->getId());
		$cmd->save();

		$cmd = $this->getCmd(null, 'application');
		if (!is_object($cmd)) {
			$cmd = new gcastcmd();
			$cmd->setLogicalId('application');
			$cmd->setIsVisible(1);
			$cmd->setName(__('Application en cours', __FILE__));
		}
		$cmd->setType('info');
		$cmd->setSubType('string');
		$cmd->setEqLogic_id($this->getId());
		$cmd->save();

		$cmd = $this->getCmd(null, 'status');
		if (!is_object($cmd)) {
			$cmd = new gcastcmd();
			$cmd->setLogicalId('status');
			$cmd->setIsVisible(1);
			$cmd->setName(__('Status', __FILE__));
		}
		$cmd->setType('info');
		$cmd->setSubType('string');
		$cmd->setEqLogic_id($this->getId());
		$cmd->save();

		$cmd = $this->getCmd(null, 'volume_lvl');
		if (!is_object($cmd)) {
			$cmd = new gcastcmd();
			$cmd->setLogicalId('volume_lvl');
			$cmd->setIsVisible(1);
			$cmd->setName(__('Niveau du volume', __FILE__));
			$cmd->setTemplate('dashboard', 'line');
		}
		$cmd->setType('info');
		$cmd->setSubType('numeric');
		$cmd->setEqLogic_id($this->getId());
		$cmd->save();
	}

	/*     * **********************Getteur Setteur*************************** */
}

class gcastCmd extends cmd {
	/*     * *************************Attributs****************************** */

	/*     * ***********************Methode static*************************** */

	/*     * *********************Methode d'instance************************* */

	public function execute($_options = null) {
		if ($this->getType() != 'action') {
			return '';
		}
		$cc = $this->getEqLogic()->getChromecast();
		if ($this->getLogicalId() == 'parle') {
			if (!is_array($_options) || isset($_options['message']) || trim($_options['message']) == '') {
				return;
			}
			try {
				$cc->DMP->play(network::getNetworkAccess('internal') . '/core/api/tts.php?apikey=' . jeedom::getApiKey('apitts') . '&text=' . urlencode($_options['message']), "BUFFERED", "audio/mpeg", true, 0);
			} catch (\Exception $e) {
				log::add('gcast', 'error', __('Erreur sur la commande : ', __FILE__) . $this->getHumanName() . '  => ' . json_encode($_options) . ' ' . $e->getMessage());
				throw $e;
			}
		} else if ($this->getLogicalId() == 'volume') {
			if ($_options['slider'] < 0) {
				$_options['slider'] = 0;
			} else if ($_options['slider'] > 100) {
				$_options['slider'] = 100;
			}
			$cc->DMP->SetVolume($_options['slider'] / 100);
		} else if ($this->getLogicalId() == 'mute') {
			$cc->DMP->Mute();
		} else if ($this->getLogicalId() == 'unmute') {
			$cc->DMP->UnMute();
		}
		$this->getEqLogic()->updateData();
	}

	/*     * **********************Getteur Setteur*************************** */
}
