<?php
/*
    PufferPanel - A Minecraft Server Management Panel
    Copyright (c) 2013 Dane Everitt
 
    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.
 
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
 
    You should have received a copy of the GNU General Public License
    along with this program.  If not, see http://www.gnu.org/licenses/.
 */
session_start();
require_once('../../src/framework/framework.core.php');

if($core->auth->isLoggedIn($_SERVER['REMOTE_ADDR'], $core->auth->getCookie('pp_auth_token'), $core->auth->getCookie('pp_server_hash')) === true){

	if(isset($_POST['server'])){
		
		/*
		 * Query Database
		 */
		$select = $mysql->prepare("SELECT * FROM `servers` WHERE `id` = :id AND `owner_id` = :oid");
		$select->execute(array(
			':id' => $_POST['server'],
			':oid' => $core->user->getData('id')
		));
		
			$server = $select->fetch();

		$select = $mysql->prepare("SELECT * FROM `nodes` WHERE `id` = :id");
		$select->execute(array(
			':id' => $server['node'],
		));
		
			$node = $select->fetch();
		
		/*
		 * Query Servers
		 */
		if($core->gsd->check_status($node['node_ip'], $server['gsd_id'], $node['gsd_secret']) === false)
			exit('<span class="label label-danger">'.$_l->tpl('string_offline').'</span>');
		else
			exit('<span class="label label-success">'.$_l->tpl('string_online').'</span>');
		
	}else{
	
		exit('<span class="label label-danger">No ID#</span>');
	
	}

}else{

	exit('<span class="label label-danger">Auth Error</span>');

}

?>
