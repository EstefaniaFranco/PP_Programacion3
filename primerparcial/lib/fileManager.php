<?php

class FileManager
{
    public static function Save($archivo, $objeto)
    {
        // LEEMOS
        $arrayJSON = FileManager::Read($archivo);
        array_push($arrayJSON, $objeto);

        // ESCRIBIMOS
        $file = fopen($archivo, 'w');

        $rta = fwrite($file, json_encode($arrayJSON));

        fclose($file);

        return $rta;
    }


    static public function Read($archivo)
    {
        $file = fopen($archivo, 'a+');
        $arrayJSON = [];

        if (filesize($archivo) > 0) {
            $arrayString = fread($file, filesize($archivo));
            $arrayJSON = json_decode($arrayString);
        }

        fclose($file);
        return $arrayJSON;
    }

}
