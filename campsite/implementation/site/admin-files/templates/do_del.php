<?php
require_once($GLOBALS['g_campsiteDir']. "/$ADMIN_DIR/templates/template_common.php");

if (!$g_user->hasPermission('DeleteTempl')) {
	camp_html_display_error(getGS("You do not have the right to delete templates."));
	exit;
}

$Path = Input::Get('Path', 'string', '');
$Name = Input::Get('Name', 'string', '');
$isFile = Input::Get('What', 'int', 0);

if (!Template::IsValidPath($Path)) {
	camp_html_goto_page("/$ADMIN/templates/");
}

$backLink = "/$ADMIN/templates/?Path=".urlencode($Path);
$fileFullName = (!empty($Path)) ? urldecode($Path)."/".urldecode($Name) : urldecode($Name);
$fileFullPath = Template::GetFullPath(urldecode($Path), '');
$errorMsgs = array();


$deleted = false;
if (empty($Name)) {
	$deleted = is_dir($fileFullPath) && rmdir($fileFullPath);
	if ($deleted) {
		$logtext = getGS('Directory $1 was deleted', mysql_real_escape_string($fileFullName));
		Log::Message($logtext, $g_user->getUserId(), 112);
		camp_html_add_msg($logtext, "ok");
	} else {
		camp_html_add_msg(camp_get_error_message(CAMP_ERROR_RMDIR, $fileFullPath));
	}
} else {
	if (!Template::InUse($fileFullName)) {
		$template = new Template($fileFullName);
		if ($template->exists() && $template->delete()) {
			$logtext = getGS('Template object $1 was deleted', mysql_real_escape_string($fileFullName));
			Log::Message($logtext, $g_user->getUserId(), 112);
			camp_html_add_msg($logtext, "ok");
		} else {
			camp_html_add_msg(camp_get_error_message(CAMP_ERROR_DELETE_FILE, $fileFullName));
		}
	} else {
		camp_html_add_msg(getGS("The template object $1 is in use and can not be deleted.", $fileFullName));
	}
}

camp_html_goto_page($backLink);

?>
