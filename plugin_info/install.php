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

require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';

function gcast_update() {
	foreach (gcast::byType('gcast') as $gcast) {
		$gcast->save();
		foreach (array('voldown','volup') as $logicalId) {
			$cmd = $gcast->getCmd('action',$logicalId);
			if(is_object($cmd)){
				$cmd->remove();
			}
		}
		$cmd = $gcast->getCmd(null, 'mute_state');	
		$cmd->setConfiguration('repeatEventManagement','never');
		$cmd->save();
	}
	jeedom::getApiKey('gcast');
}
?>
