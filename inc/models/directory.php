<?php

Class Model_Directory
{
	// rufe alle profile des benutzers ab
	function get_user_profiles($OC, $user_id)
	{
		$profiles = $OC->db->fetch_all('
			SELECT
				*
			FROM
				profiles
			WHERE
				user_id = :user_id',
			$args = [
				':user_id' => $user_id
			]);
		return $profiles;
	}

	// beantrage das erstellen eines neuen profils mit dem angegeben key
	function request_profile_id($OC, $key)
	{
		$OC->db->query('
			INSERT INTO
				profiles
			SET
				user_id = :user_id,
				profile_key = :profile_key,
				is_online = :is_online
				',
			[
				':profile_key' => $key,
				':user_id' => 1,
				 ':is_online' => 1
			]);
		// hole die ID des eben erstellten profils (was man auch eleganter machen kann bzw. sollte, um bei vielen gleichzeitigen Zugriffen nicht die falsche ID zu bekommen)
		$id = $OC->db->fetch('
			SELECT
				profile_id
			FROM
				profiles
			ORDER BY
				profile_id DESC
			LIMIT 0,1
			',
			[]);
		return $id['profile_id'];
	}
}