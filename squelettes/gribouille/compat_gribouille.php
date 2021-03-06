<?php
	
if (!defined("_ECRIRE_INC_VERSION")) return;
define('_COMPAT_GRIBOUILLE_192', true);

/* fichier de compatibilite vers spip 1.9.2 */
if (version_compare($GLOBALS['spip_version_code'], '1.9300', '<')
	AND $f = charger_fonction('compat_gribouille', 'inc'))
		$f();

function inc_compat_gribouille_dist($quoi = NULL) {
	if (!function_exists($f = 'compat_gribouille_defs')) $f .= '_dist';
	$defs = $f();

	if (is_string($quoi))
		$quoi = array($quoi);
	else if (is_null($quoi))
		$quoi = array_keys($defs);

	foreach ($quoi as $d) {
		if (!function_exists($d)
		AND isset($defs[$d])) {
			eval ("function $d".$defs[$d]);
		}
	}
}

function compat_gribouille_defs_dist() {
	$defs = array(
		'sql_fetch' => 
			'(
				$res, 
				$serveur=\'\'
			) {
				return spip_fetch_array($res);
			}',
		
		'sql_query' => 
			'($res, $serveur=\'\') {
				return spip_query_db($res);
			}',	
		
		// n'existe pas en 1.9.2
		'sql_alter' => 
			'($res, $serveur=\'\') {
				return spip_query_db(\'ALTER \' . $res);
			}',	
				
		// n'existe pas en 1.9.2
		// on cree la requete directement
		'sql_delete' => 
			'($table, $where=\'\', $serveur=\'\') {
				if (!is_array($table)) $table = array($table);
				if (!is_array($where)) $where = array($where);
				$query = \'DELETE FROM \'
						. implode(\',\', $table)
						. \' WHERE \'
						. implode(\' AND \', $where);
				return spip_query_db($query);
			}',
			
		// sql_quote : _q directement
		'sql_quote' => 
			'(
				$val, 
				$serveur=\'\'
			) {
				return _q($val);
			}',	
						
		'sql_select' => 
			'(
				$select = array(), 
				$from = array(), 
				$where = array(),
				$groupby = array(), 
				$orderby = array(), 
				$limit = \'\', 
				$having = array(),
				$serveur=\'\'
			) {
				return spip_abstract_select(
					$select, 
					$from, 
					$where, 
					$groupby, 
					$orderby, 
					$limit, 
					$limit, 
					$sousrequete = \'\', 
					$having,
					$table = \'\', 
					$id = \'\', 
					$serveur);
			}',
			
		// n'existe pas en 1.9.2
		// on cree la requete directement
		'sql_update' => 
			'(
				$table, 
				$champs, 
				$where=\'\', 
				$desc=array(), 
				$serveur=\'\'
			) {
				if (!is_array($table)) 	$table = array($table);
				if (!is_array($champs)) $champs = array($champs);
				if (!is_array($where)) 	$where = array($where);

				$query = $r = \'\';
				foreach ($champs as $champ => $val)
					$r .= \',\' . $champ . "=$val";
				if ($r = substr($r, 1))
					$query = \'UPDATE \'
							. implode(\',\', $table)
							. \' SET \' . $r
							. (empty($where) ? \'\' :\' WHERE \' . implode(\' AND \', $where));
				if ($query)
					return spip_query_db($query);
			}',

		'sql_updateq' => 
			'(
				$table, 
				$champs, 
				$where=\'\', 
				$desc=array(), 
				$serveur=\'\'
			) {
				if (!is_array($champs)) $exp = array($champs);
				
				foreach ($champs as $k => $val) {
					$champs[$k] = sql_quote($val);
				}
				
				return sql_update(				
					$table, 
					$champs, 
					$where, 
					$desc, 
					$serveur
				);
			}',	
			
		
		// n'existe pas en 1.9.2
		// on cree la requete directement
		'sql_insertq' => 
			'(
				$table,
				$champs
			) {
				if (!is_array($champs)) $exp = array($champs);
				
				foreach ($champs as $k => $val) {
					$champs[$k] = sql_quote($val);
				}
				
				$query = "INSERT INTO $table (".implode(",", array_keys($champs)).") VALUES (".implode(",", $champs).")";
				return sql_query($query);
			}',
		
		'sql_showtable' => '($table, $serveur=\'\') {
			return spip_abstract_showtable($table, \'mysql\', true);
		}'
		

		/*
		'sql_count' => 
			'(
				$res, 
				$serveur=\'\'
			) {
				return spip_mysql_count($res);
			}'
		
		
		'sql_selectdb' => 
			'(
				$res, 
				$serveur=\'\'
			) {
				$GLOBALS[\'spip_mysql_db\'] = mysql_select_db($res);
				return $GLOBALS[\'spip_mysql_db\'];
			}',	
		
		
		*/
	);
	return $defs;
}



?>
