<?php
    require ( "../includes/AwwCore.class.php" );

    if ( isset( $_REQUEST[ 'text' ] ) ) {
        if ( !empty( $_REQUEST[ 'text' ] ) ) {
            header ( 'Content-Type: application/json' );
            $text = $_REQUEST[ 'text' ];
            $pasteDetails = AwwCore::CreatePaste ( $text );
            $pasteDetails[ 'code' ] = 201;
            echo json_encode ( $pasteDetails );
        } else {
            $error = array (
                "error" => "You haven't given us anything to paste. WTF, man?",
                "code" => 1337
            );
            echo json_encode ( $error );
        }
    } else {
        $error = array (
            "error" => "Bad request",
            "code" => 400
        );
        echo json_encode ( $error );
    }
