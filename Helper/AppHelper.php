<?php

App::uses('CroogoAppHelper', 'Croogo.View/Helper');

/**
 * Base Application Helper
 *
 * Provee utilidades de serialización JSON seguras para todas las vistas.
 * Heredado por todos los helpers de los plugins (EleccionesHelper, AfigestionHelper, etc.).
 *
 * UBICACIÓN INTENCIONAL: Este archivo vive en Afitheme (repo git propio) y se registra
 * como path de búsqueda en Config/bootstrap.php mediante App::build(), de modo que
 * App::uses('AppHelper', 'View/Helper') lo encuentre automáticamente sin cambios
 * en ningún plugin.
 */
class AppHelper extends CroogoAppHelper {

	/**
	 * Serializa $value a JSON con opciones seguras para HTML.
	 * Decodifica entidades HTML antes de codificar para evitar double-encoding.
	 *
	 * @param  mixed $value
	 * @param  int   $options  Flags adicionales para json_encode
	 * @return string
	 */
	public function jsonEncode($value, $options = 0)
	{
		$normalizedValue = $this->_normalizeJsonValue($value);
		$jsonOptions = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | $options;
		$encoded = json_encode($normalizedValue, $jsonOptions);

		if ($encoded !== false) {
			return $encoded;
		}

		if (defined('JSON_PARTIAL_OUTPUT_ON_ERROR')) {
			$encoded = json_encode($normalizedValue, $jsonOptions | JSON_PARTIAL_OUTPUT_ON_ERROR);
			if ($encoded !== false) {
				return $encoded;
			}
		}

		if (is_array($normalizedValue)) {
			return '[]';
		}

		if (is_object($normalizedValue)) {
			return '{}';
		}

		return 'null';
	}

	/**
	 * Igual que jsonEncode() pero con htmlspecialchars() aplicado al resultado,
	 * listo para usarse en atributos HTML (data-*).
	 *
	 * @param  mixed $value
	 * @param  int   $options
	 * @return string
	 */
	public function jsonAttribute($value, $options = 0)
	{
		return htmlspecialchars($this->jsonEncode($value, $options), ENT_QUOTES, 'UTF-8');
	}

	/**
	 * Normaliza recursivamente un valor para JSON:
	 * - Decodifica entidades HTML en strings (maneja multi-nivel, e.g. &amp;#039; → ')
	 * - Convierte encoding a UTF-8 si es necesario
	 *
	 * @param  mixed $value
	 * @return mixed
	 */
	protected function _normalizeJsonValue($value)
	{
		if (is_array($value)) {
			foreach ($value as $key => $item) {
				$value[$key] = $this->_normalizeJsonValue($item);
			}

			return $value;
		}

		if (is_object($value)) {
			foreach ($value as $key => $item) {
				$value->{$key} = $this->_normalizeJsonValue($item);
			}

			return $value;
		}

		if (!is_string($value)) {
			return $value;
		}

		// Loop decode para manejar entidades multi-nivel (ej: &amp;#039; → &#039; → ')
		do {
			$prev = $value;
			$value = html_entity_decode($value, ENT_QUOTES, 'UTF-8');
		} while ($prev !== $value);

		if (
			function_exists('mb_check_encoding')
			&& function_exists('mb_convert_encoding')
			&& !mb_check_encoding($value, 'UTF-8')
		) {
			$value = mb_convert_encoding($value, 'UTF-8', 'Windows-1252,ISO-8859-1,UTF-8');
		}

		return $value;
	}

}
