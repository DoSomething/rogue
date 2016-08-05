<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Rogue\Services\Phoenix\Phoenix;

class PhoenixTest extends TestCase
{
	/**
	* Test that reportback successfully posts back to Phoenix.
	*
	* @return void
	*/
	public function testPostingReportback()
	{
		$phoenix = new Phoenix();
		$body = [
			'quantity' => 30,
			'uid' => 1704953,
			'file_url' => 'https://s-media-cache-ak0.pinimg.com/736x/ec/68/65/ec6865940ab8066ef16a41261f2389e1.jpg',
			'why_participated' => 'Test',
			'caption' => 'Test',
			'source' => 'Mobile App'
		];

		$response = $phoenix->postReportback(1631, $body);

		$this->assertTrue($response, 'Response is false');
	}
}
