<?php

Session_Start();
if(isset($_SESSION['r'])){
    echo "El resultado es: ".$_SESSION['r'];
}

