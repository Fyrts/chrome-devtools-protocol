<?php

namespace ChromeDevtoolsProtocol\Model\Extensions;

use ChromeDevtoolsProtocol\Exception\BuilderException;

/**
 * @generated This file has been auto-generated, do not edit.
 *
 * @author Jakub Kulhan <jakub.kulhan@gmail.com>
 */
final class GetStorageItemsRequestBuilder
{
	private $id;
	private $storageArea;
	private $keys;


	/**
	 * Validate non-optional parameters and return new instance.
	 */
	public function build(): GetStorageItemsRequest
	{
		$instance = new GetStorageItemsRequest();
		if ($this->id === null) {
			throw new BuilderException('Property [id] is required.');
		}
		$instance->id = $this->id;
		if ($this->storageArea === null) {
			throw new BuilderException('Property [storageArea] is required.');
		}
		$instance->storageArea = $this->storageArea;
		$instance->keys = $this->keys;
		return $instance;
	}


	/**
	 * @param string $id
	 *
	 * @return self
	 */
	public function setId($id): self
	{
		$this->id = $id;
		return $this;
	}


	/**
	 * @param string $storageArea
	 *
	 * @return self
	 */
	public function setStorageArea($storageArea): self
	{
		$this->storageArea = $storageArea;
		return $this;
	}


	/**
	 * @param string[]|null $keys
	 *
	 * @return self
	 */
	public function setKeys($keys): self
	{
		$this->keys = $keys;
		return $this;
	}
}
