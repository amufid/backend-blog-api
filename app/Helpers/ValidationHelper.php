<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Validator;

class ValidationHelper
{
   public static function validateDataPost($data)
   {
      $validator = Validator::make($data, [
         'title' => 'required|string|max:255',
         'content' => 'required|string',
         'category_id' => 'required|exists:categories,id',
      ]);

      if ($validator->fails()) {
         return $validator->errors();
      }

      // Jika validasi berhasil 
      return null;
   }

   public static function validateDataCategory($data)
   {
      $validator = Validator::make($data, [
         'name' => 'required|string|min:2|max:255',
      ]);

      if ($validator->fails()) {
         return $validator->errors();
      }

      return null;
   }

   public static function validateDataAttachment($data)
   {
      $validator = Validator::make($data, [
         'post_id' => 'required|min:1|exists:posts,id',
         'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
      ]);

      if ($validator->fails()) {
         return $validator->errors();
      }

      return null;
   }

   public static function validateDataUser($data)
   {
      $validator = Validator::make($data, [
         'name' => 'required|string|min:2|max:255',
         'email' => 'required|string|email|min:5|max:255|unique:users',
         'password' => 'required|string|min:7',
      ]);

      if ($validator->fails()) {
         return $validator->errors();
      }

      return null;
   }

   public static function validateUpdateUser($data, $id)
   {
      $validator = Validator::make($data, [
         'name' => 'required|string|min:2|max:255',
         'email' => 'required|email|unique:users,email,' . $id,
         'password' => 'nullable|string|min:7|confirmed',
      ]);

      if ($validator->fails()) {
         return $validator->errors();
      }

      return null;
   }
}
