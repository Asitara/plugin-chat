<?php
/*	Project:	EQdkp-Plus
 *	Package:	Chat Plugin
 *	Link:		http://eqdkp-plus.eu
 *
 *	Copyright (C) 2006-2016 EQdkp-Plus Developer Team
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU Affero General Public License as published
 *	by the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU Affero General Public License for more details.
 *
 *	You should have received a copy of the GNU Affero General Public License
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

if (!defined('EQDKP_INC'))
{
  header('HTTP/1.0 404 Not Found');exit;
}


/*+----------------------------------------------------------------------------
  | chat_portal_hook
  +--------------------------------------------------------------------------*/
if (!class_exists('chat_portal_hook'))
{
  class chat_portal_hook extends gen_class
  {

	/**
    * hook_portal
    * Do the hook 'portal'
    *
    * @return array
    */
	public function portal()
	{		
		if ($this->user->check_auth("u_chat_view", false) && $this->core->header_format == 'full' && $this->user->is_signedin()){
			$this->tpl->js_file($this->root_path.'plugins/chat/includes/js/jquery.tokeninput.js');
			$this->tpl->js_file($this->root_path.'plugins/chat/includes/js/chat.js');
			$this->tpl->css_file($this->root_path.'plugins/chat/templates/base_template/chat.css');
			
			//In Secods
			$intReloadTime = ($this->config->get('reload_chat', 'chat')) ? $this->config->get('reload_chat', 'chat') : 5;
			//In Minutes
			$intReloadOnlineList = ($this->config->get('reload_onlinelist', 'chat')) ? $this->config->get('reload_onlinelist', 'chat') : 5;
			
			$this->tpl->add_js("EQdkpChat.init(".$intReloadTime.", ".$intReloadOnlineList.",".json_encode(array('isModerator' => (($this->user->check_auth('u_chat_mod_pub', false)) ? 1 : 0), 'lang_read' => $this->user->lang('chat_read'), 'play_sounds' => ($this->config->get('new_message_sound', 'chat') ? 1 : 0))).");
				$('.chat-tooltip-trigger').on('click', function(event){
					$('#chat-tooltip').show('fast');
					$('.chatTooltipRemove').remove();
					$('.chatTooltipUnread').show();
					$.get(mmocms_root_path+ \"plugins/chat/ajax.php\"+mmocms_sid+\"&unreadTooltip\", function(data){
						$('.chatTooltipUnread').hide();
						$('.chatTooltipUnread').parent().prepend(data);
					});
					$(document).on('click', function(event) {
						var count = $(event.target).parents('.chat-tooltip-container').length;									
						if (count == 0){
							$('.chat-tooltip').hide('fast');
						}
					});
					
				});
					
			", "docready");
			
			$this->tpl->assign_block_vars("personal_area_addition", array(
				"TEXT" => '<div class="chat-tooltip-container"><a href="'.register("routing")->build("chathistory").'"><i class="fa fa-comments fa-lg"></i><span class="hiddenSmartphone">'.$this->user->lang('chat').'</span></a> 
									<div class="notification-tooltip-container">
									<span class="bubble-green chat-tooltip-trigger hand"></span>
									<ul class="dropdown-menu chat-tooltip" role="menu" id="chat-tooltip">
										<li class="chatTooltipUnread"><div style="text-align:center;"><i class="fa-spin fa fa-spinner fa-lg"></i></div></li>
										<li class="tooltip-divider"></li>
										<li><a href="'.register("routing")->build("chathistory").'">'.$this->user->lang('chat_all_conversations').'</a></li>
									</ul>
								</div>
							</div>',
			));
			
			$this->tpl->staticHTML('<div class="chatContainer">
										<div id="chatMenu" class="chatFloat">
											<div id="chatOnlineMinimized" class="chatWindowMin">
												<i class="fa fa-comments"></i> '.$this->user->lang('chat').' (<span class="chatOnlineCount">0</span>)
											</div>
											<div id="chatOnlineMaximized" class="chatWindowContainer" style="display:none;">
												<div class="chatWindowMenu">
													<div class="chatWindowHeader2">
														<i class="fa fa-comments fa-lg"></i> Chat <i class="fa fa-times floatRight hand"></i>
													</div>
													<div class="chatWindowContent">
														<div class="chatOnlineList"></div>
														<div class="clear"></div>
													</div>
													<div class="clear"></div>
													<div class="chatInput">
														<input type="text" id="chatOnlineSearch" placeholder="'.$this->user->lang('chat_filter_user').'" />
													</div>
												</div>
											</div>
										</div>
										<div id="chatWindows"><div id="chatWindowList" class="chatFloat"></div></div>
									</div>');
			
		}
	}
  }
}
?>