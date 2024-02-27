<?php
// Path: includes/register.php
// This file contains functions for user registration.

if (isset($_SESSION['form_data'])) {
		$form_data = $_SESSION['form_data'];
	} else {
		header("Location: ../rekisteroidy.php");
		exit();
	}

	$username = $form_data['username'];
	$first_name = $form_data['first_name'];
	$last_name = $form_data['last_name'];
	$phone = $form_data['phone'];
	$email = $form_data['email'];
	$pwd = $form_data['pwd'];
	$email_token = md5(rand(25, 50));
	
	try {
		include "connections.php";
		require_once "register_model.php";
		require_once "send_email_model.php";

		// Tehdään salasanan hashaus
		$hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

		// Luodaan uusi käyttäjä tietokantaan
		create_user($pdo, $username, $first_name, $last_name, $phone, $email, $hashedPwd, $email_token);
		
		// Nollataan tietokannan yhteys
		$pdo = null;
		$stmt = null;

		// Lähetetään sähköposti käyttäjälle
		send_verification_email($username, $email, $email_token);

		// Poistetaan lomakkeen tiedot sessiosta
		unset($_SESSION['form_data']);
	
		// Asetetaan rekisteröinti onnistuneeksi
		$_SESSION['register_status'] = "Rekisteröinti onnistui! Tervetluoa Kuvapankkiin, " . $username . "!";

		// Puhdistetaan lomakkeen tiedot
		unset($form_data);
		unset($email_token);

		// Ohjataan käyttäjä vahvistussivulle
		header("Location: vahvistukset.php?rekisteröinti=onnistui!");

		exit();
		
	} catch (PDOException $e) {
		die("Rekisteröinti epäonnistui: " . $e->getMessage());
	}

