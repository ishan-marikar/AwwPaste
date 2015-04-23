<?php

    /**
     * Started on   : 4/14/15 at 11:04 AM
     * Project name : AwwPasteV2
     * Developed by : Aww
     *                MonkehParade
     * When I wrote this, only God and I understood what I was doing.
     * Now, God only knows.
     *
     * For the brave souls who get this far: You are the chosen ones,
     * the valiant knights of programming who toil away, without rest,
     * fixing our most awful code. To you, true saviors, kings of men,
     * I say this: never gonna give you up, never gonna let you down,
     * never gonna run around and desert you. Never gonna make you cry,
     * never gonna say goodbye. Never gonna tell a lie and hurt you.
     */
    require ( "config.inc.php" );

    class AwwCore
    {

        public static function GetPaste ( $pasteID )
        {
            $fileName = PASTE_PATH . $pasteID . ".txt";
            if ( file_exists ( $fileName ) ) {
                $pasteData = file_get_contents ( $fileName );

                return $pasteData;
            } else {
                return false;
            }
        }

        public static function CreatePaste ( $fileContents )
        {
            // Variables to store the id and the key
            $pasteID = static::GetRandomString ( 5 );
            $deleteKey = static::GetRandomString ( 10 );

            // Variables to store the filename and location
            $filename = PASTE_PATH . $pasteID . ".txt";
            $pasteInfoFilename = PASTE_PATH . $pasteID . ".json";

            // Sanitize the data
            // Not complete, but this will do for now
            // TODO: Offload sanitization into a method
            $fileContents = htmlspecialchars ( $fileContents );

            $currentTime = date('Y-m-d\TH:i:s');
            // Create the JSON file that will store the deletion key
            $fileInfo = array (
                "delKey" => $deleteKey,
                "time" => $currentTime
            );
            $pasteInfoContents = json_encode ( $fileInfo );


            // Finally put all the data we have to where it belongs
            file_put_contents ( $filename, $fileContents );
            file_put_contents ( $pasteInfoFilename, $pasteInfoContents );

            // Change file permissions
            chmod ( $filename, 0777 );
            chmod ( $pasteInfoFilename, 0777 );

            $pasteDetails = array (
                "pasteID" => $pasteID,
                "deleteKey" => $deleteKey
            );

            return $pasteDetails;
        }

        private static function GetRandomString ( $length = 10 )
        {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen ( $characters );
            $randomString = '';
            for ( $i = 0; $i < $length; $i++ ) {
                $randomString .= $characters[ rand ( 0, $charactersLength - 1 ) ];
            }

            return $randomString;
        }

        public static function DeletePaste ( $pasteId, $deleteKey )
        {
            $fileName = PASTE_PATH . $pasteId . ".txt";
            $pasteInfoFilename = PASTE_PATH . $pasteId . ".json";

            if ( file_exists ( $fileName ) ) {
                $meta = json_decode ( file_get_contents ( $pasteInfoFilename ) );
                if ( $deleteKey == $meta->{'delKey'} ) {
                    unlink ( $fileName );
                    unlink ( $pasteInfoFilename );

                    return true;
                } else {
                    return false;
                }
            }
        }

        public static function GetDeleteKey ( $pasteId )
        {
            $pasteInfoFilename = PASTE_PATH . $pasteId . ".json";
            if ( file_exists ( $pasteInfoFilename ) ) {
                $meta = json_decode ( file_get_contents ( $pasteInfoFilename ) );
                $deleteKey = $meta->{'delKey'};

                return $deleteKey;
            } else {
                return false;
            }
        }

        public static function GetTime ( $pasteId )
        {
            $pasteInfoFilename = PASTE_PATH . $pasteId . ".json";
            if ( file_exists ( $pasteInfoFilename ) ) {
                $meta = json_decode ( file_get_contents ( $pasteInfoFilename ) );
                $time = $meta->{'time'};

                return $time;
            } else {
                return false;
            }
        }

        public static function IsPasteExist ( $pasteId )
        {
            $fileName = PASTE_PATH . $pasteId . ".txt";

            if ( file_exists ( $fileName ) ) {
                return true;
            } else {
                return false;
            }
        }

        private static function GetRandomStringV2 ( $required_length = 10 )
        {
            $limit_one = rand ();
            $limit_two = rand ();
            $randomID =
                substr (
                    uniqid (
                        sha1 (
                            crypt (
                                md5 (
                                    rand (
                                        min ( $limit_one, $limit_two ), max ( $limit_one, $limit_two )
                                    )
                                )
                            )
                        )
                    ), 0, $required_length );

            // OH MY WORD! :|
            return $randomID;
        }
    }