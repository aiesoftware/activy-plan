Feature: Guests can create bookings for themselves and/or other other guests
  In order to take part in activities during my stay
  As a guest of the holiday park
  I want to book places on to create bookings for myself and additional guests

  Scenario: Johnny creates a booking for just himself
    Given a Park named "Sunnyside"
    And the Park has a "Rock Climbing" Activity
    And the Activity has an Activity Slot at "2025-04-30 10:00" lasting "1" hour with a minimum age limit of "16" and "10" places available
    And "Johnny Rogers" is a "16" year old Guest staying at the "Sunnyside" Park between "2025-04-24 16:00" and "2025-05-01 10:00"
    When he creates a Booking for himself on that Activity Slot
    Then the Booking should be added to his Planner
    And that Activity Slot should have "9" places remaining

#    Given an Activity at the "Sunnyside" Park named "Rock Climbing" with a minimum age limit of "16" has an Activity Slot at "2025-04-30 10:00" lasting "-1" hour with "10" places available
#    And "Johnny Rogers" is a "16" year old Guest staying at the "Sunnyside" Park between "2025-04-24 16:00" and "2025-05-01 10:00"
#    When he creates a Booking on that Activity Slot
#    And the Activity Slot should have "9" places remaining
#    And the Booking should be added to his Planner
#
#  Scenario: Johnny attempts to create a booking for an Activity Slot that has insufficient places available
#    Given an Activity at the "Sunnyside" Park named "Scuba Diving" with a minimum age limit of "18" has an Activity Slot at "2025-04-30 10:00" lasting "1 hour" with "0" places available
#    And "Johnny Rogers" is a "18" year old Guest staying at the "Sunnyside" Park between "2025-04-24 16:00" and "2025-05-01 10:00"
#    When he attempts to create a Booking on that Activity Slot that has insufficient places available
#    Then the Activity Slot should have "10" places remaining
#    And the Booking should not be added to his Planner
#
#  Scenario: Johnny attempts to create a booking for an Activity he is too young for
#    Given an Activity at the "Sunnyside" Park named "Scuba Diving" with a minimum age limit of "18" has an Activity Slot at "2025-04-30 10:00" lasting "1 hour" with "10" places available
#    And "Johnny Rogers" is a "17" year old Guest staying at the "Sunnyside" Park between "2025-04-24 16:00" and "2025-05-01 10:00"
#    When he attempts to create a Booking on that Activity that he is "too young for"
#    Then the Activity Slot should have "10" places remaining
#    And the Booking should not be added to his Planner
#
#  Scenario: Johnny attempts to create a booking for an Activity Slot that is outside of his stay dates
#    Given an Activity at the "Sunnyside" Park named "Scuba Diving" with a minimum age limit of "18" has an Activity Slot at "2025-04-30 10:00" lasting "1 hour" with "10" places available
#    And "Johnny Rogers" is a "18" year old Guest staying at the "Sunnyside" Park between "2025-04-22 16:00" and "2025-04-29 10:00"
#    When he attempts to create a Booking on that Activity Slot that is outside of his stay dates
#    Then the Activity Slot should have "10" places remaining
#    And the Booking should not be added to his Planner
#
#  Scenario: Johnny attempts to create a booking for an Activity Slot that is at a different Park
#    Given an Activity at the "Sunnyside" Park named "Scuba Diving" with a minimum age limit of "18" has an Activity Slot at "2025-04-30 10:00" lasting "1 hour" with "10" places available
#    And "Johnny Rogers" is a "18" year old Guest staying at the "Bayside" Park between "2025-04-24 16:00" and "2025-05-01 10:00"
#    When he attempts to create a Booking on that Activity Slot that is outside of his stay dates
#    Then the Activity Slot should have "10" places remaining
#    And the Booking should not be added to his Planner
