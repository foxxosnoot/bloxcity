<?php

use App\Models\SiteSettings;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

function storage($file)
{
    return config('blox.domains.storage') . '/' . $file;
}

function uploadAsset($type, $id, $filetype, $uploadedFile)
{
    switch ($type) {
        case 1: case 'hat': $dir = 'hats'; break;
        case 2: case 'face': $dir = 'faces'; break;
        case 3: case 'accessory': $dir = 'accessories'; break;
        case 4: case 't-shirt': $dir = 't-shirts'; break;
        case 5: case 'shirt': $dir = 'shirts'; break;
        case 6: case 'pants': $dir = 'pants'; break;
        case 7: case 'head': $dir = 'heads'; break;
        default: return false; break;
    }

    $dir = config('blox.renderer_asset_dir') . '/' . $dir;
    $filename = $id . '.' . $filetype;

    return Storage::disk('renderer')->putFileAs($dir, $uploadedFile, $filename);
}

function deleteAsset($filename)
{
    return Storage::disk('renderer')->delete($filename);
}

function getAsset($type, $id)
{
    switch ($type) {
        case 1: case 'hat': $dir = 'hats'; break;
        case 2: case 'face': $dir = 'faces'; break;
        case 3: case 'accessory': $dir = 'accessories'; break;
        case 4: case 't-shirt': $dir = 't-shirts'; break;
        case 5: case 'shirt': $dir = 'shirts'; break;
        case 6: case 'pants': $dir = 'pants'; break;
        case 7: case 'head': $dir = 'heads'; break;
        default: return false; break;
    }

    $dir = config('blox.renderer_asset_dir') . '/' . $dir;

    return storage($dir .'/'. $id .'.png');
}

function render($type, $id)
{
    if ($type != 'user' && $type != 'item') {
        return false;
    }

    $response = Http::get(config('blox.renderer_ip') . '&type=' . $type . '&id=' . $id);

    return $response->successful();
}

function settings($key)
{
    $settings = SiteSettings::where('id', '=', 1)->first();
    return $settings->{$key};
}

function rgb2hex($rgb)
{
    switch ($rgb) {
        // Brown
        case '141/255,85/255,36/255':
            return '#8d5524';
            break;
        // Light Brown
        case '198/255,134/255,66/255':
            return '#c68642';
            break;
        // Lighter Brown
        case '224/255,172/255,105/255':
            return '#e0ac69';
            break;
        // Lighter Lighter Brown
        case '241/255,194/255,125/255':
            return '#f1c27d';
            break;
        // Lighter Lighter Brown
        case '252/255,225/255,213/255':
            return '#fce1d5';
            break;

        // Salmon
        case '241/255,157/255,154/255':
            return '#f19d9a';
            break;
        // Blue
        case '118/255,159/255,202/255':
            return '#769fca';
            break;
        // Light Blue
        case '162/255,209/255,230/255':
            return '#a2d1e6';
            break;
        // Purple
        case '160/255,139/255,208/255':
            return '#a08bd0';
            break;
        // Dark Purple
        case '49/255,43/255,76/255':
            return '#312b4c';
            break;

        // Dark Green
        case '4/255,99/255,6/255':
            return '#046306';
            break;
        // Green
        case '27/255,132/255,44/255':
            return '#1b842c';
            break;
        // Yellow
        case '247/255,177/255,85/255':
            return '#f7b155';
            break;
        // Orange
        case '247/255,144/255,57/255':
            return '#f79039';
            break;
        // Red
        case '255/255,0/255,0/255':
            return '#ff0000';
            break;

        // Light Pink
        case '248/255,163/255,213/255':
            return '#f8a3d5';
            break;
        // Pink
        case '255/255,14/255,154/255':
            return '#ff0e9a';
            break;
        // White
        case '255/255,255/255,255/255':
            return '#f1efef';
            break;
        // Gray
        case '125/255,125/255,125/255':
            return '#7d7d7d';
            break;
        // Black
        case '0/255,0/255,0/255':
            return '#000';
            break;
    }
}

function isProfanity($word)
{
    $blacklistWords = explode(',', config('blox.filter-blacklist'));
    $whitelistWords = explode(',', config('blox.filter-whitelist'));

    $tempContent = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '', $word));
    $tempContent = preg_replace('/\s+/', ' ', $tempContent);
    $wordsRegex = ('('.implode('|', $blacklistWords).')');
    $violatingWords = [];
    $flag = false;

    foreach ($blacklistWords as $blword) {
        if ((stripos($tempContent, $blword) !== FALSE || stripos($word, $blword) !== FALSE) && (arrayInString($whitelistWords, $word) == 0)) {
            $flag = true;
            $violatingWords[] = $blword;
        }
    }

    return $flag;
}

function arrayInString($inArray, $inString)
{
    if (is_array($inArray)) {
        foreach($inArray as $e) {
            if (strpos($inString, $e) !== false) {
                return true;
            }
        }

        return false;
    } else {
        return (strpos($inString, $inArray) !== false);
    }
}

/**
 * Blade Functions
 */
function mailto($email)
{
    switch ($email) {
        case 'support': $email = config('blox.emails.support'); break;
        case 'moderation': $email = config('blox.emails.moderation'); break;
        case 'careers': $email = config('blox.emails.careers'); break;
        case 'payments': $email = config('blox.emails.payments'); break;
        default: $email = $email; break;
    }

    return '<a href="mailto:'. $email .'">'. $email .'</a>';
}
