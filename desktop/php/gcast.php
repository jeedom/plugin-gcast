<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$plugin = plugin::byId('gcast');
sendVarToJS('eqType', $plugin->getId());
$eqLogics = eqLogic::byType($plugin->getId());
?>
<div class="row row-overflow">
	<div class="col-xs-12 eqLogicThumbnailDisplay">
		<legend><i class="fas fa-cog"></i>  {{Gestion}}</legend>
		<div class="eqLogicThumbnailContainer">
			<div class="cursor eqLogicAction logoPrimary" data-action="add"  >
				<i class="fas fa-plus-circle"></i>
				<br>
				<span>{{Ajouter}}</span>
			</div>
			<div class="cursor" id="bt_healthgcast"  >
				<i class="fas fa-medkit"></i>
				<br>
				<span >{{Santé}}</span>
			</div>
		</div>
		<legend><i class="icon techno-cable1"></i>  {{Mes gcasts}}
		</legend>
		<div class="eqLogicThumbnailContainer">
			<?php
			foreach ($eqLogics as $eqLogic) {
				$opacity = '';
				if ($eqLogic->getIsEnable() != 1) {
					$opacity = 'opacity:0.3;';
				}
				echo '<div class="eqLogicDisplayCard cursor '.$opacity.'" data-eqLogic_id="' . $eqLogic->getId() . '">';
				echo '<img src="' . $plugin->getPathImgIcon() . '" height="105" width="105" />';
				echo "<br>";
				echo '<span>' . $eqLogic->getHumanName(true, true) . '</span>';
				echo '</div>';
				$url = network::getNetworkAccess('external') . '/plugins/gcast/core/php/gcastApi.php?apikey=' . jeedom::getApiKey('gcast') . '&id=' . $eqLogic->getId();
			}
			?>
		</div>
	</div>
	<div class="col-xs-12 eqLogic" style="display: none;">
		<div class="input-group pull-right" style="display:inline-flex">
			<span class="input-group-btn">
				<a class="btn btn-default btn-sm eqLogicAction roundedLeft" data-action="configure"><i class="fas fa-cogs"></i> {{Configuration avancée}}</a><a class="btn btn-default btn-sm eqLogicAction" data-action="copy"><i class="fas fa-copy"></i> {{Dupliquer}}</a><a class="btn btn-sm btn-success eqLogicAction" data-action="save"><i class="fas fa-check-circle"></i> {{Sauvegarder}}</a><a class="btn btn-danger btn-sm eqLogicAction roundedRight" data-action="remove"><i class="fas fa-minus-circle"></i> {{Supprimer}}</a>
			</span>
		</div>
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation"><a href="#" class="eqLogicAction" aria-controls="home" role="tab" data-toggle="tab" data-action="returnToThumbnailDisplay"><i class="fas fa-arrow-circle-left"></i></a></li>
			<li role="presentation" class="active"><a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-tachometer-alt"></i> {{Equipement}}</a></li>
			<li role="presentation"><a href="#commandtab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fas fa-list-alt"></i> {{Commandes}}</a></li>
		</ul>
		<div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">
			<div role="tabpanel" class="tab-pane active" id="eqlogictab">
				<br/>
				<form class="form-horizontal">
					<fieldset>
						<div class="form-group">
							<label class="col-lg-3 control-label">{{Nom de l'équipement}}</label>
							<div class="col-lg-4">
								<input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
								<input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'équipement}}"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label" >{{Objet parent}}</label>
							<div class="col-lg-4">
								<select id="sel_object" class="eqLogicAttr form-control" data-l1key="object_id">
									<option value="">{{Aucun}}</option>
									<?php
									foreach (object::all() as $object) {
										echo '<option value="' . $object->getId() . '">' . $object->getName() . '</option>';
									}
									?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label">{{Catégorie}}</label>
							<div class="col-lg-9">
								<?php
								foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
									echo '<label class="checkbox-inline">';
									echo '<input type="checkbox" class="eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" />' . $value['name'];
									echo '</label>';
								}
								?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label"></label>
							<div class="col-sm-9">
								<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked/>{{Activer}}</label>
								<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked/>{{Visible}}</label>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label">{{Adresse IP}}</label>
							<div class="col-lg-4">
								<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="addr" placeholder="{{Adresse IP}}"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label">{{URL de retour}}</label>
							<div class="alert alert-warning col-lg-6">
								<span><?php echo network::getNetworkAccess('external') . '/plugins/gcast/core/php/gcastApi.php?apikey=' . config::byKey('api', 'gcast') . '&id=#ID_EQUIPEMENT#&query=XXXX'; ?></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label">{{Moteur TTS:}}</label>
							<div class="col-lg-3">
								<select id="moteurtts" class="form-control eqLogicAttr" data-l1key="configuration" data-l2key="moteurtts" onchange="javascript:showVoiceOption(this.value);">
									<option value="picotts">{{PicoTTS}}</option>
									<option value="google">{{Google}}</option>
									<option value="jeedom">{{Jeedom}}</option>
									<?php // ABA: ajout TTSWebServer //
									if (config::byKey('active', 'ttsWebServer', 0) == 1) {
										echo '<option value="ttswebserver">{{TTS WebServer (plugin)}}</option>';
									}
									
									?>
								</select>
							</div>
							<div class='voiceOption picotts'>
								<br/><br/><br/>
								<label class="col-lg-3 control-label">{{Voix PicoTTS:}}</label>
								<div class="col-lg-3">
									<select id="picoopt" class="form-control eqLogicAttr" data-l1key="configuration" data-l2key="picovoice">
										<option value="fr-FR">{{Français}}</option>
										<option value="de-DE">{{Allemand}}</option>
										<option value="en-US">{{Américain}}</option>
										<option value="en-GB">{{Anglais}}</option>
										<option value="es-ES">{{Espagnol}}</option>
										<option value="it-IT">{{Italien}}</option>
									</select>
								</div>
							</div>
							<div class='voiceOption google'>
								<br/><br/><br/>
								<label class="col-lg-3 control-label">{{Voix Google:}}</label>
								<div class="col-lg-3">
									<select id="googleopt" class="form-control eqLogicAttr" data-l1key="configuration" data-l2key="googlevoice">
										<option value="fr">Français</option>
										<option value="af">Afrikaans</option>
										<option value="sq">Albanian</option>
										<option value="ar">Arabic</option>
										<option value="hy">Armenian</option>
										<option value="ca">Catalan</option>
										<option value="zh-CN">Mandarin (simplified)</option>
										<option value="zh-TW">Mandarin (traditional)</option>
										<option value="hr">Croatian</option>
										<option value="cs">Czech</option>
										<option value="da">Danish</option>
										<option value="nl">Dutch</option>
										<option value="en">English</option>
										<option value="en-us">English (United States)</option>
										<option value="en-au">English (Australia)</option>
										<option value="eo">Esperanto</option>
										<option value="fi">Finnish</option>
										<option value="de">German</option>
										<option value="el">Greek</option>
										<option value="ht">Haitian Creole</option>
										<option value="hi">Hindi</option>
										<option value="hu">Hungarian</option>
										<option value="is">Icelandic</option>
										<option value="id">Indonesian</option>
										<option value="it">Italian</option>
										<option value="ja">Japanese</option>
										<option value="ko">Korean</option>
										<option value="la">Latin</option>
										<option value="lv">Latvian</option>
										<option value="mk">Macedonian</option>
										<option value="no">Norwegian</option>
										<option value="pl">Polish</option>
										<option value="pt">Portuguese</option>
										<option value="ro">Romanian</option>
										<option value="ru">Russian</option>
										<option value="sr">Serbian</option>
										<option value="sk">Slovak</option>
										<option value="es">Spanish</option>
										<option value="sw">Swahili</option>
										<option value="sv">Swedish</option>
										<option value="ta">Tamil</option>
										<option value="th">Thai</option>
										<option value="tr">Turkish</option>
										<option value="vi">Vietnamese</option>
										<option value="cy">Welsh</option>
									</select>
								</div>
							</div>
							<div class='voiceOption ttswebserver'>
								<br/><br/><br/>
								<label class="col-lg-3 control-label">{{Voix TTS WebServer:}}</label>
								<div class="col-lg-3">
									<?php // ABA: ajout TTSWebServer //
									if (config::byKey('active', 'ttsWebServer', 0) == 1) {
										$_aTWSVoiceList = ttsWebServer::getVoicesList();
										print_r($_aTWSVoiceList, 1);
										echo "<select id=\"ttswsopt\" class=\"form-control eqLogicAttr\" data-l1key=\"configuration\" data-l2key=\"ttswsvoice\">";
										for ($i = 0; $i < count($_aTWSVoiceList); $i++) {
											echo "<option value=\"" . $_aTWSVoiceList[$i]['eqLogicId'] . "|" . $_aTWSVoiceList[$i]['voice'] . "\">[" . $_aTWSVoiceList[$i]['eqLogicName'] . "] " . $_aTWSVoiceList[$i]['voice'] . "</option>";
										}
										echo "</select>";
									} else {
										echo "Le plugin TTS WebServer n'est pas actif";
									}
									?>
								</div>
							</div>
						</div>
					</fieldset>
				</form>
			</div>
			<div role="tabpanel" class="tab-pane" id="commandtab">
				<table id="table_cmd" class="table table-bordered table-condensed">
					<thead>
						<tr>
							<th>{{Nom}}</th><th>{{Options}}</th><th>{{Action}}</th>
						</tr>
					</thead>
					<tbody>
						
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<?php include_file('desktop', 'gcast', 'js', 'gcast');?>
<?php include_file('core', 'plugin.template', 'js');?>
