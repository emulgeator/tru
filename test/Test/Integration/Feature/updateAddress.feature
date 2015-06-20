Feature: This action should update the address with the given id.


	Scenario: When called with a nonexistent id 404 should be returned
		When I call the update address with the id "1" and data
			| name | phone  | street |
			| name | 123421 | test 1 |
		Then the http status of the response should be "404"


	Scenario Outline: When called with invalid parameters
		When I call the update address with the id "1" and data
			| name   | phone   | street   |
			| <name> | <phone> | <street> |
		Then the http status of the response should be "400"

		Examples:
			| name | phone   | street |
			|      | 12312   | test 1 |
			| test | invalid | test 1 |
			| test | 12312   |        |


	Scenario: When called with an existent id, the corresponding address be updated only
		Given The following addresses exist:
			| name  | phone | street  |
			| name  | 1234  | street1 |
			| nameB | 1234  | street1 |
		When I call the update address with the id "1" and data
			| name | phone | street |
			| test | 5678  | test 1 |
		Then the http status of the response should be "204"
			And the address with the id "1" should be modified
