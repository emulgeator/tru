Feature: This action should delete the address with the given id.


	Scenario: When called with a nonexistent id 404 should be returned
		When I call the delete address with the id "1"
		Then the http status of the response should be "404"


	Scenario: When called with an existent id, the corresponding address should be returned
		Given The following addresses exist:
			| name  | phone | street  |
			| name  | 1234  | street1 |
			| nameB | 1234  | street1 |
		When I call the delete address with the id "1"
		Then the http status of the response should be "200"
			And there should be "1" addresses should exist
			And an address with the name "nameB" should exist
