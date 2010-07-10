<?php

	require_once 'ApiTestBase.php';
	 
	class EventGetList extends ApiTestBase {

		public function testGetListUpcomingWithAuth() {
			// $this->markTestIncomplete('hang fire with the json version for a moment');

			$response = self::makeApiRequest('event', 'getlist', array('event_type'=>'upcoming'));

			$res = $this->decode_response($response, 'json');
			$this->assertTrue( $res !== null, "Could not decode JSON response");
			$this->assertExpectedFields($res);
		}

		protected function assertExpectedFields($res) {
			foreach($res as $event) {
				$this->assertType(PHPUnit_Framework_Constraint_IsType::TYPE_OBJECT, $event);
				$this->assertLooksLikeAString($event->event_name);
				$this->assertLooksLikeAString($event->event_start);
				$this->assertLooksLikeAString($event->event_end);
				$this->assertLooksLikeAString($event->ID);
				$this->assertLooksLikeAString($event->event_loc);
				$this->assertLooksLikeAString($event->event_desc);
				$this->assertLooksLikeAString($event->active);
				$this->assertLooksLikeAString($event->num_attend);
				$this->assertLooksLikeAString($event->num_comments);

				$this->assertLooksLikeAStringOrNull($event->event_stub);
				$this->assertLooksLikeAStringOrNull($event->event_icon);
				$this->assertLooksLikeAStringOrNull($event->event_cfp_start);
				$this->assertLooksLikeAStringOrNull($event->event_cfp_end);
				$this->assertLooksLikeAStringOrNull($event->event_hashtag);
				$this->assertLooksLikeAStringOrNull($event->event_href);
				$this->assertLooksLikeAStringOrNull($event->event_tz_cont);
				$this->assertLooksLikeAStringOrNull($event->event_tz_place);
				$this->assertLooksLikeAStringOrNull($event->event_cfp_start);
				$this->assertLooksLikeAStringOrNull($event->event_cfp_end);
				if((string)$event->event_tz_cont && (string)$event->event_tz_place) {
					$tz = $event->event_tz_cont.'/'.$event->event_tz_place;
					$tzObj = new DateTimeZone($tz);
					$this->assertTrue($tzObj instanceOf DateTimeZone);
				}

				$this->assertTrue(is_numeric((string)$event->event_start));
				$this->assertTrue(is_numeric((string)$event->event_end));
				$this->assertTrue(is_numeric((string)$event->event_cfp_start));
				$this->assertTrue(is_numeric((string)$event->event_cfp_end));
				$this->assertTrue(is_numeric((string)$event->num_attend));
				$this->assertTrue(is_numeric((string)$event->num_comments));

				$this->assertTrue(
									$event->active === '1', "Expected active to be 1 for " . $event->event_name . "(" . $event->ID . ")"
				);
				$this->assertTrue(
									$event->pending === '0'
									|| $event->pending === null, "Expected pending to be 0 or empty for " . $event->event_name . "(" . $event->ID . ")"
				);
				$this->assertTrue(
									$event->event_voting === 'Y'
									|| $event->event_voting === '0'
									|| $event->event_voting === null
				);
				$this->assertTrue(
									$event->private === 'N'
									|| $event->private === null
				);
				/*
				$this->assertTrue(
									$event->allow_comments === '0', "Failed asserting comments not allowed on " . $event->event_name ."(" . $event->ID .")"
				);
				*/
				$this->assertTrue(
									$event->user_attending === false
									|| $event->user_attending === true
				);
			}
		}

		public function testGetListUpcomingWithAuthXML() {

			$response = self::makeApiRequest('event', 'getlist', array('event_type'=>'upcoming'), 'xml');

			$res = $this->decode_response($response, 'xml');

			$this->assertTrue( $res !== false, "Could not decode XML response");
			$this->assertExpectedFields($res);
		}
	}
