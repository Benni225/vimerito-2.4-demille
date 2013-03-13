<?php
interface iQuerybuilder{
	public static function select();
	public static function where();
	public static function orderBy();
	public static function limit();
	public static function update();
	public static function delete();
	public static function from();
}
