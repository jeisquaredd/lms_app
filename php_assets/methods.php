<?php

//Method that saves the user information to the database

function signupUser($firstname, $lastname, $birthday, $email, $sex, $phone, $password, $profile_picture_path)
{
    $con = $this->opencon();
    try {
        $con->beginTransaction();
        
        // Insert into Users table
        $stmt = $con->prepare("INSERT INTO Users (user_FN, user_LN, user_birthday, user_sex, user_email, user_phone, user_password) VALUES (?, ?, ?, ? ,? ,?, ?)");
        $stmt->execute([$firstname, $lastname, $birthday, $sex,  $email, $phone, $password]);
        
        // Get the newly inserted user_id
        $userId = $con->lastInsertId();
        
        // Insert into users_pictures table
        $stmt = $con->prepare("INSERT INTO users_pictures (user_id, user_pic_url) VALUES (?, ?)");
        $stmt->execute([$userId, $profile_picture_path]);
        
        $con->commit();
        return $userId; // return user_id for further use (like inserting address)
    } catch (PDOException $e) {
        $con->rollBack();
        return false;
    }
}

//Method that saves the user's address information to the database
function insertAddress($user_id, $street, $barangay, $city, $province)
{
    $con = $this->opencon();
    try {
        $con->beginTransaction();
        
        // Insert into Address table
        $stmt = $con->prepare("INSERT INTO Address (ba_street, ba_barangay, ba_city, ba_province) VALUES (?, ?, ?, ?)");
        $stmt->execute([$street, $barangay, $city, $province]);
        
        // Get the newly inserted address_id
        $addressId = $con->lastInsertId();
        
        // Link User and Address into Users_Address table
        $stmt = $con->prepare("INSERT INTO Users_Address (user_id, address_id) VALUES (?, ?)");
        $stmt->execute([$user_id, $addressId]);
        
        $con->commit();
        return true;
    } catch (PDOException $e) {
        $con->rollBack();
        return false;
    }
}