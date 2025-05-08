<?php
function handleFileUpload($file)
{
    $target_dir = "uploads/";
    $original_file_name = basename($file["name"]);
    $imageFileType = strtolower(pathinfo($original_file_name, PATHINFO_EXTENSION));

    $check = getimagesize($file["tmp_name"]);
    if ($check === false) return false;

    if ($file["size"] > 500000) return false;

    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($imageFileType, $allowed_types)) return false;

    $new_file_name = pathinfo($original_file_name, PATHINFO_FILENAME) . '_' . time() . '.' . $imageFileType;
    $target_file = $target_dir . $new_file_name;

    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return $target_file;
    }

    return false;
}
