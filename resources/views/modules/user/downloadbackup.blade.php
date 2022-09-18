@php

$DATABASE="payma7bk_paymama";
$DBUSER="payma7bk_naidu";
$DBPASSWD="J7d]&+p7N*]+";
$PATH=public_path()."/";

$FILE_NAME="paymamabackup".".sql";
$command = "mysqldump --user=" . $DBUSER ." --password=" . $DBPASSWD . " --host=localhost" . $DATABASE . " | gzip > " . $PATH . $FILE_NAME;
 $returnVar = NULL;
        $output = NULL;


        exec($command, $output, $returnVar);
@endphp
<a href="paymamabackup.sql" download><button type="button">Download Backup</button></a>
<br><br>
<br>
<a href="home.php"><button type="button">Back To Dashboard</button></a>