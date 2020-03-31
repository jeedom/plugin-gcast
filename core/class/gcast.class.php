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
	
	public static function cronHourly(){
		$processes = array_merge(system::ps('gcast/resources/caster/stream2chromecast.py'), system::ps('gcast/core/class/../../resources/action.py'));
		foreach ($processes as $process) {
			$duration = shell_exec('ps -p ' . $process['pid'] . ' -o etimes -h');
			if ($duration < 600) {
				continue;
			}
			system::kill($process['pid']);
		}
	}
	
	/*     * *********************Methode d'instance************************* */
	
	public function postSave() {
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
		$ip = $gcast->getConfiguration('addr');
		$cmd='sudo /usr/bin/python ' . dirname(__FILE__) . '/../../resources/caster/stream2chromecast.py  -devicename '. $ip;
		if ($action == 'parle') {
			$url = file_get_contents(network::getNetworkAccess('internal') . '/core/api/tts.php?apikey=' . config::byKey('api', 'core') . '&path=1&text=' . urlencode($_options['message']));
			$cmd .= ' "' . $url .'"' ;
		} else if ($action == 'volume') {
			if ($_options['slider'] < 0) {
				$_options['slider'] = 0;
			}
			if ($_options['slider'] > 100) {
				$_options['slider'] = 100;
			}
			$volume = $_options['slider'] / 100;
			$cmd .= ' -setvol ' . $volume ;
		} else {
			$cmd .= ' -' . $action ;
		}
		if (log::convertLogLevel(log::getLogLevel('gcast')) == 'debug') {
			$cmd .= ' >> ' . log::getPathToLog('gcast') . ' 2>&1 &';
		} else {
			$cmd .= ' > /dev/null 2>&1 &';
		}
		log::add('gcast', 'debug', $cmd);
		shell_exec($cmd);
	}
	
	/*     * **********************Getteur Setteur*************************** */
}
