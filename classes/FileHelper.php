<?php

/**
 * Class FileHelper
 *
 * @author Yuriy Stos
 */
class FileHelper
{
    /**
     * Copy file from source path to destination
     *
     * @param $source - Copy from
     * @param $destination - Copy to
     * @throws Exception - Throws when source file not found or cannot copy to destination
     */
    public static function copyFile($source, $destination)
    {
        if (!file_exists($source)) {
            throw new Exception("Source file \"$source\" doesn't exist");
        }

        if (!copy($source, $destination)) {
            throw new Exception("Cannot copy file from \"$source\" to \"$destination\"");
        }
    }
}