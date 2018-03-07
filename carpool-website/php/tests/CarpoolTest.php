<?php
use PHPUnit\Framework\TestCase;
require_once("../DBConn.php");
require_once("../ModelMyRides.php");
require_once("../ModelMyRides2.php");
require_once("../ModelMyRides3.php");
require_once("../ModelRating.php");

class CarpoolTest extends TestCase
{

	public function testConnection()
	{
		$db = new DBConn();
		$conn = $db->connect();

		$this->assertNotNull($conn);
	}

	public function testMessage()
	{
		$db = new DBConn();
		$conn = $db->connect();
		$model= new ModelMyRides();
		// message_id=1, thread_name=user1@gmail.com
		$result = $model->getMessage($conn,1);

		$this->assertEquals("user1@gmail.com",$result[0]["thread_name"]);
	}

	public function testGetRouteOwner()
	{
		$db = new DBConn();
		$conn = $db->connect();
		$model= new ModelMyRides();
		// route_id=1, owner=fernie255@yahoo.com
		$result = $model->getRouteOwner($conn,1);

		$this->assertEquals("fernie255@yahoo.com",$result);
	}

	public function testIsPassenger()
	{
		$db = new DBConn();
		$conn = $db->connect();
		$model= new ModelMyRides2();
		// route_id=14
		$result = $model->isPassenger($conn,14,"user2@gmail.com");

		$this->assertTrue($result);
	}

	public function testGetThreadOtherUser()
	{
		$db = new DBConn();
		$conn = $db->connect();
		$model= new ModelMyRides2();
		// route_id=14
		$result = $model->getThreadOtherUser($conn,14,"user2@gmail.com","user2@gmail.com");

		$this->assertEquals("user1@gmail.com",$result);
	}

	public function testGetRide()
	{
		$db = new DBConn();
		$conn = $db->connect();
		$model= new ModelMyRides3();
		// route_id=1
		$result = $model->getRide($conn,1);

		$this->assertEquals("fernie255@yahoo.com",$result[0]["email"]);
	}

	public function testHaveAllPassengersRated()
	{
		$db = new DBConn();
		$conn = $db->connect();
		$model= new ModelRating();
		// route_id=1
		$result = $model->haveAllPassengersRated($conn,1);

		$this->assertTrue($result);
	}

	public function testGetRating()
	{
		$db = new DBConn();
		$conn = $db->connect();
		$model= new ModelRating();
		// user=user1@gmail.com
		$result = $model->getRating($conn,"user1@gmail.com");

		$this->assertEquals("3.33",$result);
	}

}
