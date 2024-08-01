<?php

use Illuminate\Support\Str;

if (! function_exists('rolePermissionName')) {
    function rolePermissionName($role, $permission) {
        // do something
        $newName = str_replace($role.'_', '', $permission); 
        $newName = ucwords(str_replace('_', ' ', $newName));
        
        if ($newName == 'Show') {
            $newName = 'Show';
        }elseif ($newName == 'Update') {
            $newName = 'Edit';
        }elseif ($newName == 'Revise') {
            $newName = 'Revisions';
        }

        return $newName;
    }
}

if (! function_exists('strStartsWith')) {
	function strStartsWith($haystack, $needle) {
		return Str::startsWith($haystack, $needle);
	}
}