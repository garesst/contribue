<?php

namespace Commons;

class Messenger
{
    function notEndpoint($name){
        header('Content-Type: text/html; charset=utf-8');
        http_response_code(404);
        echo json_encode(array("error" => "Endpoint $name no encontrado"));
        exit();
    }

    function notFunction($name){
        header('Content-Type: text/html; charset=utf-8');
        http_response_code(404);
        echo json_encode(array("error" => "Method $name not allowed"));
        exit();
    }

    function msgHtmlWithIcon($title,$msg,$icon)
    {
        return "Swal.fire({title: \"$title\",text: \"$msg\",icon: \"$icon\"});";
    }

    function msgHtmlWithIconAndTimer($title,$msg,$icon){
        return "let timerInterval;Swal.fire({title: \"$title\",text: \"$msg\",icon: \"$icon\",allowOutsideClick: false,allowEscapeKey: false,allowEnterKey: false,timer: 2000,timerProgressBar: true,  didOpen: () => {Swal.showLoading();}}).then((result)=>{if(result.dismiss===Swal.DismissReason.timer){window.location.reload();}});";
    }

    function msgWithAction($title,$msg,$icon,$confirmText,$denyText,$denyAction,$confirmAction){
        return "Swal.fire({title: \"$title\",text: \"$msg\",icon: \"$icon\",showDenyButton: true,showCancelButton: false,confirmButtonText: \"$confirmText\",confirmButtonColor: \"#06b6d4\", denyButtonText: `$denyText`}).then((result) => {if (result.isConfirmed){ {$confirmAction} }else if (result.isDenied){ {$denyAction}}});";
    }
}