<?php
interface iIterator{
	/**
	 * Jumps to the first position of the ressource.
	 */
	public function first();
	/**
	 * Jumps to the next position of the ressource.
	 */
	public function next();
	/**
	 * Jumps to the previous position of the ressource.
	 */
	public function previous();
	/**
	 * Jumps to the last position of the ressource.
	 */
	public function last();
	/**
	 * Checks if the actual item is the last one.
	 */
	public function isLast();
}
