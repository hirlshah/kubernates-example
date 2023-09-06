<?php

namespace App\Classes\Helper;

use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use FFMpeg;
use Image;
use File;
use Str;
use Log;

/**
 * Helper class for retrieving menus
 */
class CommonUtil
{

    /**
     * Helper metho to create directory if not exist
     *
     * @param $path
     *
     * @return bool
     */
    public static function createDirIfNotExist( $path ) {
        if (! File::exists(public_path($path))) {
            if(File::makeDirectory(public_path($path),0777,true)){
                return true;
            } else {
                return false;
            }
        } else{
            return true;
        }
    }

	/**
	 * Get Image Url
	 *
	 * @param $path
	 *
	 * @return string
	 */
	public static function getUrl($path) {
		return Storage::disk('public')->url($path);
	}

    /**
     * Remove File Form Folder
     *
     * @param $path
     *
     * @return bool
     */
    public static function removeFile( $path ) {
        Storage::disk('public')->delete($path);
        return true;
    }

    /**
     * Upload File Form Folder
     *
     * @param $file
     * @param $folder
     *
     * @return bool
     */
    public static function uploadFileToFolder( $file, $folder ) {
        $path = Storage::disk('public')->putFile($folder, $file);
        return $path;
    }

	/**
	 * Date Convert To Add Database
	 *
	 * @param $date
	 *
	 * @return string
	 */
    public static function dateForDatabase($date){
	    $date = Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d');
	    return $date;
    }

	/**
	 * Delete Element from Multidimensional Array based on value
	 *
	 * @param $array
	 * @param $key
	 * @param $value
	 *
	 * @return mixed
	 */
	public static function removeElementWithValue($array, $key, $value){
		$array = json_decode(json_encode($array), true);
		foreach($array as $subKey => $subArray){
			if($subArray[$key] == $value){
				unset($array[$subKey]);
			}
		}
		$array = array_values($array);
		$array = json_decode( json_encode( $array ) );
		return $array;
	}

	/**
	 * Get attachment type
	 *
	 * @param file $file
	 *
	 * @return string
	 */
    public static function getAttachmentType($file) {
	    $mimeType = $file->getMimeType();
	    $type = explode('/', $mimeType);
	    $fileType = '';
	    if(isset($type[0])) {
	    	$fileType = $type[0];
	    	if($type[0] == 'application') {
	    		$fileType = 'pdf';
	    	}
	    }
	    return $fileType;
    }

	/**
     * Upload Thumbnails File Form Folder.
     *
     * @param $file , $folder
     *
     * @return bool
     */
    public static function generateThumbnails($file, $folder)
    {       
		$imgFile    = Image::make($file)->resize(100, 100, function ($constraint) {
			$constraint->aspectRatio();
		})->encode($file->extension());
		$name = rand(0,999999)."_".time();
		Storage::disk( 'public' )->put( $folder. '/' . $name, (string) $imgFile, 'public' );
		$fileName = $folder . '/' . $name;
        
        return $fileName;
    }

	/**
	 * Upload Thumbnail File Form Folder Script
	 *
	 * @param $path
	 * @param $folder
	 * @param $name
	 * @param $extension
	 *
	 * @return array
	 */
	public static function uploadThumbnailFileToFolderScript($path, $folder, $name , $extension)
	{
		$fileName = Null;
		if(Storage::disk('public')->exists($path)) {
			$destinationPath = Storage::disk('public')->path( $folder );
			$file            = Storage::disk('public')->url( $path );
			$imgFile    = Image::make($file)->resize(100, 100, function ($constraint) {
				$constraint->aspectRatio();
			})->encode($extension);
			Storage::disk('public')->put( $folder . '/' . $name, (string) $imgFile, 'public' );
			$fileName = $folder . '/' . $name;
		}
        return $fileName;
	}

	/**
     * Upload file from url
     *
     * @param $file
     *
     * @return bool
     */
    public static function uploadFileFromUrl($file, $folder)
	{
	    $name = rand(0, 999999) . "_" . time();
	    $imageData = file_get_contents($file);
	    if($imageData === false) {
	        throw new \Exception('Failed to download image from URL.');
	    }
	    Storage::disk('public')->put($folder . '/' . $name . '.jpg', $imageData, 'public');
	    $fileName = $folder . '/' . $name . '.jpg';
	    return $fileName;
	}
}
