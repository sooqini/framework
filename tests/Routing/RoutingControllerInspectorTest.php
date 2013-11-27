<?php

class RoutingControllerInspectorTest extends PHPUnit_Framework_TestCase {

	public function testMethodsAreCorrectlyDetermined()
	{
		$inspector = new Illuminate\Routing\ControllerInspector;
		$data = $inspector->getRoutable('RoutingControllerInspectorStub', 'prefix');

		$this->assertEquals(4, count($data));
		$this->assertEquals(array('verb' => 'get', 'plain' => 'prefix', 'uri' => 'prefix'), $data['getIndex'][1]);
		$this->assertEquals(array('verb' => 'get', 'plain' => 'prefix/index', 'uri' => 'prefix/index'), $data['getIndex'][0]);
		$this->assertEquals(array('verb' => 'get', 'plain' => 'prefix/foo-bar', 'uri' => 'prefix/foo-bar/{foo}/{bar?}'), $data['getFooBar'][0]);
		$this->assertEquals(array('verb' => 'post', 'plain' => 'prefix/baz', 'uri' => 'prefix/baz/{baz}'), $data['postBaz'][0]);
		$this->assertEquals(array('verb' => 'get', 'plain' => 'prefix/breeze', 'uri' => 'prefix/breeze'), $data['getBreeze'][0]);
	}

	public function testMethodsAreCorrectWhenControllerIsNamespaced()
	{
		$inspector = new Illuminate\Routing\ControllerInspector;
		$data = $inspector->getRoutable('\\RoutingControllerInspectorStub', 'prefix');

		$this->assertEquals(4, count($data));
		$this->assertEquals(array('verb' => 'get', 'plain' => 'prefix', 'uri' => 'prefix'), $data['getIndex'][1]);
		$this->assertEquals(array('verb' => 'get', 'plain' => 'prefix/index', 'uri' => 'prefix/index'), $data['getIndex'][0]);
		$this->assertEquals(array('verb' => 'get', 'plain' => 'prefix/foo-bar', 'uri' => 'prefix/foo-bar/{foo}/{bar?}'), $data['getFooBar'][0]);
		$this->assertEquals(array('verb' => 'post', 'plain' => 'prefix/baz', 'uri' => 'prefix/baz/{baz}'), $data['postBaz'][0]);
	}

}

class RoutingControllerInspectorBaseStub {
	public function getBreeze() {}
}

class RoutingControllerInspectorStub extends RoutingControllerInspectorBaseStub {
	public function getIndex() {}
	public function getFooBar($foo, $bar = null) {}
	public function postBaz($baz) {}
	protected function getBoom() {}
}