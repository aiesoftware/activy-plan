#Feature: Guests can book themselves and additional guests onto activity slots
#  In order to take part in activities during my stay
#  As a guest of the holiday park
#  I want to book places on to activity slots for myself and additional guests

#  Scenario: Johnny reserves a booking on an activity slot for just himself
#    Given an activity named "rock climbing" with a minimum age limit of "16" has an activity slot at "2025-04-30 10:00" lasting "1 hour" with "10" places available
#    And "Johnny Rogers" is a "16" year old guest staying at the park between "2025-04-24 16:00" and "2025-05-01 10:00"
#    Then he can reserve a booking on that activity slot for "10" minutes
#    And the activity slot should have "9" places remaining
#
#  Scenario: Johnny attempts to reserve a booking on an activity slot that he is too young for
#    Given an activity named "scuba diving" with a minimum age limit of "18" has an activity slot at "2025-04-30 10:00" lasting "1 hour" with "10" places available
#    And "Johnny Rogers" is a "17" year old guest staying at the park between "2025-04-24 16:00" and "2025-05-01 10:00"
#    Then he cannot reserve a booking on that activity slot
#    And the activity slot should have "10" places remaining
#
#  Scenario: David books an activity slot for himself and several others
#  Scenario: David attempts to book an activity slot that an additional guest is too young for
