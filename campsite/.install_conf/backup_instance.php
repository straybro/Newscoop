<?php

$etc_dir = trim($GLOBALS['argv'][1]);
$instance_name = trim($GLOBALS['argv'][2]);

if ($etc_dir == "")
	die("Please supply the configuration directory as the first argument.\n");
if ($instance_name == "")
	die("Please supply the instance name as the second argument.\n");

// include install_conf.php file
require_once("$etc_dir/install_conf.php");
require_once("$etc_dir/$instance_name/database_conf.php");
$html_dir = $Campsite['WWW_DIR'] . "/$instance_name/html";
$backup_dir = $Campsite['CAMPSITE_DIR'] . "/backup/$instance_name";

// backup look (templates) directory
if (archive_file("$html_dir/look", $backup_dir, "$instance_name-look", $output) != 0)
	exit_with_error($output);

// backup images directory
if (archive_file("$html_dir/images", $backup_dir, "$instance_name-images", $output) != 0)
	exit_with_error($output);

// backup configuration directory
if (archive_file("$etc_dir/$instance_name", $backup_dir, "$instance_name-conf", $output) != 0)
	exit_with_error($output);

// backup the database
$db_file_name = "$backup_dir/$instance_name-database.sql";
if (is_file($db_file_name) && (backup_file($db_file_name, $output) != 0))
	exit_with_error($output);
if (backup_database($instance_name, $db_file_name, $output) != 0)
	exit_with_error($output);
if (archive_file($db_file_name, $backup_dir, "$instance_name-database", $output) != 0)
	exit_with_error($output);

// create the final archive
$cmd = "pushd $backup_dir > /dev/null && tar cf "
	. escapeshellarg("$instance_name-bak.tar")
	. " *.tar.gz && popd > /dev/null";
exec($cmd, $output, $result);
if ($result != 0)
	exit_with_error($output);
unlink("$backup_dir/$instance_name-conf.tar.gz");
unlink("$backup_dir/$instance_name-images.tar.gz");
unlink("$backup_dir/$instance_name-look.tar.gz");
unlink("$backup_dir/$instance_name-database.tar.gz");
unlink("$backup_dir/$instance_name-database.sql");


function backup_file($file_path, &$output)
{
	if (!is_file($file_path)) {
		$output = "File $file_path does not exist.";
		return 1;
	}
	$dir_name = dirname($file_path);
	if (!($file_stat = @stat($file_path))) {
		$output = "Unable to read file $file_path data.";
		return 1;
	}
	$file_name = substr($file_path, strlen($dir_name) + 1);
	if ($dot_pos = strrpos($file_name, '.')) {
		$base_name = substr($file_name, 0, $dot_pos);
		$extension = substr($file_name, $dot_pos);
	} else {
		$base_name = $file_name;
		$extension = "";
	}
	$change_time = strftime("%Y-%m-%d-%H", $file_stat['ctime']);
	$new_name = "$base_name-$change_time$extension";

	if (is_file("$dir_name/$new_name"))
		return 0;

	if (!rename($file_path, "$dir_name/$new_name")) {
		$output = "Unable to rename file $file_path";
		return 1;
	}
	return 0;
}

function archive_file($source_file, $dest_dir, $file_name, &$output)
{
	$source_dir = dirname($source_file);
	$source_file_name = substr($source_file, strlen($source_dir) + 1);
	$cmd = "pushd $source_dir > /dev/null && tar czf "
		. escapeshellarg("$dest_dir/$file_name.tar.gz")
		. " " . escapeshellarg($source_file_name) . " && popd > /dev/null";
	exec($cmd, $output, $result);
	return $result;
}

function backup_database($db_name, $dest_file, &$output)
{
	global $Campsite;

	$user = $Campsite['DATABASE_USER'];
	$password = $Campsite['DATABASE_PASSWORD'];
	$cmd = "mysqldump --add-drop-table -c -e -Q -u $user";
	if ($password != "")
		$cmd .= " -p=$password";
	$cmd .= " $db_name > $dest_file";
	exec($cmd, $output, $result);
	return $result;
}

function exit_with_error($error_str)
{
	if (is_array($error_str))
		$error_str = implode("\n", $error_str);
	echo "$error_str\n";
	exit(1);
}

?>
