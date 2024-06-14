<?php
Session_Start();
$_Session['r']=0;
$_Session['base']= $_Get['base'];
$_Session['altura']= $_Get['altura'];

$_Session['r']= ($_Session['base']*$_Session['altura'])/2;
