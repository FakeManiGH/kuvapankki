<?php 

// Regular expressions for input validation

// Username
$patternUser = "/^.{5,35}$/";

// First name
$patternFirst = "/^[a-zåäöA-ZÅÄÖ0'\- ]{1,50}$/";

// Last name
$patternLast = "/^[a-zåäöA-ZÅÄÖ0'\- ]{1,50}$/";

// Password
$patternPwd = "/^.{12,}$/";

// Password confirmation
$patternPwd2 = $patternPwd;

// Phone number
$patternPhone = "/^[0-9]{7,15}$/";

// Email
$patternEmail = "/^[a-zA-Z_]+[a-zA-Z0-9_\.+-]*@[a-zA-Z_]+(\.[a-zA-Z0-9_-]{2,})?\.[a-zA-Z]{2,}$/";

// Gallery name
$patternGallery = "/^.{1,75}$/";

// Gallery description
$patternDesc = "/^.{1,400}$/";

// Gallery tags
$patternTags = "/^.{0,100}$/";