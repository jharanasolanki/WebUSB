<!doctype html>
<html>

<head>
	<meta charset="UTF-8">
	<link rel="shortcut icon" href="./.favicon.ico">
	<title>Directory Contents</title>

	<link rel="stylesheet" href="./.style.css">
	<script src="./.sorttable.js"></script>
</head>

<body>
	<div id="container">
		<h1>Directory Contents</h1>

		<table class="sortable">
			<thead>
				<tr>
					<th>Filename</th>
					<th>Type</th>
					<th>Size</th>
					<th>Date Modified</th>
					<th>Download</th>
				</tr>
			</thead>
			<tbody><?php

					// Adds pretty filesizes
					function pretty_filesize($file)
					{
						$size = filesize($file);
						if ($size < 1024) {
							$size = $size . " Bytes";
						} elseif (($size < 1048576) && ($size > 1023)) {
							$size = round($size / 1024, 1) . " KB";
						} elseif (($size < 1073741824) && ($size > 1048575)) {
							$size = round($size / 1048576, 1) . " MB";
						} else {
							$size = round($size / 1073741824, 1) . " GB";
						}
						return $size;
					}

					// Checks to see if veiwing hidden files is enabled
					if (isset($_SERVER['QUERY_STRING'])) {
						if ($_SERVER['QUERY_STRING'] == "hidden") {
							$hide = "";
							$ahref = "./";
							$atext = "Hide";
						}
					} else {
						$hide = ".";
						$ahref = "./?hidden";
						$atext = "Show";
					}

					// Opens directory
					$myDirectory = opendir(".");

					// Gets each entry
					while ($entryName = readdir($myDirectory)) {
						$dirArray[] = $entryName;
					}

					// Closes directory
					closedir($myDirectory);

					// Counts elements in array
					$indexCount = count($dirArray);

					// Sorts files
					sort($dirArray);

					// Loops through the array of files
					for ($index = 0; $index < $indexCount; $index++) {

						// Decides if hidden files should be displayed, based on query above.
						if (substr("$dirArray[$index]", 0, 1) != $hide) {

							// Resets Variables
							$favicon = "";
							$class = "file";

							// Gets File Names
							$name = $dirArray[$index];
							$namehref = $dirArray[$index];

							// Gets Date Modified
							$modtime = date("M j Y g:i A", filemtime($dirArray[$index]));
							$timekey = date("YmdHis", filemtime($dirArray[$index]));


							// Separates directories, and performs operations on those directories
							if (is_dir($dirArray[$index])) {
								$extn = "&lt;Directory&gt;";
								$size = "&lt;Directory&gt;";
								$sizekey = "0";
								$class = "dir";

								// Gets favicon.ico, and displays it, only if it exists.
								if (file_exists("$namehref/favicon.ico")) {
									$favicon = " style='background-image:url($namehref/favicon.ico);'";
									$extn = "&lt;Website&gt;";
								}

								// Cleans up . and .. directories
								if ($name == ".") {
									$name = ". (Current Directory)";
									$extn = "&lt;System Dir&gt;";
									$favicon = " style='background-image:url($namehref/.favicon.ico);'";
								}
								if ($name == "..") {
									$name = ".. (Parent Directory)";
									$extn = "&lt;System Dir&gt;";
								}
							}

							// File-only operations
							else {
								// Gets file extension
								$extn = pathinfo($dirArray[$index], PATHINFO_EXTENSION);

								// Prettifies file type
								switch ($extn) {
									case "png":
										$extn = "PNG Image";
										break;
									case "jpg":
										$extn = "JPEG Image";
										break;
									case "jpeg":
										$extn = "JPEG Image";
										break;
									case "svg":
										$extn = "SVG Image";
										break;
									case "gif":
										$extn = "GIF Image";
										break;
									case "ico":
										$extn = "Windows Icon";
										break;

									case "txt":
										$extn = "Text File";
										break;
									case "log":
										$extn = "Log File";
										break;
									case "htm":
										$extn = "HTML File";
										break;
									case "html":
										$extn = "HTML File";
										break;
									case "xhtml":
										$extn = "HTML File";
										break;
									case "shtml":
										$extn = "HTML File";
										break;
									case "php":
										$extn = "PHP Script";
										break;
									case "js":
										$extn = "Javascript File";
										break;
									case "css":
										$extn = "Stylesheet";
										break;

									case "pdf":
										$extn = "PDF Document";
										break;
									case "xls":
										$extn = "Spreadsheet";
										break;
									case "xlsx":
										$extn = "Spreadsheet";
										break;
									case "doc":
										$extn = "Microsoft Word Document";
										break;
									case "docx":
										$extn = "Microsoft Word Document";
										break;

									case "zip":
										$extn = "ZIP Archive";
										break;
									case "htaccess":
										$extn = "Apache Config File";
										break;
									case "exe":
										$extn = "Windows Executable";
										break;

									default:
										if ($extn != "") {
											$extn = strtoupper($extn) . " File";
										} else {
											$extn = "Unknown";
										}
										break;
								}

								// Gets and cleans up file size
								$size = pretty_filesize($dirArray[$index]);
								$sizekey = filesize($dirArray[$index]);
							}

							// Output
							echo ("
		<tr class='$class'>
			<td><a href='./$namehref'$favicon class='name'>$name</a>
			</td>
			<td><div>$extn</div></td>
			<td sorttable_customkey='$sizekey'><a href='./$namehref'>$size</a></td>
			<td sorttable_customkey='$timekey'><a href='./$namehref'>$modtime</a></td>
			<td ><div onclick='downloadFile(this)' id='$namehref'>DOwnload</div></td>
		</tr>");
						}
					}
					?>
				

			</tbody>
		</table>

		<h2><?php echo ("<a href='$ahref'>$atext hidden files</a>"); ?></h2>
		<form action="fileupload.php" method="post" enctype="multipart/form-data">
			Select file to upload:
			<input type="file" name="fileToUpload" id="fileToUpload">
			<input type="submit" value="Upload File" name="submit">
		</form><br>
	</div>

</body>
<script type="text/javascript" language="javascript">
function downloadFile(str) {
	alert(str);
	str=str.id;
	alert(str);
        if (str.length != 0) {
            var xmlhttp = new XMLHttpRequest();
            //when the open() function state is changed this function is called
            xmlhttp.onreadystatechange = function() {
                //checks if the open() function state is changed to complete
                if (this.readyState == 4 && this.status == 200) {
                    allowed = (this.responseText); // stores answer returned by open function
                    if (allowed == 0)
                        {document.getElementById("errMsg").innerHTML = "error";
                        document.getElementById("errMsg").style.display = "block";
                        Console.log("hey");
                        }
                    else
                       alert(allowed);
                }
            };
            // the username entered by the user is sent to the php script as a querystring
            xmlhttp.open("GET", "download.php?q=" + str, true);
            xmlhttp.send();
        }
	}
</script>
</html>