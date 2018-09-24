#!/usr/local/bin/php
<?php

function is_dir_empty($dir) {
  if (!is_readable($dir)) return NULL; 
  return (count(scandir($dir)) == 2);
}

umask(0022);

if (!empty($argv[1])) {
  $folders = array("/www/" . $argv[1] . "/htdocs");
} else {
  $folders = glob("/www/*/drupal.db");
}

foreach($folders as $folder) {

  $match = preg_match(
    "|/www/(.*)|",
    $folder,
    $matches
  );

  if ($match != 1) {
    echo
      "ERROR: Could not extract host name from folder name: " .
      $folder .
      "\n";

    continue;
  } else {
    echo
      "We found the Drupal site: " .
      $folder .
      "\n";
  }

  $host_name = $matches[1];

  echo "================================================================================\n";
  echo $host_name . "\n";

  $folder = "/www/" . rtrim($host_name,"drupal.db") . "htdocs/";
  
  $modules_folder = $folder . "sites/default/modules";
  
  if (is_dir_empty($modules_folder)) {
    echo "Empty\n";
  } else {
    $modules_folder_results = passthru("ls -A " . $modules_folder);
    echo $modules_folder_results;
    passthru("echo " . $modules_folder . " >> results2.txt");
    passthru("ls -A " . $modules_folder . " >> results2.txt");
  }

  /*$commands = array(
    #"echo " . $host_name . " >> results.txt",
    #"/bin/ls " . $folder . "/sites/default/modules/ >> results.txt",
    #"/usr/local/bin/drush --root=" . escapeshellarg($folder) . " cr",
    #"/usr/local/bin/vim " .  escapeshellarg($folder . "testing-script.txt"),
    # "/bin/chmod 755 " . escapeshellarg($folder . "sites/default"),
    #"/bin/chmod 644 " . escapeshellarg($folder . "sites/default/settings.php"),
    #"/usr/local/bin/drush --root=" . escapeshellarg($folder) . " vset --exact maintenance_mode 1",
    #"/usr/local/bin/svn upgrade " . escapeshellarg($folder),
    #"/usr/local/bin/svn revert --depth=infinity " . escapeshellarg($folder),
    #"/usr/local/bin/svn update --accept working --username g-guest --password fwcONiGEyYH5eGrR " . escapeshellarg($folder),
    #"/usr/local/bin/svn cleanup " . escapeshellarg($folder),
    #"/usr/local/bin/drush --root=" . escapeshellarg($folder) . " --yes updatedb",
    #"/usr/local/bin/drush --root=" . escapeshellarg($folder) . " --yes cache-clear all",
    #"/usr/local/bin/drush --root=" . escapeshellarg($folder) . " vset --exact maintenance_mode 0",
    #"/bin/chown -R www:www " . escapeshellarg($folder),
  );

  foreach($commands as $command) {
    echo $command . "\n";

    passthru(
      $command,
      $exit_code
    );

    if ($exit_code != 0) {
      echo "--------------------------------------------------------------------------------\n";
      echo $command . "\n";
      echo "--------------------------------------------------------------------------------\n";
      echo "ERROR: Got exit code " . $exit_code . "!\n";
      echo "\n";
      readline("Please fix the problem and then press ENTER to continue: ");
      echo "\n";
    }

    echo "\n";
  }*/

  # readline("Press ENTER to continue: ");

  echo "Sleeping for 2 seconds...\n";

  sleep(2);
}

?>
