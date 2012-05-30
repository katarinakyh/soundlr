<?php
	$PDO = new PDO("mysql:host=localhost;dbname=music_rating", "root", "");

	function executeQuery($qry, $bnds = NULL, $PDOs) {
		$result = array(
			'error' => null,
			'rows' => null,
			'insert_id' => null,
			'affected_rows' => null
		);
		
		$stmt = $PDOs -> prepare($qry);

		if(!$stmt -> execute($bnds)){
			$result['error'] = $stmt->errorInfo();	
		}

		$result['rows'] = $stmt -> fetchAll(PDO::FETCH_ASSOC);
		$result['insert_id'] = $PDOs -> lastInsertId('id');
		$result['affected_rows'] = $stmt->rowCount();
		return $result;
	}
?>
 