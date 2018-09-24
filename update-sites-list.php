#!/usr/local/bin/php
<?php

echo "<html>\n";
echo "<head>\n";
echo "<title>Baskin School of Engineering Site List</title>\n";
echo "</head>\n";
echo "<body>\n";
echo "<ul>\n";

$count = 0;

$config_files = glob("/usr/local/etc/apache24/sites/*.conf");

foreach($config_files as $config_file) {
  $host_name = pathinfo($config_file, PATHINFO_FILENAME);

  $parts = preg_split(
    "/\./",
    $host_name
  );

  $url =
    "http://" .
    $host_name .
    "/";

  $curl = curl_init($url);

  curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); 
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); 
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_TIMEOUT, 15);
  curl_setopt($curl, CURLOPT_USERAGENT, "BSOE Web Crawler");

  $html = curl_exec($curl);

  $info = curl_getinfo($curl);

  if ($info["http_code"] == 200) {
    $count++;

    if (!empty($info["redirect_count"])) {
      $url = curl_getinfo($curl, CURLINFO_EFFECTIVE_URL);
    }

    $title = false;

    if ($html != "") {
      $dom = new DomDocument();

      $success = @$dom->loadHtml($html);

      if ($success) {
        $titles = $dom->getElementsByTagName("title");

        if ($titles->length != 0) {
          $title = $titles->item(0)->nodeValue;
        }
      }
    }

    if (empty($title)) {
      $title = $url;
    }

    echo "<li><a href=\"" .
      $url .
      "\">" .
      $title .
      "</a></li>\n";
  }

  curl_close($curl);
}

echo "</ul>\n";

echo
  "<p>There are " .
  number_format($count) .
  " sites listed on this page.</p>\n";

echo "</body>\n";
echo "</html>\n";

?>
