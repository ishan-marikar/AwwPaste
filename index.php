<?php
    $show_errors = true;
    require_once ( "./includes/errors.inc.php" );
    require_once ( "./includes/AwwCore.class.php" );
    require_once ( "./includes/config.inc.php" );


    // Initialize the flags that I'll be using
    if ( !isset( $isPaste ) ) {
        $isPaste = false;
    }
    if ( !isset( $isDelete ) ) {
        $isDelete = false;
    }
    if ( !isset( $isRaw ) ) {
        $isRaw = false;
    }

    // Yeah, the comments aren't all that helpful. Sorry :c


    // .. and we have data coming in. Most probably from the  user pressing the submit button.
    // we have the data redirect to the same page and handle it.
    if ( isset( $_POST[ 'text' ] ) ) {
        $text = $_POST[ 'text' ];
        if ( !empty( $text ) ) {
            $pasteDeleteKey = AwwCore::CreatePaste ( $text );
            if ( !empty( $pasteDeleteKey ) ) {
                $isPaste = true;
                $redirectUrl = "./?id=" . $pasteDeleteKey[ 'pasteID' ];
                header ( "Location: " . $redirectUrl );

            } else {
                // An error occured while trying to create the paste file?
                die( "Unfortunately, our site died in battle trying to save your paste. <br> May he rust in pieces. " );
                // TODO: Redirect user to an actual error page
            }
        } else {
            // The data is empty. What the fudge?
            die( "Dude. The paste you sent us was empty. Like WTF, man?" );
            // TODO: Redirect user to an actual error page
        }
    }

    // The user is trying to delete a paste.
    if ( isset( $_GET[ 'delete_key' ] ) ) {
        $deleteKey = $_GET[ 'delete_key' ];
        if ( isset( $_GET[ 'id' ] ) ) {
            $pasteId = $_GET[ 'id' ];
            $deleteStatus = AwwCore::DeletePaste ( $pasteId, $deleteKey );
            $isDelete = true;
        } else {

        }
        // The user is trying to view a paste.
    } else if ( isset( $_GET[ 'id' ] ) ) {
        $pasteID = $_GET[ 'id' ];
        $pasteText = AwwCore::GetPaste ( $pasteID );
        $pasteDeleteKey = AwwCore::GetDeleteKey ( $pasteID );
        $pasteTime = AwwCore::GetTime($pasteID);

        if ( !empty( $pasteText ) ) {
            $isPaste = true;
            // Does he want the data raw and uncooked?
            if ( isset( $_GET[ 'raw' ] ) and $_GET[ 'raw' ] == true ) {
                $isRaw = true;
            }
        } else {
            $isPaste = false;
            // We couldn't find it :c
            die( "I tried searching everywhere for you paste. Not even mom knows where it is.<br>It's either that the paste was deleted, or you're just seeing things. Probably the latter. " );
            //TODO: If the paste doesn't exist, yet again, error
        }
    }


?>
<?php if ( !$isRaw ) { ?>
<! -- Original Implementation by Aww -->
<! -- Also developed by MonkehParade -->
    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>AwwPaste</title>

        <!-- Load stylesheet for the site -->
        <link rel='stylesheet' href='public/css/styles.css'>

        <!-- Load the syntax highlighter -->
    <?php if ( $isPaste ) { ?>
    <link rel='stylesheet' href='./public/css/default.min.css'>
    <script src='./public/js/highlight.min.js'></script>
    <script src='./public/js/jquery-1.11.2.min.js'></script>
    <script src='./public/js/jquery.timeago.js'></script>
    <script>hljs.initHighlightingOnLoad();</script>
<?php } ?>
        <script src='./public/js/autosize.min.js'></script>
        <script src='./public/js/scripts.js'></script>
    </head>
    <body>
    <?php
    if ( $isPaste ) {
        $urlView = "./?id=" . $pasteID;
        // http://smile.sh/awwbin/?id=12345
        $urlRaw = "./?id=" . $pasteID . "&raw=true";
        // http://smile.sh/awwbin/?id=12345&raw=true
        $urlDelete = "./?id=" . $pasteID . "&delete_key=" . $pasteDeleteKey;
        // http://smile.sh/awwbin/?id=12345&delete_key=a1b2c3d4e5
        ?>
        <p>This paste was created <time class="timeago" datetime="<?php print $pasteTime; ?>">N/A</time></p>
        <div class='code'>
            <pre><code><?php print ( $pasteText ); ?></code></pre>
        </div>

        <table id="links">
            <tr>
                <td><p>URL</p></td>
                <td><a href="<?php print( $urlView ); ?>"><?php print( $urlView ); ?></a></td>
            </tr>
            <tr>
                <td><p>RAW</p></td>
                <td><a href="<?php print( $urlRaw ); ?>"><?php print( $urlRaw ); ?></a></td>
            </tr>
            <tr>
                <td><p>DELETE</p></td>
                <td><a href="<?php print( $urlDelete ); ?>"><?php print( $urlDelete ); ?></a></td>
            </tr>
        </table>
    <?php } else if ( $isDelete ) {
        if ( $deleteStatus ) {
            print( "The paste has successfully been deleted." );
        } else {
            die( "Uh-oh. Is that URL correct? Doesn't seem like it is." );
        }
    } else { ?>

        <form class="form" method="post">
            <div class="field">
                <textarea id="text" name="text" placeholder="Enter what ever you want to paste here.. "></textarea>
                <button class="myButton" type="submit">Paste!</button>
            </div>
        </form>
    <?php } ?>

    </body>
    </html>
<?php } else {
    header ( "Content-Type:text/plain" );
    print( $pasteText );
} ?>