<?php

    $show_errors = true;
    require ( "./includes/AwwCore.class.php" );
    include ( "./includes/errors.inc.php" );


    print( "<br>[*] [C]RD TEST - CREATE<br> " );
    $data = " For the brave souls who get this far: You are the chosen ones, the valiant knights of programming who toil away, without rest, fixing our most awful code. To you, true saviors, kings of men, I say this: never gonna give you up, never gonna let you down, never gonna run around and desert you. Never gonna make you cry, never gonna say goodbye. Never gonna tell a lie and hurt you.";
    print( "<br> [*] Creating the paste file .." );
    $pasteDetails = AwwCore::CreatePaste ( $data );
    print( "<br> [*] PasteID: " . $pasteDetails[ 'pasteID' ] );
    print( "<br> [*] Delete Key: " . $pasteDetails[ 'deleteKey' ] . "<br>" );


    print( "<br>[*] C[R]D TEST - READ <br>" );
    $outputContents = AwwCore::GetPaste ( $pasteDetails[ 'pasteID' ] );
    if ( !$outputContents ) {
        print( "<br>[!] An error occured while reading the file<br>" );
    } else {
        print( "<br>[*] Reading paste: <br>" );
        print( "<code> $outputContents </code><br>" );

    }


    print( "<br>[*] CR[D] TEST - DELETE<br>" );
    print( "<br>[*] Deleting paste .." );

    $deleteStatus = AwwCore::DeletePaste ( $pasteDetails[ 'pasteID' ], $pasteDetails[ 'deleteKey' ] );
    if ( $deleteStatus ) {
        print( "<br>[*]  Successfully deleted .." );
    } else {
        print( "<br>[!] Error while deleting paste ..<br>" );
    }
